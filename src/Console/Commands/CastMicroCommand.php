<?php

namespace SultanovPackage\MicroCommands\Console\Commands;

use SultanovPackage\MicroCommands\Console\GeneratorCommand;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputOption;

#[AsCommand(name: 'micro:cast')]
class CastMicroCommand extends GeneratorCommand
{
    /**
     * The console command name.
     */
    protected $name = 'micro:cast';
    /**
     * The console command description.
     */
    protected $description = 'Create a new custom Eloquent cast class';

    /**
     * The type of class being generated.
     */
    protected string $type = 'Cast';

    /**
     * Get the stub file for the generator.
     */
    protected function getStub(): string
    {
        return $this->option('inbound')
            ? $this->resolveStubPath('/stubs/cast.inbound.stub')
            : $this->resolveStubPath('/stubs/cast.stub');
    }

    /**
     * Resolve the fully-qualified path to the stub.
     */
    protected function resolveStubPath(string $stub): string
    {
        return file_exists($customPath = $this->laravel->basePath(trim($stub, '/')))
            ? $customPath
            : __DIR__ . $stub;
    }

    /**
     * Get the default namespace for the class.
     */
    protected function getDefaultNamespace(string $rootNamespace): string
    {
        return $rootNamespace . '\Casts';
    }

    /**
     * Get the console command arguments.
     */
    protected function getOptions(): array
    {
        return [
            ['inbound', null, InputOption::VALUE_OPTIONAL, 'Generate an inbound cast class'],
        ];
    }
}
