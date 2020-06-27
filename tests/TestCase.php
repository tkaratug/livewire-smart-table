<?php

namespace Tkaratug\LivewireSmartTable\Tests;

use CreateDummyTable;
use Livewire\LivewireServiceProvider;
use Orchestra\Testbench\TestCase as BaseCase;
use Tkaratug\LivewireSmartTable\LivewireSmartTableServiceProvider;

class TestCase extends BaseCase
{
    public function setUp(): void
    {
        parent::setUp();

        include_once __DIR__ . '/database/migrations/create_dummy_table.php';

        (new CreateDummyTable())->up();
    }

    protected function getPackageProviders($application)
    {
        return [
            LivewireServiceProvider::class,
            LivewireSmartTableServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('app.key', 'base64:Hupx3yAySikrM2/edkZQNQHslgDWYfiBfCuSThJ5SK8=');
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => ''
        ]);
    }
}
