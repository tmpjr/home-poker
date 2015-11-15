<?php

namespace TomPloskina\HomePoker;

/**
 * Class InvalidPlayerCountException
 * @package TomPloskina\HomePoker
 */
class InvalidPlayerCountException extends \Exception
{
    /**
     * @return string
     */
    public function __toString()
    {
       return "INVALID: Player count is invalid.";
    }
}