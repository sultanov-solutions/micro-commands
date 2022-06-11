<?php

namespace SultanovPackage\MicroCommands\Console\Commands;

use SultanovPackage\MicroCommands\Console\GeneratorCommand;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputOption;

#[AsCommand(name: 'micro:exception')]
class ExceptionMicroCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'micro:exception';

    /**
     * The console command description.
     */
    protected $description = 'Create a new custom exception class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected string $type = 'Exception';

    /**
     * Get the stub file for the generator.
     */
    protected function getStub(): string
    {
        if ($this->option('render')) {
            return $this->option('report')
                ? __DIR__ . '/stubs/exception-render-report.stub'
                : __DIR__ . '/stubs/exception-render.stub';
        }

        return $this->option('report')
            ? __DIR__ . '/stubs/exception-report.stub'
            : __DIR__ . '/stubs/exception.stub';
    }

    /**
     * Determine if the class already exists.
     *
     * @param string $rawName
     * @return bool
     */
    protected function alreadyExists(string $rawName): bool
    {
        return class_exists($this->rootNamespace() . 'Exceptions\\' . $rawName);
    }

    /**
     * Get the default namespace for the class.
     */
    protected function getDefaultNamespace(string $rootNamespace): string
    {
        return $rootNamespace . '\Exceptions';
    }

    /**
     * Get the console command options.
     */
    protected function getOptions(): array
    {
        return [
            ['render', null, InputOption::VALUE_NONE, 'Create the exception with an empty render method'],

            ['report', null, InputOption::VALUE_NONE, 'Create the exception with an empty report method'],
        ];
    }
}
