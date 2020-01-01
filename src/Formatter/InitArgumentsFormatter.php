<?php

namespace GenSys\GenerateBundle\Formatter;

use GenSys\GenerateBundle\Model\PropertyType;

class InitArgumentsFormatter
{
    /** @var InitArgumentFormatter */
    private $initArgumentFormatter;

    /**
     * InitArgumentsFormatter constructor.
     * @param InitArgumentFormatter $initArgumentFormatter
     */
    public function __construct(
        InitArgumentFormatter $initArgumentFormatter
    ) {
        $this->initArgumentFormatter = $initArgumentFormatter;
    }

    /**
     * @param PropertyType[] $properties
     * @return string
     */
    public function format(iterable $properties): string
    {
        $formattedProperties = [];
        foreach ($properties as $property) {
            $formattedProperties[] = $this->initArgumentFormatter->format($property);
        }

        $glue = PHP_EOL . str_repeat('    ', 2);

        return implode($glue, $formattedProperties);
    }
}