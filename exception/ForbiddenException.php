<?php


namespace parthkapatel\phpmvc\exception;


class ForbiddenException extends \Exception
{
    protected $code = 403;
    protected $message = "You don't have permission to access this page";
}