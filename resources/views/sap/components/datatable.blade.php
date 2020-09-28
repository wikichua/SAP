@php
  $data = $attributes->get('data',[]);
@endphp
<table class="table table-hover table-striped table-bordered">
  <thead class="thead-dark">
    <tr>
      @foreach ($data as $el)
      <th>
        <a href="#" @if(isset($el['sortable']) && $el['sortable']) data-key="{{ $el['data'] }}" data-direction="asc" @endif class="sortable text-white text-decoration-none">
          {{ $el['title'] }} @if(isset($el['sortable']) && $el['sortable']) <i class="icon fa fa-fw"></i> @endif
        </a>
      </th>
      @endforeach
    </tr>
  </thead>
  <tbody id="datatable-row">
  </tbody>
</table>

<div id="datatable-pagination" class="d-flex justify-content-center"></div>

@push('scripts')
<script id="row-template" type="text/x-lodash-template">
  <% _.forEach(data, function(el) { %>
  <tr>
    @foreach ($data as $el)
    <th><%= el.{{ $el['data'] }} %></th>
    @endforeach
  </tr>
  <% }); %>
</script>
<script>
  url = '{{ $attributes['url'] }}';
</script>
@endpush
