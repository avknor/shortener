<?php


namespace Domain\Entities\Link\Service;


class StatisticsItemService
{
    public static function getVisitorHostIp()
    {
        $ipAddress = 'UNKNOWN';
        $keys = ['HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR'];
        foreach ($keys as $k) {
            if (isset($_SERVER[$k]) && !empty($_SERVER[$k]) && filter_var($_SERVER[$k], FILTER_VALIDATE_IP)) {
                $ipAddress = $_SERVER[$k];
                break;
            }
        }

        return $ipAddress;
    }
}
