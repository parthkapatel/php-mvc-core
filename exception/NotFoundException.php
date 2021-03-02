<?php


namespace parthkapatel\phpmvc\exception;


class NotFoundException extends \Exception
{
    protected $code = 404;
    protected $message = "Page Not Found";
}