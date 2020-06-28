<?php

namespace App\Service;

use Domain\Entities\Link\Id;
use Domain\Entities\Link\Url;
use Doctrine\ORM\ORMException;
use App\Entity\Link as ORMLink;
use App\Exception\LinkNotFound;
use Domain\Entities\Link\LinkMeta;
use Domain\Entities\Link\OriginalUrl;
use App\Repository\LinkMySQLRepository;
use Domain\Entities\Link\ShortenedPart;
use App\Exception\NonUniqueShortenedPart;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\NonUniqueResultException;
use Domain\Entities\Link\Dto\LinkClickDto;
use Domain\Entities\Link\Dto\LinkCreateDto;
use Domain\Entities\Link\Dto\LinkClickedDto;
use Domain\Entities\Link\Link as DomainLink;
use Domain\Entities\Link\LinkServiceInterface;
use Domain\Entities\Link\Exception\StringIsNotValidUrl;
use Domain\Entities\Link\LinkSimple as DomainSimpleLink;
use Domain\Entities\Link\Exception\WrongShortenedPartFormat;
use Domain\Entities\Link\LinkCommercial as DomainCommercialLink;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class LinkService implements LinkServiceInterface
{
    private $linkRepository;

    private $params;

    private $requestStack;

    public function __construct(LinkMySQLRepository $linkRepository, ParameterBagInterface $params, RequestStack $requestStack)
    {
        $this->linkRepository = $linkRepository;
        $this->params = $params;
        $this->requestStack = $requestStack;
    }

    /**
     * @param LinkCreateDto $dto
     *
     * @throws NonUniqueShortenedPart
     * @throws WrongShortenedPartFormat
     * @throws StringIsNotValidUrl
     * @throws ORMException
     *
     * @return DomainSimpleLink
     *
     * @todo implement builder for DomainLink
     * @todo refactor create method
     */
    public function create(LinkCreateDto $dto): DomainLink
    {
        $linkType = self::determineLinkClass($dto->isCommercial);

        $newLink = new $linkType(
            Id::next(),
            new Url(
                new OriginalUrl($dto->originalUrl),
                new ShortenedPart($dto->customShortenedPart)
            ),
            new LinkMeta(
                $dto->activeTill,
                $dto->isCommercial
            )
        );
        $this->linkRepository->add($newLink);

        return $newLink;
    }

    /**
     * @param string $shortenedPart
     *
     * @throws LinkNotFound
     * @throws NonUniqueResultException
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws StringIsNotValidUrl
     * @throws WrongShortenedPartFormat
     *
     * @return LinkClickedDto
     */
    public function click(string $shortenedPart): LinkClickedDto
    {
        $linkEntity = $this->linkRepository->findByShortenedPart($shortenedPart);
        $link = self::mapToDomainLink($linkEntity);

        $request = $this->requestStack->getCurrentRequest();

        $statisticsItem = $link->click();
        if (false === $request->cookies->has($shortenedPart)) {
            $statisticsItem->makeUnique();
        }

        $linkClickDto = new LinkClickDto(
            $shortenedPart,
            $statisticsItem->clickedAt(),
            $statisticsItem->id(),
            $statisticsItem->visitorIp(),
            $statisticsItem->isUnique(),
            $statisticsItem->pictureName()
            );

        $this->linkRepository->click($linkClickDto);

        return new LinkClickedDto($link->url()->originalUrl(), $link->isCommercial(), $statisticsItem->pictureName());
    }

    /**
     * @param string $short
     *
     * @throws LinkNotFound
     * @throws NonUniqueResultException
     *
     * @todo: think to implement isCommercial method in repository. Optimization possibility.
     *
     * @return bool
     */
    public function isCommercial(string $short): bool
    {
        $linkEntity = $this->linkRepository->findByShortenedPart($short);

        return $linkEntity->getIsCommercial();
    }

    /**
     * Maps App\Entity\Link ORM object to DomainLink object.
     *
     * @param ORMLink $ormEntity
     *
     * @throws StringIsNotValidUrl
     * @throws WrongShortenedPartFormat
     *
     * @return DomainLink
     */
    public static function mapToDomainLink(ORMLink $ormEntity): DomainLink
    {
        $linkType = self::determineLinkClass($ormEntity->getIsCommercial());

        return new $linkType(
            new Id($ormEntity->getId()),
            new Url(
                new OriginalUrl($ormEntity->getOriginalUrl()),
                new ShortenedPart($ormEntity->getShortenedPart())
            ),
            new LinkMeta(
                $ormEntity->getActiveTill(),
                $ormEntity->getIsCommercial()
            ),
            $ormEntity->getStatistics()->toArray()
        );
    }

    private static function determineLinkClass(bool $isCommercial): string
    {
        return $isCommercial ? DomainCommercialLink::class : DomainSimpleLink::class;
    }
}
