<?php

namespace SultanovPackage\MicroCommands\Console;

use Illuminate\Console\Command;

class BaseCommand extends Command
{
    /**
     * Get all the migration paths.
     *
     */
    protected function getMigrationPaths(): array
    {
        // Here, we will check to see if a path option has been defined. If it has we will
        // use the path relative to the root of the installation folder so our database
        // migrations may be run for any customized path from within the application.
        if ($this->input->hasOption('path') && $this->option('path')) {
            return collect($this->option('path'))->map(function ($path) {
                return !$this->usingRealPath()
                    ? $this->laravel->basePath() . '/' . $path
                    : $path;
            })->all();
        }

        return array_merge(
            $this->migrator->paths(), [$this->getMigrationPath()]
        );
    }

    /**
     * Determine if the given path(s) are pre-resolved "real" paths.
     *
     */
    protected function usingRealPath(): bool
    {
        return $this->input->hasOption('realpath') && $this->option('realpath');
    }

    /**
     * Get the path to the migration directory.
     *
     */
    protected function getMigrationPath(): string
    {
        return MICRO_SRC_DIR.'/Database/Migrations';
    }
}
