<?php

namespace Oblak;

/**
 * Checks if number is valid using check digit 11 function (mod11)
 *
 * @param  int      $number Number to check
 * @return null|number      Check digit if valid, null otherwise
 */
function mod11($number) {
    $digits = array_reverse(str_split((string) $number));

    $sum = 0;
    foreach ($digits as $index => $digit) {
        $sum += (int) $digit * (($index % 6) + 2);
    }

    $remainder = $sum % 11;

    switch ($remainder) {
        case 0:
            return $remainder;
            break;
        case 1:
            return null;
            break;
        default:
            return 11 - $remainder;
            break;
    }
}
