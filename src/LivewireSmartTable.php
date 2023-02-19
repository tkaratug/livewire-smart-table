<?php

namespace Tkaratug\LivewireSmartTable;

use Illuminate\Contracts\View\Factory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class LivewireSmartTable extends Component
{
    use WithPagination;

    public Collection $query;

    public string $search = '';

    public $page = 1;

    public int $perPage = 10;

    public string $sortField = 'id';

    public bool $sortAsc = true;

    public array $columns = [];

    public string $tableClass = 'table';

    public string $sortIcon = '&#8597;';

    public string $sortAscIcon = '&#8593;';

    public string $sortDescIcon = '&#8595;';

    public function mount(Collection $query): void
    {
        $this->query = $query;
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
     * @return Factory|View
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

        // Sort results
        $data = $this->sortAsc
            ? $items->sortBy($this->sortField)
            : $items->sortByDesc($this->sortField);

        // Paginate results
        $data = new LengthAwarePaginator(
            $data->forPage($this->page, $this->perPage),
            $data->count(),
            $this->perPage,
            $this->page,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return view('livewire-smart-table::livewire-smart-table', [
            'data'    => $data,
            'columns' => $this->columns,
        ]);
    }

    /**
     * Prepare data by columns
     *
     * @param  Collection  $query
     * @return Collection
     * @throws \JsonException
     */
    private function prepareData(Collection $query): Collection
    {
        foreach ($query as $item) {
            foreach ($this->columns as $key => $props) {
                if (isset($props['type'])) {
                    switch ($props['type']) {
                        case 'json':
                            $from = $item->hasCast($props['from'], ['array'])
                                ? $item->{$props['from']}
                                : json_decode($item->{$props['from']}, true, 512, JSON_THROW_ON_ERROR);
                            $nestedFields = explode('.', $props['value']);
                            foreach ($nestedFields as $field) {
                                $from = $from[$field] ?? null;
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
     * @return string
     */
    private function makeUrl(string $url, $item): string
    {
        preg_match_all('/\{.*?\}/', $url, $matches);

        foreach ($matches[0] as $match) {
            preg_match('/\{([^\]]*)\}/', $match, $field);
            $url = str_replace($match, $item->{$field[1]}, $url);
        }

        return $url;
    }
}
