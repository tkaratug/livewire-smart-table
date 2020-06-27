<?php

namespace Tkaratug\LivewireSmartTable\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Livewire\LivewireManager;
use Livewire\Testing\TestableLivewire;
use Tkaratug\LivewireSmartTable\LivewireSmartTable;
use Tkaratug\LivewireSmartTable\Tests\Database\Models\Dummy;

class LivewireSmartTableTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private function createComponent($params): TestableLivewire
    {
        return app(LivewireManager::class)->test(LivewireSmartTable::class, $params);
    }

    /** @test */
    public function can_create_component_with_required_params()
    {
        // Arrange
        $name = $this->faker->name;

        // Act
        $component = $this->createComponent([
            'name' => $name,
        ]);

        // Assert
        $this->assertNotNull($component);

        $component->assertSet('columns', []);
        $component->assertSet('search', '');
        $component->assertSet('page', 1);
        $component->assertSet('perPage', 10);
        $component->assertSet('sortField', 'id');
        $component->assertSet('sortAsc', true);
        $component->assertSet('tableClass', 'table');
        $component->assertSet('sortIcon', '&#8597;');
        $component->assertSet('sortAscIcon', '&#8593;');
        $component->assertSet('sortDescIcon', '&#8595;');
    }

    /** @test */
    public function can_get_data_and_set_columns()
    {
        $data = $this->prepareData();
        $columns = $this->prepareColumns();

        Livewire::test(LivewireSmartTable::class, ['query' => $data])
            ->set('columns', $columns)
            ->assertSet('columns', $columns)
            ->assertSee($data->first()->firstname)
            ->set('page', 2)
            ->assertSee($data->last()->firstname);
    }

    /**
     * Prepare data to list in table.
     *
     * @return \Illuminate\Support\Collection
     */
    private function prepareData()
    {
        for ($i = 1; $i <= 20; $i++) {
            Dummy::create([
                'firstname' => $this->faker->firstName,
                'lastname' => $this->faker->lastName,
                'email' => $this->faker->email
            ]);
        }

        return Dummy::query()->get();
    }

    /**
     * Prepare columns.
     *
     * @return array
     */
    private function prepareColumns()
    {
        return [
            'id' => [
                'type' => 'string',
                'name' => 'id',
            ],
            'firstname' => [
                'type' => 'string',
                'name' => 'First Name',
            ],
        ];
    }
}
