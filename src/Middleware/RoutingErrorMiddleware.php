<?php
declare(strict_types=1);

namespace Cupcake\Middleware;

use Cake\Core\Configure;
use Cake\Http\Response;
use Cake\Log\Engine\FileLog;
use Cake\Log\Log;
use Cake\Routing\Exception\MissingRouteException;
use Cake\View\View;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * RoutingError middleware
 */
class RoutingErrorMiddleware implements MiddlewareInterface
{
    public function __construct()
    {
        if (!Log::getConfig('routing')) {
            Log::setConfig('routing', [
                'className' => FileLog::class,
                'path' => LOGS,
                'file' => 'routing',
                //'levels' => ['notice', 'info', 'debug'],
                'scopes' => ['routing'],
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
            return $handler->handle($request);
        } catch (MissingRouteException $ex) {
            if (Configure::read('debug')) {
                //throw $ex;
            }

            /** @var \Cake\Http\ServerRequest $request */
            $clientIp = $request->clientIp();
            $message = sprintf('[%s] %s', $clientIp, $ex->getMessage());
            if ($request->referer()) {
                $message .= ' Referer: ' . $request->referer();
            }
            Log::write('error', $message, ['scope' => 'routing']);

            // Render error template
            $view = new View($request);
            $view->setTemplatePath('Error');
            $view->setTemplate('error404');
            //$view->setLayout("error");
            $view->set([
                'message' => __('Page not found'),
                'url' => (string)$request->getUri(),
            ]);

            $response = (new Response())
                ->withStatus(404, 'Not Found')
                ->withStringBody($view->render());

            return $response;
        }
    }
}
