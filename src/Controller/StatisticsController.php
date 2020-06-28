<?php

namespace App\Controller;

use Ramsey\Uuid\Uuid;
use App\Exception\LinkNotFound;
use App\Service\StatisticsService;
use App\Validation\UuidAnnotation;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\DBAL\Types\ConversionException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Domain\Entities\Link\Exception\StringIsNotValidUrl;
use Domain\Entities\Link\Exception\WrongShortenedPartFormat;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StatisticsController extends AbstractController
{
    private $service;

    public function __construct(StatisticsService $service)
    {
        $this->service = $service;
    }

    /**
     * @Route("/stat", name="summary", priority=1)
     *
     * @return JsonResponse
     */
    public function summary(): Response
    {
        $summary = $this->service->summary();

        return $this->render('summary.html.twig', ['summary'=>$summary]);
    }

    /**
     * @Route("/stat/{uuid}", name="link_statistics", requirements={"uuid"=UuidAnnotation::PATTERN})
     *
     * @param string $uuid
     *
     * @throws LinkNotFound
     * @throws NonUniqueResultException
     * @throws StringIsNotValidUrl
     * @throws WrongShortenedPartFormat
     * @throws ConversionException
     *
     * @return JsonResponse
     */
    public function linkStatistics(string $uuid): Response
    {
        $dto = $this->service->statistics(Uuid::fromString($uuid));

        return $this->render('statistics.html.twig', (array) $dto);
    }
}
