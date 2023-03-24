<?php

namespace Potievdev\SlimRbac\Component\UserIdExtractor;

use Psr\Http\Message\ServerRequestInterface;

class CookieUserIdExtractor extends BaseUserIdExtractor implements UserIdExtractor
{
    public function getUserId(ServerRequestInterface $request): string
    {
        $params = $request->getCookieParams();

        return $params[$this->userIdFieldName];
    }
}