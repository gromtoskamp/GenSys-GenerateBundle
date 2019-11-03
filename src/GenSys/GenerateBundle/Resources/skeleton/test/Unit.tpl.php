<?php

/** @var UnitTest $unitTest */
use GenSys\GenerateBundle\Model\UnitTest;

?>
<?= "<?php\n" ?>

namespace <?= $unitTest->getNamespace() ?>;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
<?php foreach ($unitTest->getMockDependencies() as $mockDependency): ?>
use <?= $mockDependency->getFullyQualifiedClassName() . ";\n" ?>
<?php endforeach; ?>

class <?= $unitTest->getClassName() ?> extends TestCase
{
<?php foreach($unitTest->getMockDependencies() as $mockDependency): ?>
    /** @var <?= $mockDependency->getClassName() ?>|MockObject */
    public $<?= $mockDependency->getPropertyName() ?>;

<?php endforeach; ?>

    public function setUp(): void
    {
<?php foreach($unitTest->getMockDependencies() as $mockDependency): ?>
        $this-><?= $mockDependency->getPropertyName() ?> = $this->getMockBuilder(<?= $mockDependency->getClassName() ?>::class)->disableOriginalConstructor()->getMock();
<?php endforeach; ?>
    }

<?php foreach($unitTest->getTestMethods() as $testMethod): ?>
    public function <?= $testMethod->getName() ?>(): void
    {
<?php foreach($testMethod->getMockDependencies() as $mockDependency): ?>
        $<?= $mockDependency->getPropertyName() ?> = clone $this-><?= $mockDependency->getPropertyName() ?>;
<?php endforeach; ?>
<?php foreach($testMethod->getMethodCalls() as $methodCall): ?>
        $<?= $methodCall->getSubject() ?>->method('<?= $methodCall->getMethodName() ?>')->willReturn(null);
<?php endforeach; ?>
    }

<?php endforeach; ?>
    public function tearDown(): void
    {
<?php foreach($unitTest->getMockDependencies() as $mockDependency): ?>
        unset($this-><?= $mockDependency->getPropertyName() ?>);
<?php endforeach; ?>
    }
}
