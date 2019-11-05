<?php

namespace GenSys\GenerateBundle\Model\Scanner;

class AbstractScanner
{
    /**
     * @param $regex
     * @return array
     */
    protected function match($subject, string $regex): array
    {
        $matches = [];
        preg_match_all($regex, $subject, $matches);
        return array_unique($matches[1]);
    }

    /**
     * @param string $regex
     * @return array
     */
    protected function combinedMatch($subject, string $regex): array
    {
        $matches = [];
        preg_match_all($regex, $subject, $matches);
        $combinedMatches = [];
        foreach ($matches[1] as $key => $match) {
            $combinedMatches[$match][] = $matches[2][$key];
        }

        return $combinedMatches;
    }
}
