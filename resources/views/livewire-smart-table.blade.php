<div>
    @if ($data->isEmpty())
        <div class="alert alert-outline-warning">There is no submission for this form.</div>
    @else
    <div class="row mb-4">
        <div class="col form-inline">
            Per Page: &nbsp;
            <select wire:model="perPage" class="form-control">
                <option>10</option>
                <option>20</option>
                <option>30</option>
            </select>
        </div>

        <div class="col">
            <input wire:model="search" type="text" class="form-control" placeholder="Search...">
        </div>
    </div>

    <div class="row">
        <table class="{{ $tableClass }}">
            <thead>
                <tr>
                    @foreach ($columns as $column => $props)
                    <th>
                        @if (isset($props['from']))
                            <a wire:click.prevent="sortBy('{{ $props['from'] }}->{{ $column }}')" role="button" href="#">
                                {{ $column }}
                            </a>
                        @else
                            <a wire:click.prevent="sortBy('{{ $props }}')" role="button" href="#">
                                {{ $props }}
                            </a>
                            @include('livewire-smart-table::sort-icon', ['field' => $props])
                        @endif
                    </th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $item)
                <tr>
                    @foreach ($columns as $column => $props)
                        @if (isset($props['from']))
                            <td>{{ json_decode($item[$props['from']])->{$column} }}</td>
                        @else
                            <td>{{ $item[$props] }}</td>
                        @endif
                    @endforeach
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="row">
        <div class="col">
            {{ $data->links() }}
        </div>

        <div class="col text-right text-muted">
            Showing {{ $data->firstItem() }} to {{ $data->lastItem() }} out of {{ $data->total() }} results
        </div>
    </div>
    @endif
</div>
