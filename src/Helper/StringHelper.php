<?php

namespace App\Helper;

class StringHelper
{
    public static function replaceWithinBracers(string $source, string $mark, string $replacement): string
    {
        $pattern = sprintf("/{%s}/", $mark);

        return preg_replace($pattern, $replacement, $source);
    }
}
