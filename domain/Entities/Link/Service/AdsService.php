<?php


namespace Domain\Entities\Link\Service;


class AdsService
{
    /**
     * @return string Random commercial picture file name
     */
    public static function nextAdvertise(): string
    {
        $ads = [];
        foreach (glob($_SERVER['DOCUMENT_ROOT'].'/ads/*.png') as $filename) {
            $ads[] = basename($filename);
        }

        return $ads[array_rand($ads)];
    }
}
