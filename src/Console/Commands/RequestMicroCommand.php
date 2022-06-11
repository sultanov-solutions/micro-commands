<?php

namespace SultanovPackage\MicroCommands\Console\Commands;

use SultanovPackage\MicroCommands\Console\GeneratorCommand;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'micro:request')]
class RequestMicroCommand extends GeneratorCommand
{
    /**
     * The console command name.
     */
    protected $name = 'micro:request';
    /**
     * The console command description.
     */
    protected $description = 'Create a new form request class';

    /**
     * The type of class being generated.
     */
    protected string $type = 'Request';

    /**
     * Get the stub file for the generator.
     */
    protected function getStub(): string
    {
        return $this->resolveStubPath('/stubs/request.stub');
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
        return $rootNamespace . '\Requests';
    }
}
