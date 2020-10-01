@php
	foreach ($attributes as $key => $val) {
		$$key = $val;
	}
	$value = isset($value)? $value:'';
@endphp
<div class="list-group-item">
    <div class="form-group row mb-0">
        <label class="col-md-3 col-form-label">{{ $label }}</label>
        <div class="col-md-9">
            <div class="form-control-plaintext">
            	@switch($type)
				    @case('list')
				        {!! implode('<br>', $value) !!}
				        @break
				    @case('json')
				        @forelse ($value as $k => $v)
				        	<li class="list-unstyled">{!! $k !!} : {!! $v !!}</li>
				        @empty
				        	Null
				        @endforelse
				        @break
				    @default
				        {!! $value !!}
				@endswitch
        	</div>
        </div>
    </div>
</div>
