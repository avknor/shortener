<?php

namespace App\Controller;

use App\Form\LinkType;
use App\Service\LinkService;
use Doctrine\ORM\ORMException;
use App\Exception\NonUniqueShortenedPart;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Domain\Entities\Link\Exception\StringIsNotValidUrl;
use Domain\Entities\Link\Exception\WrongShortenedPartFormat;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MainController extends AbstractController
{
    private $service;

    public function __construct(LinkService $service)
    {
        $this->service = $service;
    }

    /**
     * @Route("/", name="add_link")
     *
     * @param Request $request
     *
     * @throws ORMException
     * @throws NonUniqueShortenedPart
     * @throws StringIsNotValidUrl
     * @throws WrongShortenedPartFormat
     *
     * @return Response
     */
    public function add(Request $request): Response
    {
        $form = $this->createForm(LinkType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $dto = $form->getData();
            $link = $this->service->create($dto);

            $this->addFlash('success', 'Link just shortened.');

            return $this->redirectToRoute('link_statistics', ['uuid' => $link->url()->statisticsUrl()]);
        }

        return $this->render('add.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
