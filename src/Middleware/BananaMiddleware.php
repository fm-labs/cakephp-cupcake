<?php
declare(strict_types=1);

namespace Banana\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class BananaMiddleware
{
    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request The request.
     * @param \Psr\Http\Message\ResponseInterface $response The response.
     * @param callable $next The next middleware to call.
     * @return \Psr\Http\Message\ResponseInterface A response.
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, $next)
    {
        return $next($request, $response);
    }
}
