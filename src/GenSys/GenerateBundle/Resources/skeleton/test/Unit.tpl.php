<?php

/** @var UnitTest $unitTest */
use GenSys\GenerateBundle\Model\UnitTest;

?>
<?= "<?php\n" ?>

namespace <?= $unitTest->getNamespace() ?>;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
<?php foreach ($unitTest->getMockDependencies() as $mockDependency): ?>
<?= 'use ' . $mockDependency->getFullyQualifiedClassName() . ";\n" ?>
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
        <?= $mockDependency->getBody() . "\n" ?>
<?php endforeach; ?>
    }

<?php foreach($unitTest->getTestMethods() as $testMethod): ?>
    public function <?= $testMethod->getName() ?>()
    {
<?php foreach($testMethod->getMockDependencies() as $mockDependency): ?>
        <?= $mockDependency->getVariableName() ?> = clone <?= $mockDependency->getPropertyCall() ?>;
<?php endforeach; ?>
<?php foreach($testMethod->getPropertyMethodCalls() as $property => $methodCalls): ?>
<?php foreach($methodCalls as $methodCall): ?>
        $<?= $property ?>->method('<?= $methodCall ?>')
            ->willReturn(null);
<?php endforeach; ?>
<?php endforeach; ?>
    }

<?php endforeach; ?>
}
