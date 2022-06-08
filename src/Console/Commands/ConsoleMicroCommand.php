<?php

namespace SultanovPackage\MicroCommands\Console\Commands;

use Illuminate\Console\Concerns\CreatesMatchingTest;
use SultanovPackage\MicroCommands\Console\GeneratorCommand;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

#[AsCommand(name: 'micro:command')]
class ConsoleMicroCommand extends GeneratorCommand
{
    use CreatesMatchingTest;

    /**
     * The console command name.
     *
     */
    protected $name = 'micro:command';
    /**
     * The console command description.
     *
     */
    protected $description = 'Create a new Micro command';

    /**
     * The type of class being generated.
     *
     */
    protected string $type = 'Console command';

    /**
     * Replace the class name for the given stub.
     *
     */
    protected function replaceClass(string $stub, string $name): string
    {
        $stub = parent::replaceClass($stub, $name);

        return str_replace(['dummy:command', '{{ command }}'], $this->option('command'), $stub);
    }

    /**
     * Get the stub file for the generator.
     *
     */
    protected function getStub(): string
    {
        $relativePath = '/stubs/console.stub';

        return file_exists($customPath = $this->laravel->basePath(trim($relativePath, '/')))
            ? $customPath
            : __DIR__ . $relativePath;
    }

    /**
     * Get the default namespace for the class.
     *
     */
    protected function getDefaultNamespace(string $rootNamespace): string
    {
        return $rootNamespace . '\Console\Commands';
    }

    /**
     * Get the console command arguments.
     *
     */
    protected function getArguments(): array
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the command'],
        ];
    }

    /**
     * Get the console command options.
     *
     */
    protected function getOptions(): array
    {
        return [
            ['command', null, InputOption::VALUE_OPTIONAL, 'The terminal command that should be assigned', 'command:name'],
        ];
    }
}
