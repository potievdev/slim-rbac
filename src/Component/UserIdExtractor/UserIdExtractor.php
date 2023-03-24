<?php

namespace Potievdev\SlimRbac\Component\UserIdExtractor;

use Psr\Http\Message\ServerRequestInterface;

interface UserIdExtractor
{
    /**
     * Extracts user id from $request and returns it.
     * @param ServerRequestInterface $request
     * @return string
     */
    public function getUserId(ServerRequestInterface $request): string;

}