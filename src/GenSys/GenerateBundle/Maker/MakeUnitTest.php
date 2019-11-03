<?php

namespace GenSys\GenerateBundle\Maker;

use Exception;
use GenSys\GenerateBundle\Factory\UnitTestFactory;
use GenSys\GenerateBundle\Service\FileService;
use ReflectionClass;
use Symfony\Bundle\MakerBundle\ConsoleStyle;
use Symfony\Bundle\MakerBundle\DependencyBuilder;
use Symfony\Bundle\MakerBundle\Generator;
use Symfony\Bundle\MakerBundle\InputConfiguration;
use Symfony\Bundle\MakerBundle\Maker\AbstractMaker;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;

class MakeUnitTest extends AbstractMaker
{
    /** @var UnitTestFactory */
    private $unitTestFactory;
    /** @var FileService */
    private $fileService;

    /**
     * MakeUnitTest constructor.
     * @param UnitTestFactory $unitTestFactory
     * @param FileService $fileService
     */
    public function __construct(
        UnitTestFactory $unitTestFactory,
        FileService $fileService
    ) {
        $this->unitTestFactory = $unitTestFactory;
        $this->fileService = $fileService;
    }

    /**
     * Return the command name for your maker (e.g. make:report).
     *
     * @return string
     */
    public static function getCommandName(): string
    {
        return 'gensys:make:unit-test';
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
        $command
            ->setDescription('Creates a new unit test class')
            ->addArgument('filePath', InputArgument::REQUIRED, 'Filename of the class to generate a Unit Test for | this has to be the fully qualified filename, from the root of your own directory; In PHPStorm, this is the context menu option -copy path-')
            ->addOption('force', '-f', InputOption::VALUE_OPTIONAL, 'Overwrite existing file')
        ;
    }

    /**
     * Configure any library dependencies that your maker requires.
     *
     * @param DependencyBuilder $dependencies
     */
    public function configureDependencies(DependencyBuilder $dependencies): void
    {
        // TODO: Implement configureDependencies() method.
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
        $className = $this->fileService->getClassNameFromFile(
            $input->getArgument('filePath')
        );

        $testClassNameDetails = $generator->createClassNameDetails(
            '\\Tests\\Unit\\' . $className . 'Test',
            'Tests\\Unit\\',
            'Test'
        );

        $unitTest = $this->unitTestFactory->createFromClassName($className);
        if ($input->hasOption('force')) {
            $reflectionClass = new ReflectionClass($unitTest->getFullyQualifiedName());
            unlink($reflectionClass->getFileName());
        }

        $filePath = $generator->generateClass(
            $testClassNameDetails->getFullName(),
            __DIR__ . '/../Resources/skeleton/test/Unit.tpl.php',
            [
                'unitTest' => $unitTest
            ]
        );

        $generator->writeChanges();

        $this->writeSuccessMessage($io);

        $io->text([
            'Next: Open your new test class and start customizing it: ',
            ' at ' . $generator->getRootDirectory() . '/' . $filePath,
        ]);
    }
}
