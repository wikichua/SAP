@extends('sap::layouts.master')

@section('container')
    <div class="mt-3">
        @yield('content')
    </div>
@endsection

@push('scripts')
<script>
$(function() {
});
</script>
@endpush
