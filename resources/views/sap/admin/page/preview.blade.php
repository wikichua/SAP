@extends(strtolower($model->brand->name).'::'.($model->template ?? 'layouts.main'))

@section('content')
{!! Help::viewRenderer($model->blade) !!}
@endsection

@push('styles')
    @foreach ($model->styles as $style)
    {!! $style ?? ''!!}
    @endforeach
@endpush

@push('scripts')
    @foreach ($model->scripts as $script)
    {!! $script ?? '' !!}
    @endforeach
@endpush

