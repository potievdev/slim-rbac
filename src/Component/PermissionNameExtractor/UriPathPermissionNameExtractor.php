<?php

namespace Potievdev\SlimRbac\Component\PermissionNameExtractor;

use Psr\Http\Message\ServerRequestInterface;

class UriPathPermissionNameExtractor implements PermissionNameExtractor
{
    /**
     * @inheritDoc
     */
    public function getPermissionName(ServerRequestInterface $request): string
    {
        return $request->getUri()->getPath();
    }
}