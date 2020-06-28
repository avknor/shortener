<?php

namespace Domain\Entities\Link\Exception;

use Exception;

class IdMustBeValidUuidString extends Exception
{
    protected $message = 'Id must be valid Uuid string.';
}
