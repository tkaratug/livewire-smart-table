<?php

namespace Tkaratug\LivewireSmartTable\Tests;

use Livewire\LivewireServiceProvider;
use Orchestra\Testbench\TestCase as BaseCase;
use Tkaratug\LivewireSmartTable\LivewireSmartTableServiceProvider;

class TestCase extends BaseCase
{
    protected function getPackageProviders($application)
    {
        return [
            LivewireServiceProvider::class,
            LivewireSmartTableServiceProvider::class,
        ];
    }
}
