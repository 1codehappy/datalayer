<?php

namespace CodeHappy\DataLayer\Helpers;

class Sketchy
{
    /**
     * Compile stub
     *
     * @param string $text
     * @param array $valuables
     * @return string
     */
    public function compile(string $text, array $valuables): string
    {
        preg_match_all('/{{([^{]+)}}/', $text, $output);
        list($tags, $keys) = $output;

        $replacements = [];
        foreach ($keys as $key) {
            $normalizedKey = trim($key);
            if (empty($valuables[$normalizedKey]) === true) {
                continue;
            }
            $replacements[] = $valuables[$normalizedKey];
        }

        return str_replace($tags, $replacements, $text);
    }
}
