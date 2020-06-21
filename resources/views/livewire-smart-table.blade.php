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
            <input wire:model.debounce:500ms="search" type="text" class="form-control" placeholder="Search...">
        </div>
    </div>

    <div class="row">
        <table class="{{ $tableClass }}">
            <thead>
                <tr>
                    @foreach ($columns as $column => $props)
                    <th>
                        @if ($props['type'] !== 'actions')
                        <a wire:click.prevent="sortBy('{{ $column }}')" role="button" href="#">
                            @if (isset($props['name']))
                                {{ $props['name'] }}
                            @else
                                {{ $column }}
                            @endif
                        </a>
                        @include('livewire-smart-table::sort-icon', ['field' => $column])
                        @else
                            @if (isset($props['name']))
                                {{ $props['name'] }}
                            @else
                                {{ $column }}
                            @endif
                        @endif
                    </th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $item)
                <tr>
                    @foreach ($columns as $column => $props)
                        @if ($props['type'] === 'link')
                        <td class="{{ $props['class'] ?? '' }}">
                            <a href="{{ $item->{$column.'_url'} }}" target="{{ $props['target'] ?? '_blank' }}">
                                {!! $item->{$column} !!}
                            </a>
                        </td>
                        @elseif ($props['type'] === 'actions')
                        <td class="{{ $props['class'] ?? '' }}">
                            @foreach ($item->{$column} as $option)
                            <a href="{{ $option['url'] }}">{!! $option['element'] !!}</a>
                            @endforeach
                        </td>
                        @else
                        <td class="{{ $props['class'] ?? '' }}">{!! $item->{$column} !!}</td>
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
