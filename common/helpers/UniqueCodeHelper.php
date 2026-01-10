<?php

namespace common\helpers;

class UniqueCodeHelper
{
    /**
     * Generate a prefixed unique code
     *
     * Example: CUST-48392
     *
     * @param string $prefix
     * @param int $length
     * @return string
     */
    public static function generate(string $prefix, int $length = 6): string
    {
        $min = 10 ** ($length - 1);
        $max = (10 ** $length) - 1;

        return $prefix .'-'. random_int($min, $max);
    }
}