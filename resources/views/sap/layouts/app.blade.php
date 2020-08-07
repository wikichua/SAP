@extends('sap::layouts.master')

@section('container')
    <div class="mt-3">
        @yield('content')
    </div>
@endsection

@push('scripts')
<script>
$(function() {
    $(document).on("change", ".image-file", function(e) {
        previewImage($(this));
    });
});
</script>
@endpush
