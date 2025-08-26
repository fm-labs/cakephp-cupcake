<?php
declare(strict_types=1);

namespace Cupcake\Middleware;

use Cake\Core\Configure;
use Cake\Http\Response;
use Cake\Log\Engine\FileLog;
use Cake\Log\Log;
use Cupcake\Exception\SecurityException;
use Cupcake\Util\NetUtil;
use Exception;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * RequestFilter middleware
 *
 * Filters incoming requests based on client IP, allowed hosts, and other security measures.
 */
class RequestFilterMiddleware implements MiddlewareInterface
{
    protected array $_config = [
     'allowedHosts' => [], // List of allowed hostnames
     'allowIpv4' => true, // Whether to allow IPv4 addresses
     'allowIpv6' => true, // Whether to allow IPv6 addresses
     'allowedClientCidrs' => [], // List of allowed client CIDR ranges
    ];

    //protected array $_rules = [];

    public function __construct(array $config = [])
    {
        if (!Log::getConfig('protect')) {
            Log::setConfig('protect', [
                'className' => FileLog::class,
                'path' => LOGS,
                'file' => 'protect',
                //'levels' => ['notice', 'info', 'debug'],
                'scopes' => ['protect'],
            ]);
        }

        $defaultConfig = [
            'allowedHosts' => Configure::read('App.allowedHosts', []),
            'allowIpv4' => Configure::read('App.allowIpv4', true),
            'allowIpv6' => Configure::read('App.allowIpv6', true),
            'allowedClientCidrs' => Configure::read('App.allowedClientCidrs', []),
        ];
        $this->_config = array_merge($defaultConfig, $config);
    }

    /**
     * Process method.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request The request.
     * @param \Psr\Http\Server\RequestHandlerInterface $handler The request handler.
     * @return \Psr\Http\Message\ResponseInterface A response.
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            $clientIp = $request->clientIp();
            $host = $request->host();

            // Check allowed hosts
            $allowedHosts = $this->_config['allowedHosts'];
            if ($allowedHosts && !in_array($host, $allowedHosts, true)) {
                $message = sprintf(
                    '[%s] Host %s not in allowed hosts: %s',
                    $clientIp,
                    $host,
                    implode(', ', $allowedHosts),
                );
                Log::write('error', $message, ['scope' => 'protect']);
                throw new SecurityException($message);
            }

            if ($this->_config['allowIpv4'] === false && NetUtil::isIpv4($clientIp)) {
                $message = sprintf(
                    '[%s] Invalid client IP address format',
                    $clientIp,
                );
                Log::write('error', $message, ['scope' => 'protect']);
                throw new SecurityException($message);
            }

            if ($this->_config['allowIpv6'] === false && NetUtil::isIpV6($clientIp)) {
                $message = sprintf(
                    '[%s] IPv6 addresses are not allowed',
                    $clientIp,
                );
                Log::write('error', $message, ['scope' => 'protect']);
                throw new SecurityException($message);
            }

            $allowedClientCidr = $this->_config['allowedClientCidrs'];
            if ($allowedClientCidr) {
                $found = false;
                foreach ($allowedClientCidr as $cidr) {
                    if (NetUtil::isIpInCidr($clientIp, $cidr)) {
                        $found = true;
                        break;
                    }
                }
                if (!$found) {
                    $message = sprintf(
                        '[%s] Client IP %s not in allowed CIDRs: %s',
                        $clientIp,
                        $clientIp,
                        implode(', ', $allowedClientCidr),
                    );
                    Log::write('error', $message, ['scope' => 'protect']);
                    throw new SecurityException($message);
                }
            }

            //            // Check referer
            //            $referer = $request->referer();
            //            if ($referer) {
            //                $refererHost = parse_url($referer, PHP_URL_HOST);
            //                if ($refererHost && $refererHost !== $request->host()) {
            //                    $message = sprintf(
            //                        '[%s] Invalid referer: %s (host: %s)',
            //                        $clientIp,
            //                        $referer,
            //                        $refererHost
            //                    );
            //                    Log::write('error', $message, ['scope' => 'protect']);
            //                    throw new SecurityException($message);
            //                }
            //            }
        } catch (SecurityException $ex) {
            $message = 'Bad Request';
            if (Configure::read('debug')) {
                $message .= 'Security exception: ' . $ex->getMessage();
            }

            $response = (new Response())
                ->withStatus(400, 'Bad Request')
                ->withStringBody($message);

            return $response;
        } catch (Exception $ex) {
            throw $ex;

//            /** @var \Cake\Http\ServerRequest $request */
//            $clientIp = $request->clientIp();
//            $message = sprintf('[%s] %s', $clientIp, $ex->getMessage());
//            if ($request->referer()) {
//                $message .= ' Referer: ' . $request->referer();
//            }
//            Log::write('error', $message, ['scope' => 'protect']);
//
//            // Render error template
//            $view = new View($request);
//            $view->setTemplatePath('Error');
//            $view->setTemplate('error404');
//            //$view->setLayout("error");
//            $view->set([
//                'message' => __('Page not found'),
//                'url' => (string)$request->getUri(),
//            ]);
//
//            $response = (new Response())
//                ->withStatus(404, 'Not Found')
//                ->withStringBody($view->render());
//
//            return $response;
        }

        return $handler->handle($request);
    }
}
