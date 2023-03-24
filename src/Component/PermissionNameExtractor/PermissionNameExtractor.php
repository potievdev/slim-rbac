<?php

namespace Potievdev\SlimRbac\Component\PermissionNameExtractor;

use Psr\Http\Message\ServerRequestInterface;

interface PermissionNameExtractor
{
    /**
     * Extracts permission name for checking.
     *
     * @param ServerRequestInterface $request
     * @return string
     */
    public function getPermissionName(ServerRequestInterface $request): string;

}