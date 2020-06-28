<?php


namespace App\Exception;


use Exception;

class NonUniqueShortenedPart extends Exception
{
    protected $message = 'Shortened part of the URL must be unique.';
}
