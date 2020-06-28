<?php

namespace App\Controller;

use App\Service\LinkService;
use Doctrine\ORM\ORMException;
use App\Exception\LinkNotFound;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Domain\Entities\Link\Exception\StringIsNotValidUrl;
use Domain\Entities\Link\Exception\WrongShortenedPartFormat;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RedirectController extends AbstractController
{
    /**
     * @Route("/{short}", name="click_link")
     *
     * @param string      $short
     * @param LinkService $service
     * @param Request     $request
     *
     * @throws NonUniqueResultException
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws StringIsNotValidUrl
     * @throws WrongShortenedPartFormat
     *
     * @return Response
     * @todo: is it possible refactor go() to separate simple and commercial pages rendering?
     */
    public function go(string $short, LinkService $service, Request $request): Response
    {
        try {
            $dto = $service->click($short);
        } catch (LinkNotFound $exception) {
            throw $this->createNotFoundException($exception->getMessage());
        }

        $response = $this->redirect($dto->originalUrl);

        if (true === $dto->isCommercial) {
            $response = $this->render('commercial.html.twig', [
                'picture' => $dto->picture,
                'delay' => $this->getParameter('app.ads_delay'),
            ]);

            $response->headers->set('Refresh', $this->getParameter('app.ads_delay').';url='.$dto->originalUrl);
        }

        //make this visit unique if necessary
        if (false === $request->cookies->has($short)) {
            $response->headers->setCookie(Cookie::create($short, $short)->withExpires(new \DateTime('+1 minute')));
        }

        return $response;
    }
}
