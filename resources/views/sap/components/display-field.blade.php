@php
	foreach ($attributes as $key => $val) {
		$$key = $val;
	}
	$value = trim(isset($value)? $value:'');
@endphp
<div class="list-group-item">
    <div class="form-group row mb-0">
        <label class="col-md-3 col-form-label">{!! $label !!}</label>
        <div class="col-md-9">
            <div class="form-control-plaintext">
            	@switch($type)
                    @case('date')
                        {{ \Carbon\Carbon::parse(trim($value)) }}
                        @break
                    @case('image')
                        @if (is_array($value))
                            @forelse ($value as $k => $v)
                                <li class="list-unstyled"><img src="{{ asset(trim($v)) }}" style="max-height:50px;" /></li>
                            @empty
                                Null
                            @endforelse
                        @endif
                        <img src="{{ asset(trim($value)) }}" style="max-height:50px;" />
                        @break
                    @case('file')
                        @if (is_array($value))
                            @forelse ($value as $k => $v)
                                <li class="list-unstyled"><a target="_blank" href="{{ asset(trim($v)) }}">{{ trim($value) }}</a></li>
                            @empty
                                Null
                            @endforelse
                        @endif
                        <a target="_blank" href="{{ asset(trim($value)) }}">{{ trim($value) }}</a>
                        @break
                    @case('editor')
                        {!! trim($value) !!}
                        @break
                    @case('markdown')
                        {!! markdown($value) !!}
                        @break
                    @case('list')
				        {!! implode('<br>', $value) !!}
				        @break
				    @case('json') {{-- special for key-paired --}}
                        @forelse ($value as $k => $v)
				        	<li class="list-unstyled">{!! $k !!} : {!! is_array($v)? '<pre class="text-muted">'.(json_encode($v,JSON_PRETTY_PRINT)).'</pre>':$v !!}</li>
				        @empty
				        	Null
				        @endforelse
				        @break
                    @case('code')
                            @if (json_decode($value))
                            <pre class="text-muted">@json($value, JSON_PRETTY_PRINT)</pre>
                            @else
                            <pre class="text-muted">{!! $value !!}</pre>
                            @endif
                        @break
				    @default
				        {{ $value }}
				@endswitch
        	</div>
        </div>
    </div>
</div>
