<?php

namespace Core\Utils;

class Minmax {

    public static function clamp(int|float|null $value, int|float $min, int|float $max): int {

        if($value === null) return $min;

        return min(max($value, $min), $max);
        
    }

}