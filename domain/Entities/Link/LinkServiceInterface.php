<?php

namespace Domain\Entities\Link;

use Domain\Entities\Link\Dto\LinkCreateDto;
use Domain\Entities\Link\Dto\LinkClickedDto;

interface LinkServiceInterface
{
    public function create(LinkCreateDto $dto): Link;

    public function click(string $shortenedPart): LinkClickedDto;
}
