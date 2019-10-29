<?php

/** @var UnitTest $unitTest */
use GenSys\GenerateBundle\Model\UnitTest;

?>
<?= "<?php\n" ?>

namespace <?= $unitTest->getNamespace() ?>;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
<?php foreach ($unitTest->getMockDependencies() as $mockDependency): ?>
<?= "use " . $mockDependency->getFullyQualifiedClassName() . ";\n"; ?>
<?php endforeach; ?>

class <?= $unitTest->getClassName() ?> extends TestCase
{
<?php foreach($unitTest->getMockDependencies() as $mockDependency): ?>
    /** @var <?= $mockDependency->getClassName() ?>|MockObject */
    public <?= $mockDependency->getVariableName() ?>;

<?php endforeach; ?>

    public function setUp()
    {
<?php foreach($unitTest->getMockDependencies() as $mockDependency): ?>
        <?= $mockDependency->getBody() . "\n"; ?>
<?php endforeach; ?>
    }

<?php foreach($unitTest->getTestMethods() as $testMethod): ?>
    public function <?= $testMethod->getName(); ?>()
    {
<?php foreach($testMethod->getBody() as $body): ?>
        <?= $body . "\n"; ?>
<?php endforeach; ?>
    }

<?php endforeach; ?>
}
