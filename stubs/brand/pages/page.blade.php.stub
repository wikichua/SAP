@extends('{%brand_string%}::'.($model->template ?? 'layouts.main'))

@section('content')
<section class="mt-5 wow fadeIn">
  <div class="row wow fadeIn">
    <div class="col-lg-6 col-md-12 px-4">
      <div class="row">
        <div class="col-1 mr-3">
          <i class="fas fa-code fa-2x indigo-text"></i>
        </div>
        <div class="col-10">
          {!! Help::viewRenderer($model->blade) !!}
        </div>
      </div>
    </div>
  </div>
</section>
<hr class="my-5">
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


