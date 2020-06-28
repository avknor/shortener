<?php


namespace Domain\Entities\Link\Exception;


use Exception;

class WrongVisitorIpFormat extends Exception
{
    protected $message = 'Visitor IP address must be valid.';
}
