<?php

namespace App\Utilities;

class Base62Service
{
    private const CHARACTERS = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";

    /**
     * Encode a numeric ID into a Base62 string
     */
    public function encode(int $number): string
    {
        $res = "";
        while ($number > 0) {
            // Get the remainder (0-61)
            $remainder = $number % 62;
            // Grab the character at that position
            $res = self::CHARACTERS[$remainder] . $res;
            // Divide the number for the next loop
            $number = intdiv($number, 62);
        }

        return $res;
    }

    /**
     * Decode a Base62 string back into a numeric ID
     */
    public function decode(string $base62): int
    {
        $number = 0;
        $len = strlen($base62);

        for ($i = 0; $i < $len; $i++) {
            // Find the index of the character
            $pos = strpos(self::CHARACTERS, $base62[$i]);
            // Reconstruct the number using powers of 62
            $number = $number * 62 + $pos;
        }

        return $number;
    }
}
