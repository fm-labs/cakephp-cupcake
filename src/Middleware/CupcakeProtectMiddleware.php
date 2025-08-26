<?php
declare(strict_types=1);

namespace Cupcake\Middleware;

use Cake\Core\Configure;
use Cake\Http\Response;
use Cake\Log\Engine\FileLog;
use Cake\Log\Log;
use Cupcake\Exception\SecurityException;
use Exception;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * RoutingError middleware
 */
class CupcakeProtectMiddleware implements MiddlewareInterface
{
    public function __construct()
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
            $referer = $request->referer();

            // Check allowed hosts
            $host = $request->host();
            $allowedHosts = Configure::read('App.allowedHosts');
            //debug($host);
            //debug($allowedHosts);
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
