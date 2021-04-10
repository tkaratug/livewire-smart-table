<div>
    @if ($data->isEmpty())
        <div class="bg-red-100 border border-red-200 text-red-600 p-3 rounded relative" role="alert">
            <span class="block sm:inline">No data have found.</span>
        </div>
    @else
        <div class="container mx-auto py-6 px-4">
            <div class="mb-4 flex justify-between items-center">
                <div class="flex-1 pr-4">
                    <div class="relative md:w-1/3">
                        <input
                            wire:model.debounce:500ms="search"
                            type="search"
                            class="w-full pl-10 pr-4 py-2 rounded-lg shadow focus:outline-none focus:shadow-outline text-gray-600 font-medium"
                            placeholder="Search...">
                        <div class="absolute top-0 left-0 inline-flex items-center p-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gray-400" viewBox="0 0 24 24"
                                 stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round"
                                 stroke-linejoin="round">
                                <rect x="0" y="0" width="24" height="24" stroke="none"></rect>
                                <circle cx="10" cy="10" r="7" />
                                <line x1="21" y1="21" x2="15" y2="15" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div>
                    <div class="shadow rounded-lg flex">
                        <div class="relative">
                            <div class="relative">
                                <select wire:model="perPage" class="appearance-none border-none inline-block py-2 pl-4 pr-4 rounded leading-tight w-full">
                                    <option>10</option>
                                    <option>20</option>
                                    <option>30</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow Ûrelative">
                <table class="border-collapse table-auto w-full whitespace-no-wrap bg-white table-striped relative {{ $tableClass }}">
                    <thead>
                    <tr class="text-left">
                        @foreach ($columns as $column => $props)
                            <th class="bg-gray-100 sticky top-0 border-b border-gray-200 px-6 py-2 text-gray-600 font-bold tracking-wider uppercase text-xs">
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
                                    <td class="border-dashed border-t border-gray-200 {{ $props['class'] ?? '' }}">
                                        <a href="{{ $item->{$column.'_url'} }}" target="{{ $props['target'] ?? '_blank' }}">
                                            {!! $item->{$column} !!}
                                        </a>
                                    </td>
                                @elseif ($props['type'] === 'actions')
                                    <td class="border-dashed border-t border-gray-200 {{ $props['class'] ?? '' }}">
                                        @foreach ($item->{$column} as $option)
                                            <a href="{{ $option['url'] }}">{!! $option['element'] !!}</a>
                                        @endforeach
                                    </td>
                                @else
                                    <td class="border-dashed border-t border-gray-200 {{ $props['class'] ?? '' }}">
                                        <span class="text-gray-700 px-6 py-3 flex items-center">{!! $item->{$column} !!}</span>
                                    </td>
                                @endif
                            @endforeach
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $data->links() }}
            </div>
        </div>
    @endif
</div>
