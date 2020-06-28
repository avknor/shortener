<?php

namespace Domain\Entities\Link\Dto;

class LinkCreateDto
{
    public $originalUrl;

    public $customShortenedPart;

    public $activeTill;

    public $isCommercial;
}
