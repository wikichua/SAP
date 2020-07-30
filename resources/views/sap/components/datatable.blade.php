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
<script id="row-template" type="text/x-handlebars-template">
  @{{#each data}}
  <tr>
    @foreach ($data as $el)
    <th>@{{{ @php echo $el['data']; @endphp }}}</th>
    @endforeach
  </tr>
  @{{/each}}
</script>
<script>
  url = '{{ $attributes['url'] }}';
</script>
@endpush