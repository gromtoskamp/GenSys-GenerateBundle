<?php

namespace GenSys\GenerateBundle\Maker;

use Symfony\Bundle\MakerBundle\DependencyBuilder;
use Symfony\Bundle\MakerBundle\InputConfiguration;
use Symfony\Bundle\MakerBundle\Maker\AbstractMaker as SymfonyAbstractMaker;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;

abstract class AbstractMaker extends SymfonyAbstractMaker
{
    /** @var string  */
    public const COMMAND_PREFIX = 'gensys:make:';

    /**
     * @param Command $command
     * @param InputConfiguration $inputConfig
     */
    public function configureCommand(Command $command, InputConfiguration $inputConfig): void
    {
        $command->addOption('force', '-f', InputOption::VALUE_NONE, 'Overwrite existing file');
    }

    /**
     * @param $key
     * @return string
     */
    protected function getTemplateFile($key): string
    {
        $key = ucfirst($key);
        return __DIR__ . '/../Resources/skeleton/test/' . $key . '.tpl.php';
    }

    /**
     * Configure any library dependencies that your maker requires.
     *
     * @param DependencyBuilder $dependencies
     */
    public function configureDependencies(DependencyBuilder $dependencies): void
    {
    }




}