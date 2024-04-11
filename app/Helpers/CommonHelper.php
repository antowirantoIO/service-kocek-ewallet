<?php

namespace App\Helpers;

class CommonHelper {
    public static function generateRandomString(int $length = 6): string
    {
        $characters = '0123456789';
        $charactersLength = strlen($characters);
        $randomString = '';

        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        return $randomString;
    }

    public static function normalizeIdNumberPhone(
        string $countryCode,
        string $numberPhone
    ): string
    {
        $countryCode = str_replace('+', '', $countryCode);
        return $countryCode . $numberPhone;
    }
}
