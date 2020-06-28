<?php

namespace Domain\Entities\Link\Exception;

use Exception;

class InvalidUrlString extends Exception
{
    protected $message = 'String must be a valid URL.';
}
