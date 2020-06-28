<?php

namespace Domain\Entities\Link;

use Domain\Entities\Link\Exception\WrongVisitorIpFormat;

class VisitorIp
{
    private $ip;

    /**
     * @param string $ip
     *
     * @throws WrongVisitorIpFormat
     */
    public function __construct(string $ip)
    {
        if (!filter_var($ip, FILTER_VALIDATE_IP)) {
            throw new WrongVisitorIpFormat();
        }

        $this->ip = $ip;
    }

    public function __toString(): string
    {
        return  $this->ip;
    }
}
