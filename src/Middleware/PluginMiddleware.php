<?php

namespace Banana\Middleware;

use Banana\Banana;
use Banana\Plugin\PluginLoader;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class PluginMiddleware
{
    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request The request.
     * @param \Psr\Http\Message\ResponseInterface $response The response.
     * @param callable $next The next middleware to call.
     * @return \Psr\Http\Message\ResponseInterface A response.
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, $next)
    {
        //PluginLoader::runAll();

        Banana::getInstance()->run();

        return $next($request, $response);
    }
}