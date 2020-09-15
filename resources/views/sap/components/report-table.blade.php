@php
  $data = $attributes->get('data',[]);
  $fields = count($data)? array_keys((array)$data[0]):[];
@endphp
<table class="table table-sm">
  <thead>
    <tr>
      @foreach ($fields as $field)
      <th scope="col">
        {{ $loop->index? str_replace('_',' ',\Str::title(\Str::snake($field))):'#' }}
      </th>
      @endforeach
    </tr>
  </thead>
  <tbody>
    @foreach ($data as $model)
    <tr>
      @foreach ($fields as $field)
      <td>{{ isset($model[$field])? $model[$field]:(isset($model->{$field})? $model->{$field}:'') }}</td>
      @endforeach
    </tr>
    @endforeach
  </tbody>
</table>

@push('scripts')
@endpush
