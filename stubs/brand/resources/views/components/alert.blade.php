@if (Session::has('alert.config'))
@once
@push('styles')
@if(config('sweetalert.animation.enable'))
    <link rel="stylesheet"href="//cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
@endif
@endpush
@push('scripts')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script src="//cdn.jsdelivr.net/npm/promise-polyfill"></script>
<script>
    Swal.fire({!! Session::pull('alert.config') !!});
</script>
@endpush
@endonce
@endif
