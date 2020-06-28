<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Domain\Entities\Link\Service\StatisticsItemService;

class SandboxController
{
    /**
     * @Route("/sandbox", priority=10)
     */
    public function sandbox(): Response
    {
        return new Response(
            StatisticsItemService::getVisitorHostIp()
        );
    }
}
