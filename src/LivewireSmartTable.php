<?php

namespace Tkaratug\LivewireSmartTable;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Component;
use Livewire\WithPagination;

class LivewireSmartTable extends Component
{
    use WithPagination;

    public $query;

    public $search = '';

    public $page = 1;

    public $perPage = 10;

    public $sortField = 'id';

    public $sortAsc = true;

    public $columns = [];

    public $tableClass;

    public function mount(
        Collection $query,
        array $columns = [],
        string $tableClass = 'table')
    {
        $this->query = $query;
        $this->columns = $columns;
        $this->tableClass = $tableClass;
        $this->page = request()->query('page', $this->page);
    }

    /**
     * Sort by.
     *
     * @param $field
     * @return void
     */
    public function sortBy($field): void
    {
        if ($this->sortField === $field) {
            $this->sortAsc = ! $this->sortAsc;
        }

        $this->sortField = $field;
    }

    /**
     * Render
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function render()
    {
        $items = $this->query;

        // If user search something, filter items by input
        if (!empty($this->search)) {
            $items = $this->query->filter(function ($item) {
                $found = false;
                foreach ($this->columns as $column => $props) {
                    // if column is a property of a json data then sort by the key
                    if (isset($props['from'])) {
                        if (stripos(json_decode($item->{$props['from']})->{$column}, $this->search) !== false) {
                            $found = true;
                        }
                    } else {
                        if (stripos($item->{$props}, $this->search) !== false) {
                            $found = true;
                        }
                    }
                }
                return $found;
            });
        }

        // If the field that wanted to be sorted is from a json data
        if (strpos($this->sortField, '->') !== false) {
            $field = explode('->', $this->sortField);
            if ($this->sortAsc) {
                $data =$items->sortBy(function ($item) use ($field) {
                    return json_decode($item->{$field[0]})->{$field[1]};
                });
            } else {
                $data = $items->sortByDesc(function ($item) use ($field) {
                    return json_decode($item->{$field[0]})->{$field[1]};
                });
            }
        } else {
            $data = $this->sortAsc
                ? $items->sortBy($this->sortField)
                : $items->sortByDesc($this->sortField);
        }

        // Paginate the results
        $data = new LengthAwarePaginator(
            $data->forPage($this->page, $this->perPage),
            $data->count(),
            $this->perPage,
            $this->page,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return view('livewire-smart-table::livewire-smart-table', [
            'data' => $data,
            'columns' => $this->columns,
        ]);
    }
}
