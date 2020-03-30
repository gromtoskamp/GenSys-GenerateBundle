<?php

namespace GenSys\GenerateBundle\Formatter\PropertyTypes;

use GenSys\GenerateBundle\Formatter\PropertyType\InitArgumentFormatter;
use GenSys\GenerateBundle\Formatter\PropertyTypesFormatter;
use GenSys\GenerateBundle\Model\PropertyType;

class InitArgumentsFormatter implements PropertyTypesFormatter
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
     * @param PropertyType[] $propertyTypes
     * @return string
     */
    public function format(iterable $propertyTypes): string
    {
        $formattedPropertyTypes = [];
        foreach ($propertyTypes as $propertyType) {
            $formattedPropertyTypes[] = $this->initArgumentFormatter->format($propertyType);
        }

        $glue = PHP_EOL . str_repeat('    ', 2);

        return implode($glue, $formattedPropertyTypes);
    }
}
