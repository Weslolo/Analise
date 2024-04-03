<?php
namespace Utils\api\Errors;

class BaseError
{
    public $error;
    public $message;
    public function __construct($error, $message)
    {
        $this->error = $error;
        $this->message = $message;
    }
}