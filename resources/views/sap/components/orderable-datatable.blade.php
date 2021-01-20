@php
  $data = $attributes->get('data',[]);
@endphp
<table id="bootstrap-table" class="bootstrap-table table table-hover table-striped table-bordered"
  data-use-row-attr-func="true"
  data-reorderable-rows="true"
  data-sticky-header="true"
  data-sticky-header-offset-left="16.8em"
  data-sticky-header-offset-right="2.8em"
  >
  <thead class="thead-dark">
    <tr>
      @foreach ($data as $el)
      <th data-field="{{ $el['data'] }}">
        {{ $el['title'] }}
      </th>
      @endforeach
    </tr>
  </thead>
  <tbody>
  </tbody>
</table>
<form novalidate data-ajax-form method="POST" action="{{ $attributes['actUrl'] ?? '' }}">
  @csrf
  <input type="hidden" id="newRow" name="newRow" value="">
</form>
@once
  @push('styles')
  <link href="//unpkg.com/bootstrap-table@1.18.0/dist/extensions/reorder-rows/bootstrap-table-reorder-rows.css" rel="stylesheet">
  @endpush
  @push('scripts')
  <script src="//cdnjs.cloudflare.com/ajax/libs/TableDnD/1.0.3/jquery.tablednd.min.js"></script>
  @endpush
@endonce

@push('scripts')
<script>
  url = '{{ $attributes['url'] ?? '' }}';
</script>
@endpush
