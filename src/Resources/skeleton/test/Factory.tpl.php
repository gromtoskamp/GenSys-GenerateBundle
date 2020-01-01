<?php

use GenSys\GenerateBundle\Formatter\ConstructorArgumentsFormatter;
use GenSys\GenerateBundle\Formatter\InitArgumentsFormatter;
use GenSys\GenerateBundle\Model\Factory;

/** @var Factory $factory */
/** @var ConstructorArgumentsFormatter $constructorArgumentsFormatter */
/** @var InitArgumentsFormatter $initArgumentsFormatter */

?>
<?= "<?php\n" ?>

namespace <?= $factory->getNamespace() ?>;

use <?= $factory->getEntityClassName() ?>;

class <?= $factory->getClassName() . PHP_EOL ?>
{
    /**
     * @returns <?= $factory->getEntityShortName() .PHP_EOL ?>
     */
    public function create(): <?= $factory->getEntityShortName() . PHP_EOL ?>
    {
        <?= $initArgumentsFormatter->format($factory->getPropertyTypes()) . PHP_EOL ?>
        return new <?= $factory->getEntityShortName() ?>(
            <?= $constructorArgumentsFormatter->format($factory->getPropertyTypes()) . PHP_EOL ?>
        );
    }
}
