<?php

namespace SultanovPackage\MicroCommands\Console\Commands;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Str;
use SultanovPackage\MicroCommands\Console\GeneratorCommand;

class SeederMicroCommand extends GeneratorCommand
{
    /**
     * The console command name.
     */
    protected $name = 'micro:seeder';
    /**
     * The console command description.
     */
    protected $description = 'Create a new seeder class';

    /**
     * The type of class being generated.
     */
    protected string $type = 'Seeder';

    /**
     * Execute the console command.
     * @throws FileNotFoundException
     */
    public function handle(): ?bool
    {
        parent::handle();

        return true;
    }

    /**
     * Get the stub file for the generator.
     */
    protected function getStub(): string
    {
        return $this->resolveStubPath('/stubs/seeder.stub');
    }

    /**
     * Resolve the fully-qualified path to the stub.
     */
    protected function resolveStubPath(string $stub): string
    {
        dd(is_file($customPath = $this->laravel->basePath(trim($stub, '/')))
            ? $customPath
            : __DIR__ . $stub);
        return is_file($customPath = $this->laravel->basePath(trim($stub, '/')))
            ? $customPath
            : __DIR__ . $stub;
    }

    /**
     * Get the destination class path.
     */
    protected function getPath(string $name): string
    {
        $name = str_replace('\\', '/', Str::replaceFirst($this->rootNamespace(), '', $name));

        if (is_dir($this->laravel->databasePath() . '/seeds')) {
            return $this->laravel->databasePath() . '/seeds/' . $name . '.php';
        }

        return $this->laravel->databasePath() . '/seeders/' . $name . '.php';
    }

    /**
     * Get the root namespace for the class.
     */
    protected function rootNamespace(): string
    {
        return 'Database\Seeders\\';
    }
}
