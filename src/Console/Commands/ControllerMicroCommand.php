<?php

namespace SultanovPackage\MicroCommands\Console\Commands;

use Illuminate\Console\Concerns\CreatesMatchingTest;
use InvalidArgumentException;
use SultanovPackage\MicroCommands\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputOption;

class ControllerMicroCommand extends GeneratorCommand
{
    use CreatesMatchingTest;

    /**
     * The console command name.
     *
     */
    protected $name = 'micro:controller';
    /**
     * The console command description.
     *
     */
    protected $description = 'Create a new controller class';

    /**
     * The type of class being generated.
     *
     */
    protected string $type = 'Controller';

    /**
     * Get the stub file for the generator.
     *
     */
    protected function getStub(): string
    {
        $stub = null;

        if ($type = $this->option('type')) {
            $stub = "/stubs/controller.{$type}.stub";
        } elseif ($this->option('parent')) {
            $stub = '/stubs/controller.nested.stub';
        } elseif ($this->option('model')) {
            $stub = '/stubs/controller.model.stub';
        } elseif ($this->option('invokable')) {
            $stub = '/stubs/controller.invokable.stub';
        } elseif ($this->option('resource')) {
            $stub = '/stubs/controller.stub';
        }

        if ($this->option('api') && is_null($stub)) {
            $stub = '/stubs/controller.api.stub';
        } elseif ($this->option('api') && !is_null($stub) && !$this->option('invokable')) {
            $stub = str_replace('.stub', '.api.stub', $stub);
        }

        $stub ??= '/stubs/controller.plain.stub';

        return $this->resolveStubPath($stub);
    }

    /**
     * Resolve the fully-qualified path to the stub.
     *
     */
    protected function resolveStubPath(string $stub): string
    {
        return file_exists($customPath = $this->laravel->basePath(trim($stub, '/'))) ? $customPath : __DIR__ . $stub;
    }

    /**
     * Get the default namespace for the class.
     */
    protected function getDefaultNamespace(string $rootNamespace): string
    {
        return $rootNamespace . '\Controllers';
    }

    /**
     * Build the class with the given name.
     */
    protected function buildClass(string $name): string
    {
        $controllerNamespace = $this->getNamespace($name);

        $replace = [];

        if ($this->option('parent')) {
            $replace = $this->buildParentReplacements();
        }

        if ($this->option('model')) {
            $replace = $this->buildModelReplacements($replace);
        }

        $replace["use {$controllerNamespace}\Controller;\n"] = '';

        return str_replace(array_keys($replace), array_values($replace), parent::buildClass($name));
    }

    /**
     * Build the replacements for a parent controller.
     */
    protected function buildParentReplacements(): array
    {
        $parentModelClass = $this->parseModel($this->option('parent'));

        if (!class_exists($parentModelClass) && $this->confirm("A {$parentModelClass} model does not exist. Do you want to generate it?", true)) {
            $this->call('micro:model', ['name' => $parentModelClass]);
        }

        return ['ParentDummyFullModelClass' => $parentModelClass, '{{ namespacedParentModel }}' => $parentModelClass, '{{namespacedParentModel}}' => $parentModelClass, 'ParentDummyModelClass' => class_basename($parentModelClass), '{{ parentModel }}' => class_basename($parentModelClass), '{{parentModel}}' => class_basename($parentModelClass), 'ParentDummyModelVariable' => lcfirst(class_basename($parentModelClass)), '{{ parentModelVariable }}' => lcfirst(class_basename($parentModelClass)), '{{parentModelVariable}}' => lcfirst(class_basename($parentModelClass)),];
    }

    /**
     * Get the fully-qualified model class name.
     *
     * @throws InvalidArgumentException
     */
    protected function parseModel(string $model): string
    {
        if (preg_match('([^A-Za-z0-9_/\\\\])', $model)) {
            throw new InvalidArgumentException('Model name contains invalid characters.');
        }

        return $this->qualifyModel($model);
    }

