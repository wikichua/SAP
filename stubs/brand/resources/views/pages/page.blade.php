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
          {!! Help::viewRenderer(html_entity_decode($model->blade),get_defined_vars()) !!}
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
    {!! viewRenderer($script,get_defined_vars()) !!}
    @endforeach
@endpush


