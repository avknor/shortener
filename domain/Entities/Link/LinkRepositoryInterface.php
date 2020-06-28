<?php

namespace Domain\Entities\Link;

use Domain\Entities\Link\Dto\LinkClickDto;

interface LinkRepositoryInterface
{
    public function add(Link $link);

    public function click(LinkClickDto $dto): void;
}
