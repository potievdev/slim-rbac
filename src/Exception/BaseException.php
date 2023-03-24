<?php

namespace Potievdev\SlimRbac\Exception;

use Exception;

class BaseException extends Exception
{
    /** @var array */
    protected $additionalParams;

    /**
     * @return array
     */
    public function getAdditionalParams(): array
    {
        return $this->additionalParams;
    }

}