@if ($sortField !== $field)
    <i class="text-muted mdi mdi-sort"></i>
@elseif ($sortAsc)
    <i class="mdi mdi-sort-ascending"></i>
@else
    <i class="mdi mdi-sort-descending"></i>
@endif
