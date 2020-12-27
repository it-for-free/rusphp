<?php

namespace ItForFree\rusphp\PHP\Object\Exception;

use Exception;

class CountException extends Exception
{
    protected $message, $code;

    public function __construct(string $message = 'Передано неверное количество аргументов',
                                int $code = 0)
    {
        $this->message = $message;
        $this->code = $code;
        parent::__construct($message, $code);
    }
}