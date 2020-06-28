<?php

declare(strict_types=1);

namespace Domain\Entities\Link;

class Url
{
    private $originalUrl;

    private $shortenedPart;

    private $statUrl;

    /**
     * @param OriginalUrl   $originalUrl
     * @param ShortenedPart $customShortenedPart
     *
     * @throws Exception\WrongShortenedPartFormat
     */
    public function __construct(OriginalUrl $originalUrl, ShortenedPart $customShortenedPart = null)
    {
        $this->originalUrl = $originalUrl;
        $this->shortenedPart = $customShortenedPart ?? ShortenedPart::next();
        $this->statUrl = new StatUrl($this->shortenedPart());
    }

    public function originalUrl(): OriginalUrl
    {
        return $this->originalUrl;
    }

    public function shortenedPart(): ShortenedPart
    {
        return $this->shortenedPart;
    }

    public function statisticsUrl(): StatUrl
    {
        return $this->statUrl;
    }
}
