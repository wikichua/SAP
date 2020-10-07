@php
  $data = $attributes->get('data',[]);
@endphp
<div id="toolbar">
  <div class="form-inline" role="form">
    <x-sap::select-field name="take" id="pageTake" label="Page Limit" :class="['pageTake', 'ml-1']" :attribute_tags="[]" :data="['style'=>'border bg-white','live-search'=>false]" :options="[10 => 10,25 => 25,50 => 50,100 => 100,200 => 200]" :selected="old('take',25)"/>
  </div>
</div>
<table id="bootstrap-table" class="bootstrap-table table table-hover table-striped table-bordered"
  data-toolbar="#toolbar"
  data-show-columns="true"
  data-use-row-attr-func="true"
  data-reorderable-rows="true"
  data-show-toggle="true"
  >
  <thead class="thead-dark">
    <tr>
      @foreach ($data as $el)
      <th data-field="{{ $el['data'] }}" data-sortable="{{ isset($el['sortable']) && $el['sortable']? 'true':'false' }}">
        @if ($loop->last)
        Action
        @else
        {{ $el['title'] }}
        @endif
      </th>
      @endforeach
    </tr>
  </thead>
  <tbody id="datatable-row">
  </tbody>
</table>

<div id="datatable-pagination" class="d-flex justify-content-center m-5"></div>

@once
  @push('styles')
  <link href="https://unpkg.com/jquery-resizable-columns@0.2.3/dist/jquery.resizableColumns.css" rel="stylesheet">
  <link href="https://unpkg.com/bootstrap-table@1.18.0/dist/extensions/reorder-rows/bootstrap-table-reorder-rows.css" rel="stylesheet">
  @endpush
  @push('scripts')
  <script src="https://unpkg.com/jquery-resizable-columns@0.2.3/dist/jquery.resizableColumns.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/TableDnD/1.0.3/jquery.tablednd.min.js"></script>
  @endpush
@endonce

@push('scripts')
<script>
  url = '{{ $attributes['url'] }}';
</script>
@endpush
