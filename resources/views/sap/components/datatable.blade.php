@php
  $data = $attributes->get('data',[]);
@endphp
<div id="toolbar-secondary" class="float-right mr-1">
  <div class="form-inline" role="form">
    <x-sap::select-field name="take" id="pageTake" label="Page Limit" :class="['pageTake', 'ml-1']" :attribute_tags="[]" :data="['style'=>'border bg-white','live-search'=>false]" :options="[10 => 10,25 => 25,50 => 50,100 => 100,200 => 200]" :selected="old('take',25)"/>
  </div>
</div>
<table id="bootstrap-table" class="bootstrap-table table table-hover table-striped table-bordered"
  data-toolbar="#toolbar-primary, #toolbar-secondary"
  data-show-columns="true"
  data-show-toggle="true"
  data-resizable="true"
  data-sticky-header="true"
  data-sticky-header-offset-left="16.8em"
  data-sticky-header-offset-right="2.8em"
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
  <tbody>
  </tbody>
</table>

<div id="datatable-pagination" class="d-flex justify-content-center m-5"></div>

@once
  @push('styles')
  <link href="//unpkg.com/jquery-resizable-columns@0.2.3/dist/jquery.resizableColumns.css" rel="stylesheet">
  @endpush
  @push('scripts')
  <script src="//unpkg.com/jquery-resizable-columns@0.2.3/dist/jquery.resizableColumns.min.js"></script>
  @endpush
@endonce

@push('scripts')
<script>
  url = '{{ $attributes['url'] }}';
</script>
@endpush
