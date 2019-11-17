<?php


namespace GenSys\GenerateBundle\Service;

class FileService
{
    private const REGEX_CLASSNAME = '/class\s([a-zA-Z0-9]*).*\n\{/';
    private const REGEX_NAMESPACE = '/namespace\s([a-zA-Z0-9\\\\]*);/';

    /**
     * @param $fileName
     * @return string
     */
    public function getClassNameFromFile(string $fileName): string
    {
        $contents = file_get_contents($fileName);

        /**
         * Get namespace from file contents through regex
         */
        $matches = [];
        preg_match(self::REGEX_NAMESPACE, $contents, $matches);
        $namespace = $matches[1];

        /**
         * Get classname from file contents trough regex
         */
        $matches = [];
        preg_match(self::REGEX_CLASSNAME, $contents, $matches);
        $className = $matches[1];

        return $namespace . '\\' . $className;
    }

    /**
     * @param $fileName
     * @param $startLine
     * @param $endLine
     * @return string
     */
    public function getContents(string $fileName, int $startLine, int $endLine): string
    {
        $source = file($fileName);

        $length = $endLine - $startLine;
        $body = array_slice($source, $startLine, $length);

        foreach ($body as $lineNr => $line) {
            if (strpos($line,'{') !== false) {
                $startLine += $lineNr + 1 ;
                break;
            }
        }

        foreach (array_reverse($body) as $lineNr => $line) {
            if (strpos($line, '}') !== false) {
                $endLine -= $lineNr + 1;
                break;
            }
        }

        $length = $endLine - $startLine;
        $trimmedBody = array_slice($source, $startLine, $length);
        return implode('', $trimmedBody);
    }
}
