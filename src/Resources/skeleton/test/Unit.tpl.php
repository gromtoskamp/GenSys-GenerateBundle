<?php

use GenSys\GenerateBundle\Formatter\InitMockDependencyFormatter;
use GenSys\GenerateBundle\Formatter\PropertyMockDependencyFormatter;
use GenSys\GenerateBundle\Model\MockDependency;
use GenSys\GenerateBundle\Model\UnitTest;
use GenSys\GenerateBundle\Formatter\UseMockDependenciesFormatter;

/** @var UnitTest $unitTest */
/** @var UseMockDependenciesFormatter $useMockDependencyFormatter */
/** @var PropertyMockDependencyFormatter $propertyMockDependencyFormatter */
/** @var InitMockDependencyFormatter $initMockDependencyFormatter */

/** @var MockDependency[] $mockDependencies */
$mockDependencies = $unitTest->getMockDependencies();

?>
<?= "<?php\n" ?>

namespace <?= $unitTest->getNamespace() ?>;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use <?= $unitTest->getFixtureClassName() ?>;
<?= $useMockDependencyFormatter->format($mockDependencies) ?>

class <?= $unitTest->getClassName() ?> extends TestCase
{
<?= $propertyMockDependencyFormatter->format($mockDependencies) ?>

    public function setUp(): void
    {
        <?= $initMockDependencyFormatter->format($mockDependencies) ?>
    }

<?php foreach($unitTest->getTestMethods() as $testMethod): ?>
    public function <?= $testMethod->getName() ?>(): void
    {
<?php foreach($testMethod->getMethodCalls() as $methodCall): ?>
        $this-><?= $methodCall->getSubject() ?>->method('<?= $methodCall->getMethodName() ?>')->willReturn(null);
<?php endforeach; ?>
<?php $fixture = $testMethod->getFixture() ?>
        $fixture = new <?= $fixture->getClassName() ?>(<?= $fixture->getFixtureArguments() ?>);
        <?php if (!$testMethod->isReturnsVoid()): ?>$result = <?php endif; ?>$fixture-><?= $testMethod->getOriginalName() ?>(<?= $fixture->getMethodParameters() ?>);

        //TODO: Write assertion.

        $this->tearDown();
    }

<?php endforeach; ?>
}
