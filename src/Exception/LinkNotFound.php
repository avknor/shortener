<?php


namespace App\Exception;


use Exception;

class LinkNotFound extends Exception
{
    protected $message = 'Link does not exist or was expired.';
}
