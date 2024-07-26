<?php

namespace Cupcake\Authentication;

use Authentication\AuthenticationService;
use Psr\Http\Message\ServerRequestInterface;

class CupcakeAuthenticationService extends AuthenticationService
{
    public function __construct()
    {
        parent::__construct();
        $this->setConfig([
            'unauthenticatedRedirect' => null,
            'queryParam' => null,
        ]);
    }

    public function getUnauthenticatedRedirectUrl(ServerRequestInterface $request): ?string
    {
        return null;
    }
}