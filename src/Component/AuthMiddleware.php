<?php

namespace Potievdev\SlimRbac\Component;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Checking Access Middleware
 * Class AuthMiddleware
 * @package Potievdev\SlimRbac\Component
 */
class AuthMiddleware extends BaseComponent
{
    /**
     * Check access
     * @param  ServerRequestInterface                   $request  PSR7 request
     * @param  ResponseInterface                        $response PSR7 response
     * @param  callable                                 $next     Next middleware
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke($request, $response, $next)
    {
        /** @var integer $userId */
        $userId = $request->getAttribute('userId');

        /** @var string $permissionName */
        $permissionName = $request->getUri()->getPath();

        /** @var bool $permitted */
        $permitted = $this->checkAccess($userId, $permissionName);

        /** @var ResponseInterface $response */
        if ($permitted == false) {
            $response = $response->withStatus(403, 'Permission denied');
        } else {
            $response = $next($request, $response);
        }

        return $response;
    }
}