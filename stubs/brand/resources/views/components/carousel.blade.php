<div id="carousel-{{ $uniqueId }}" class="carousel slide carousel-fade" data-ride="carousel">
  <!--Indicators-->
  <ol class="carousel-indicators">
    @foreach ($carousels as $carousel)
    <li data-target="#carousel-{{ $uniqueId }}" data-slide-to="{{ $loop->iteration }}" class="{{ $loop->iteration == 1? 'active':'' }}"></li>
    @endforeach
  </ol>
  <!--/.Indicators-->
  <!--Slides-->
  <div class="carousel-inner" role="listbox">
    @foreach ($carousels as $carousel)
    <div class="carousel-item {{ $loop->iteration == 1? 'active':'' }}">
      <div class="view" style="background-image: url('{{ asset($carousel->image_url) }}'); background-repeat: no-repeat; background-size: cover;">
        <!-- Mask & flexbox options-->
        <div class="mask rgba-black-light d-flex justify-content-center align-items-center">
          <!-- Content -->
          <div class="text-center white-text mx-5 wow fadeIn">
            <p class="mb-4 d-none d-md-block">
              <strong>{!! $carousel->caption !!}</strong>
            </p>
          </div>
          <!-- Content -->
        </div>
        <!-- Mask & flexbox options-->
      </div>
    </div>
    @endforeach
  </div>
  <!--/.Slides-->
  <!--Controls-->
  <a class="carousel-control-prev" href="#carousel-{{ $uniqueId }}" role="button" data-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="sr-only">Previous</span>
  </a>
  <a class="carousel-control-next" href="#carousel-{{ $uniqueId }}" role="button" data-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="sr-only">Next</span>
  </a>
  <!--/.Controls-->
</div>
@push('scripts')
<script>
$(function() {
});
</script>
@endpush
