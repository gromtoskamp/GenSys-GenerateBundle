<?php

namespace GenSys\GenerateBundle\Command;

use GenSys\GenerateBundle\Service\GenerateUnitTestService;
use GenSys\GenerateBundle\Service\Generator\NetteGenerator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateUnitTestCommand extends Command
{
    const REGEX_CLASSNAME = '/class\s([a-zA-Z0-9]*)\n\{/';
    const REGEX_NAMESPACE = '/namespace\s([a-zA-Z0-9\\\\]*);/';

    protected static $defaultName = 'gensys:generate:unit';

    protected function configure()
    {
        $this
            ->setDescription('Generate a Unit Test boilerplate for given classname.')
            ->addArgument('fileName', InputArgument::REQUIRED, 'Filename of the class to generate a Unit Test for | this has to be the fully qualified filename, from the root of your own directory; In PHPStorm, this is the context menu option -copy path-');
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $fileName = $input->getArgument('fileName');
        $className = $this->getClassNameFromFile($fileName);

        //TODO: Add DI.
        $generateUnitTestService = new GenerateUnitTestService(new NetteGenerator());
        $generateUnitTestService->generateUnitTest($className);

        return 0;
    }

    /**
     * @param $fileName
     * @return string
     *
     * TODO: make this a service.
     */
    private function getClassNameFromFile($fileName): string
    {
        $contents = file_get_contents($fileName);

        /**
         * Get namespace from file contents through regex
         */
        $matches = [];
        preg_match(self::REGEX_NAMESPACE, $contents, $matches);
        $namespace = $matches[1];

        /**
         * Get classname from file contents trough regex
         */
        $matches = [];
        preg_match(self::REGEX_CLASSNAME, $contents, $matches);
        $className = $matches[1];

        return $namespace . '\\' . $className;
    }
}
