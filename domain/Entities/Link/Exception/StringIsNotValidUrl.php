<?php


namespace Domain\Entities\Link\Exception;


use Exception;

class StringIsNotValidUrl extends Exception
{
    protected $message = 'Given URL is not valid';
}
