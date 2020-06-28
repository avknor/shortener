<?php

namespace Domain\Entities\Link\Dto;

class LinkClickedDto
{
    public $originalUrl;

    public $isCommercial;

    public $picture;

    public function __construct(string $originalUrl, bool $isCommercial = false, string $picture = null)
    {
        $this->originalUrl = $originalUrl;
        $this->isCommercial = $isCommercial;
        $this->picture = $picture;
    }
}
