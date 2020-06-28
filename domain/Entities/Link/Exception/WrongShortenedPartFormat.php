<?php

namespace Domain\Entities\Link\Exception;

use Exception;

class WrongShortenedPartFormat extends Exception
{
    protected $message = 'Shortened part must be valid part of the URL.';
}
