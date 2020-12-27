<?php

namespace ItForFree\rusphp\PHP\Object\Exception;

use Exception;

/**
 * Class TypeException
 * @property string $message
 * @property int $code
 * @package ItForFree\rusphp\PHP\Object\Exception
 */
class TypeException extends Exception
{
    protected $message, $code;

    public function __construct(string $message = 'передан аргумент не верного типа', int $code = 0)
    {
        $this->message = $message;
        $this->code = $code;
        parent::__construct($message, $code);
    }
}