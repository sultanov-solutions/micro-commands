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
        return is_file($customPath = MICRO_SRC_DIR . '/Console/Commands' . $stub)
            ? $customPath
            : __DIR__ . $stub;
    }

    /**
     * Get the destination class path.
     */
    protected function getPath(string $name): string
    {
        $name = str_replace('\\', '/', Str::replaceFirst($this->rootNamespace(), '', $name));

        return MICRO_SRC_DIR . '/' . $name . '.php';
    }

    /**
     * Get the default namespace for the class.
     *
     */
    protected function getDefaultNamespace(string $rootNamespace): string
    {
        return $rootNamespace . '\Database\Seeders';
    }
}
