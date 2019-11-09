<?php

/** @var UnitTest $unitTest */
use GenSys\GenerateBundle\Model\UnitTest;

?>
<?= "<?php\n" ?>

namespace <?= $unitTest->getNamespace() ?>;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use <?= $unitTest->getFixtureClassName() ?>;
<?php foreach ($unitTest->getMockDependencies() as $mockDependency): ?>
use <?= $mockDependency->getFullyQualifiedClassName() ?>;
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
<?php foreach($testMethod->getMethodCalls() as $methodCall): ?>
        $this-><?= $methodCall->getSubject() ?>->method('<?= $methodCall->getMethodName() ?>')->willReturn(null);
<?php endforeach; ?>
<?php $fixture = $testMethod->getFixture() ?>
        $fixture = new <?= $fixture->getClassName() ?>(<?= $fixture->getFixtureArguments() ?>);
        $result = $fixture-><?= $testMethod->getOriginalName() ?>(<?= $fixture->getMethodParameters() ?>);

        //TODO: Write assertion.

        $this->tearDown();
    }

<?php endforeach; ?>
}
