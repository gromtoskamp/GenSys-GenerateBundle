<?php

namespace GenSys\GenerateBundle\Maker;

use GenSys\GenerateBundle\Factory\FactoryFactory;
use GenSys\GenerateBundle\Formatter\PropertyTypes\ConstructorArgumentsFormatter;
use GenSys\GenerateBundle\Formatter\PropertyTypes\InitArgumentsFormatter;
use GenSys\GenerateBundle\Service\FileService;
use ReflectionClass;
use Symfony\Bundle\MakerBundle\ConsoleStyle;
use Symfony\Bundle\MakerBundle\Generator;
use Symfony\Bundle\MakerBundle\InputConfiguration;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;

class MakeFactory extends AbstractMaker
{
    /** @var string  */
    private const PREFIX = 'GenSys\\GenerateBundle\\';

    /** @var FactoryFactory */
    private $factoryFactory;
    /** @var FileService */
    private $fileService;
    /** @var ConstructorArgumentsFormatter */
    private $constructorArgumentsFormatter;
    /** @var InitArgumentsFormatter */
    private $initArgumentsFormatter;

    /**
     * MakeFactory constructor.
     * @param FactoryFactory $factoryFactory
     * @param FileService $fileService
     * @param ConstructorArgumentsFormatter $constructorArgumentsFormatter
     * @param InitArgumentsFormatter $initArgumentsFormatter
     */
    public function __construct(
        FactoryFactory $factoryFactory,
        FileService $fileService,
        ConstructorArgumentsFormatter $constructorArgumentsFormatter,
        InitArgumentsFormatter $initArgumentsFormatter
    ) {
        $this->factoryFactory = $factoryFactory;
        $this->fileService = $fileService;
        $this->constructorArgumentsFormatter = $constructorArgumentsFormatter;
        $this->initArgumentsFormatter = $initArgumentsFormatter;
    }

    /**
     * Return the command name for your maker (e.g. make:report).
     *
     * @return string
     */
    public static function getCommandName(): string
    {
        return self::COMMAND_PREFIX . 'factory';
    }

    /**
     * @param Command $command
     * @param InputConfiguration $inputConfig
     */
    public function configureCommand(Command $command, InputConfiguration $inputConfig): void
    {
        parent::configureCommand($command, $inputConfig);
        $command
            ->setDescription('Creates a new factory class based on an Datastructure class.')
            ->addArgument('filePath', InputArgument::REQUIRED, 'Filename of the class to generate a Unit Test for | this has to be the fully qualified filename, from the root of your own directory; In PHPStorm, this is the context menu option -copy path-');
    }

    /**
     * Called after normal code generation: allows you to do anything.
     *
     * @param InputInterface $input
     * @param ConsoleStyle $io
     * @param Generator $generator
     * @throws \ReflectionException
     * @throws \Exception
     */
    public function generate(InputInterface $input, ConsoleStyle $io, Generator $generator): void
    {
        $className = $this->getClassName($input);
        $className = str_replace(self::PREFIX, '', $className);

        $testClassNameDetails = $generator->createClassNameDetails(
            $className,
            'Factory',
            'Factory'
        );

        $reflectionClass = new ReflectionClass($this->getClassName($input));
        $factory = $this->factoryFactory->create($reflectionClass);

        if ($input->getOption('force')  && class_exists($testClassName = $factory->getFullyQualifiedName())) {
            $testReflectionClass = new ReflectionClass($testClassName);
            unlink($testReflectionClass->getFileName());
        }

        $filePath = $generator->generateClass(
            $testClassNameDetails->getFullName(),
            $this->getTemplateFile('Factory'),
            [
                'factory' => $factory,
                'initArgumentsFormatter' => $this->initArgumentsFormatter,
                'constructorArgumentsFormatter' => $this->constructorArgumentsFormatter,
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
    public function getClassName(InputInterface $input): string
    {
        $className = $this->fileService->getClassNameFromFile(
            $input->getArgument('filePath')
        );
        return $className;
    }
}
