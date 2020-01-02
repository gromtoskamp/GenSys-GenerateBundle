<?php

use GenSys\GenerateBundle\Formatter\UnitTestFormatter;
use GenSys\GenerateBundle\Model\UnitTest;
use GenSys\GenerateBundle\Formatter\TestMethodFormatter;

/** @var UnitTest $unitTest */
/** @var UnitTestFormatter $unitTestFormatter */
/** @var TestMethodFormatter $testMethodFormatter */

?>
<?= "<?php\n" ?>

namespace <?= $unitTest->getNamespace() ?>;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use <?= $unitTest->getFixtureClassName() ?>;
<?= $unitTestFormatter->formatUseMockDependencies($unitTest) ?>

class <?= $unitTest->getClassName() ?> extends TestCase
{
<?= $unitTestFormatter->formatPropertyMockDependencies($unitTest) ?>

    public function setUp(): void
    {
        <?= $unitTestFormatter->formatInitMockDependencies($unitTest) ?>
    }

<?php foreach($unitTest->getTestMethods() as $testMethod): ?>
    public function <?= $testMethod->getName() ?>(): void
    {
        <?= $testMethodFormatter->formatTestMethodCalls($testMethod) ?>
        <?= $testMethodFormatter->formatFixture($testMethod) ?>
        <?= $testMethodFormatter->formatResult($testMethod) ?>

        //TODO: Write assertion.

        $this->tearDown();
    }

<?php endforeach; ?>
}
