<?php

namespace GenSys\GenerateBundle\Formatter;

use GenSys\GenerateBundle\Formatter\MockDependencies\FixtureArgumentsFormatter;
use GenSys\GenerateBundle\Model\Fixture;

class FixtureFormatter
{
    /** @var FixtureArgumentsFormatter */
    private $fixtureArgumentsFormatter;

    /**
     * FixtureFormatter constructor.
     * @param FixtureArgumentsFormatter $fixtureArgumentsFormatter
     */
    public function __construct(
        FixtureArgumentsFormatter $fixtureArgumentsFormatter
    ) {
        $this->fixtureArgumentsFormatter = $fixtureArgumentsFormatter;
    }

    /**
     * @param Fixture $fixture
     * @return string
     */
    public function format(Fixture $fixture): string
    {
        $className = $fixture->getClassName();
        $fixtureArguments = $this->fixtureArgumentsFormatter->format($fixture->getMockDependencies());
        return sprintf('$fixture = new %s(%s);', $className, $fixtureArguments);
    }
}
