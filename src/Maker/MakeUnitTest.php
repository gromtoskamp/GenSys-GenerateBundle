<?php

namespace GenSys\GenerateBundle\Maker;

use Exception;
use GenSys\GenerateBundle\Factory\UnitTestFactory;
use GenSys\GenerateBundle\Formatter\TestMethodFormatter;
use GenSys\GenerateBundle\Formatter\UnitTestFormatter;
use GenSys\GenerateBundle\Service\FileService;
use ReflectionClass;
use Symfony\Bundle\MakerBundle\ConsoleStyle;
use Symfony\Bundle\MakerBundle\Generator;
use Symfony\Bundle\MakerBundle\InputConfiguration;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;

class MakeUnitTest extends AbstractMaker
{
    /** @var string */
    private const PREFIX = '\\Tests\\Unit\\';

    /** @var UnitTestFactory */
    private $unitTestFactory;
    /** @var FileService */
    private $fileService;
    /** @var TestMethodFormatter */
    private $testMethodFormatter;
    /** @var UnitTestFormatter */
    private $unitTestFormatter;

    /**
     * MakeUnitTest constructor.
     * @param UnitTestFactory $unitTestFactory
     * @param FileService $fileService
     * @param UnitTestFormatter $unitTestFormatter
     * @param TestMethodFormatter $testMethodFormatter
     */
    public function __construct(
        UnitTestFactory $unitTestFactory,
        FileService $fileService,
        UnitTestFormatter $unitTestFormatter,
        TestMethodFormatter $testMethodFormatter
    ) {
        $this->unitTestFactory = $unitTestFactory;
        $this->fileService = $fileService;
        $this->unitTestFormatter = $unitTestFormatter;
        $this->testMethodFormatter = $testMethodFormatter;
    }

    /**
     * Return the command name for your maker (e.g. make:report).
     *
     * @return string
     */
    public static function getCommandName(): string
    {
        return self::COMMAND_PREFIX . 'unit-test';
    }

    /**
     * Configure the command: set description, input arguments, options, etc.
     *
     * By default, all arguments will be asked interactively. If you want
     * to avoid that, use the $inputConfig->setArgumentAsNonInteractive() method.
     *
     * @param Command $command
     * @param InputConfiguration $inputConfig
     */
    public function configureCommand(Command $command, InputConfiguration $inputConfig): void
    {
        parent::configureCommand($command, $inputConfig);
        $command
            ->setDescription('Creates a new unit test class')
            ->addArgument('filePath', InputArgument::REQUIRED, 'Filename of the class to generate a Unit Test for | this has to be the fully qualified filename, from the root of your own directory; In PHPStorm, this is the context menu option -copy path-')
        ;
    }

    /**
     * Called after normal code generation: allows you to do anything.
     *
     * @param InputInterface $input
     * @param ConsoleStyle $io
     * @param Generator $generator
     * @throws Exception
     */
    public function generate(InputInterface $input, ConsoleStyle $io, Generator $generator): void
    {
        $className = $this->getClassName($input);

        $testClassNameDetails = $generator->createClassNameDetails(
            self::PREFIX . $className . 'Test',
            self::PREFIX,
            'Test'
        );

        $reflectionClass = new ReflectionClass($className);
        $unitTest = $this->unitTestFactory->create($reflectionClass);
        if ($input->getOption('force')  && class_exists($testClassName = $unitTest->getFullyQualifiedName())) {
            $testReflectionClass = new ReflectionClass($testClassName);
            unlink($testReflectionClass->getFileName());
        }

        $filePath = $generator->generateClass(
            $testClassNameDetails->getFullName(),
            $this->getTemplateFile('Unit'),
            [
                'unitTest' => $unitTest,
                'unitTestFormatter' => $this->unitTestFormatter,
                'testMethodFormatter' => $this->testMethodFormatter,
            ]
        );

        $generator->writeChanges();

        $this->writeSuccessMessage($io);

        $io->text([
            'Next: Open your new test class and start customizing it: ',
            ' at ' . $generator->getRootDirectory() . '/' . $filePath,
        ]);
    }

    /**
     * @param InputInterface $input
     * @return string
     */
    private function getClassName(InputInterface $input): string
    {
        $className = $this->fileService->getClassNameFromFile(
            $input->getArgument('filePath')
        );
        return $className;
    }
}
