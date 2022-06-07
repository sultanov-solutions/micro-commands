<?php

namespace SultanovPackage\MicroCommands\Controllers;

use SultanovPackage\MicroCommands\Models\Example;
use SultanovSolutions\LaravelBase\Controllers\BaseController;

class ExampleController extends BaseController
{
    protected ?string $model = Example::class;

    protected string $scope = 'example';
}
