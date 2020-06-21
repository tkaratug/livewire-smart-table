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

    public $sortIcon = '&#8597;';

    public $sortAscIcon = '&#8593;';

    public $sortDescIcon = '&#8595;';

    public function mount(
        Collection $query,
        string $tableClass = 'table')
    {
        $this->query = $query;
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
        $items = $this->prepareData($this->query);

        // If user search something, filter items by input
        if (!empty($this->search)) {
            $items = $this->query->filter(function ($item) {
                $found = false;
                foreach ($this->columns as $column => $props) {
                    if ($props['type'] !== 'actions') {
                        if (stripos($item->{$column}, $this->search) !== false) {
                            $found = true;
                        }
                    }
                }
                return $found;
            });
        }

        // If the field that wanted to be sorted is from a json data
        $data = $this->sortAsc
            ? $items->sortBy($this->sortField)
            : $items->sortByDesc($this->sortField);

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

    /**
     * Prepare data by columns
     * 
     * @param Collection $query
     * @return Collection
     */
    private function prepareData(Collection $query)
    {
        foreach ($query as $item) {
            foreach ($this->columns as $key => $props) {
                if (isset($props['type'])) {
                    switch ($props['type']) {
                        case 'json':
                            $from = json_decode($item->{$props['from']});
                            $nastedFields = explode('.', $props['value']);
                            foreach ($nastedFields as $field) {
                                $from = $from->$field;
                            }
                            $item->{$key} = $from;
                            break;
                        case 'link':
                            $item->{$key.'_url'} = $this->makeUrl($props['url'], $item);
                            break;
                        case 'actions':
                            $actions = [];
                            foreach ($props['actions'] as $action) {
                                $actions[] = [
                                    'element' => $action['element'],
                                    'url' => $this->makeUrl($action['url'], $item),
                                ];
                                $item->{$key} = $actions;
                            }
                            break;
                        default:
                    }
                }
            }
        }
        return $query;
    }

    /**
     * Make url
     * 
     * @param string $url
     * @param $item
     */
    private function makeUrl(string $url, $item)
    {
        preg_match_all('/\{.*?\}/', $url, $matches);

        foreach ($matches[0] as $match) {
            preg_match('/\{([^\]]*)\}/', $match, $field);
            $url = str_replace($match, $item->{$field[1]}, $url);
        }
        
        return $url;
    }
}
