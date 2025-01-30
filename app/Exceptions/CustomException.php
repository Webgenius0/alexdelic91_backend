<?php

namespace App\Exceptions;

use Exception;

class CustomException extends Exception
{
    protected $data;

    public function __construct($message, $code = 400, $data = [])
    {
        parent::__construct($message, $code);
        $this->data = $data;
    }

    public function getData()
    {
        return $this->data;
    }
}
