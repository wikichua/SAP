<table class="table table-hover table-striped table-bordered">
  <thead class="thead-dark">
    <tr>
      @foreach ($html as $el)
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
    @foreach ($html as $el)
    <th>@{{{ @php echo $el['data']; @endphp }}}</th>
    @endforeach
  </tr>
  @{{/each}}
</script>
<script>
  const url = '{{ $getUrl }}';
</script>
<script type="text/javascript" src="{{ asset('js/datatable.min.js') }}"></script>
@endpush