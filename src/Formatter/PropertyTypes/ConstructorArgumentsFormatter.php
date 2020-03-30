<?php /** @noinspection PhpParamsInspection */

namespace GenSys\GenerateBundle\Formatter\PropertyTypes;

use GenSys\GenerateBundle\Formatter\PropertyType\ConstructorArgumentFormatter;
use GenSys\GenerateBundle\Formatter\PropertyTypesFormatter;
use GenSys\GenerateBundle\Model\PropertyType;

class ConstructorArgumentsFormatter implements PropertyTypesFormatter
{
    /** @var string  */
    private const INDENT = '    ';

    /** @var ConstructorArgumentFormatter */
    private $constructorArgumentFormatter;

    public function __construct(
        ConstructorArgumentFormatter $constructorArgumentFormatter
    ) {
        $this->constructorArgumentFormatter = $constructorArgumentFormatter;
    }

    /**
     * @param PropertyType[] $propertyTypes
     * @return string
     */
    public function format(iterable $propertyTypes): string
    {
        $formattedPropertyTypes = [];
        foreach ($propertyTypes as $propertyType) {
            $formattedPropertyTypes[] = $this->constructorArgumentFormatter->format($propertyType);
        }

        $glue = ',' . PHP_EOL . str_repeat(self::INDENT, 3);
        return implode($glue, $formattedPropertyTypes);
    }
}