    /**
     * Build the model replacement values.
     *
     */
    protected function buildModelReplacements(array $replace): array
    {
        $modelClass = $this->parseModel($this->option('model'));

        if (!class_exists($modelClass) && $this->confirm("A {$modelClass} model does not exist. Do you want to generate it?", true)) {
            $this->call('micro:model', ['name' => $modelClass]);
        }

        $replace = $this->buildFormRequestReplacements($replace, $modelClass);

        return array_merge($replace, ['DummyFullModelClass' => $modelClass, '{{ namespacedModel }}' => $modelClass, '{{namespacedModel}}' => $modelClass, 'DummyModelClass' => class_basename($modelClass), '{{ model }}' => class_basename($modelClass), '{{model}}' => class_basename($modelClass), 'DummyModelVariable' => lcfirst(class_basename($modelClass)), '{{ modelVariable }}' => lcfirst(class_basename($modelClass)), '{{modelVariable}}' => lcfirst(class_basename($modelClass)),]);
    }

    /**
     * Build the model replacement values.
     */
    protected function buildFormRequestReplacements(array $replace, string $modelClass): array
    {
        [$namespace, $storeRequestClass, $updateRequestClass] = ['Illuminate\\Http', 'Request', 'Request',];

        if ($this->option('requests')) {
            $namespace = MICRO_SRC_DIR . '\\Requests';

            [$storeRequestClass, $updateRequestClass] = $this->generateFormRequests($modelClass, $storeRequestClass, $updateRequestClass);
        }

        $namespacedRequests = $namespace . '\\' . $storeRequestClass . ';';

        if ($storeRequestClass !== $updateRequestClass) {
            $namespacedRequests .= PHP_EOL . 'use ' . $namespace . '\\' . $updateRequestClass . ';';
        }

        return array_merge($replace, ['{{ storeRequest }}' => $storeRequestClass, '{{storeRequest}}' => $storeRequestClass, '{{ updateRequest }}' => $updateRequestClass, '{{updateRequest}}' => $updateRequestClass, '{{ namespacedStoreRequest }}' => $namespace . '\\' . $storeRequestClass, '{{namespacedStoreRequest}}' => $namespace . '\\' . $storeRequestClass, '{{ namespacedUpdateRequest }}' => $namespace . '\\' . $updateRequestClass, '{{namespacedUpdateRequest}}' => $namespace . '\\' . $updateRequestClass, '{{ namespacedRequests }}' => $namespacedRequests, '{{namespacedRequests}}' => $namespacedRequests,]);
    }

    /**
     * Generate the form requests for the given model and classes.
     *
     * @param string $modelClass
     * @param string $storeRequestClass
     * @param string $updateRequestClass
     * @return array
     */
    protected function generateFormRequests(string $modelClass, string $storeRequestClass, string $updateRequestClass): array
    {
        $storeRequestClass = 'Store' . class_basename($modelClass) . 'Request';

        $this->call('micro:request', ['name' => $storeRequestClass,]);

        $updateRequestClass = 'Update' . class_basename($modelClass) . 'Request';

        $this->call('micro:request', ['name' => $updateRequestClass,]);

        return [$storeRequestClass, $updateRequestClass];
    }

    /**
     * Get the console command options.
     */
    protected function getOptions(): array
    {
        return [['api', null, InputOption::VALUE_NONE, 'Exclude the create and edit methods from the controller.'], ['type', null, InputOption::VALUE_REQUIRED, 'Manually specify the controller stub file to use.'], ['force', null, InputOption::VALUE_NONE, 'Create the class even if the controller already exists'], ['invokable', 'i', InputOption::VALUE_NONE, 'Generate a single method, invokable controller class.'], ['model', 'm', InputOption::VALUE_OPTIONAL, 'Generate a resource controller for the given model.'], ['parent', 'p', InputOption::VALUE_OPTIONAL, 'Generate a nested resource controller class.'], ['resource', 'r', InputOption::VALUE_NONE, 'Generate a resource controller class.'], ['requests', 'R', InputOption::VALUE_NONE, 'Generate FormRequest classes for store and update.'],];
    }
}
