<?php

namespace Potievdev\SlimRbac\Component;

use Potievdev\SlimRbac\Structure\AuthOptions;
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
     * @param  ServerRequestInterface $request PSR7 request
     * @param  ResponseInterface $response PSR7 response
     * @param  callable $next Next middleware
     *
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \Exception
     */
    public function __invoke($request, $response, $next)
    {
        $variableName = $this->authOptions->getVariableName();
        $storageType = $this->authOptions->getVariableStorageType();

        /** @var integer $userId */
        switch ($storageType) {

            case AuthOptions::ATTRIBUTE_STORAGE_TYPE:
                $userId = $request->getAttribute($variableName);
                break;

            case AuthOptions::HEADER_STORAGE_TYPE:
                $userId = $request->getHeaderLine($variableName);
                break;

            case AuthOptions::COOKIE_STORAGE_TYPE:
                $params = $request->getCookieParams();
                $userId = $params[$variableName];
                break;
        }

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