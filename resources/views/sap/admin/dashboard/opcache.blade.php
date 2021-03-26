@extends('sap::layouts.app')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap pt-1 pb-2 mb-1 row">
        <div class="btn-toolbar col-md-10">
            <span class="h2">
                <span id="subTitle">OpCache Manager</span>
            </span>
        </div>
    </div>
    <div class="embed-responsive embed-responsive-16by9">
        <div class="embed-responsive-item">
            @php
                include_once base_path('vendor/amnuts/opcache-gui/index.php');
            @endphp
        </div>
    </div>
@endsection

@push('scripts')
<script>
    $(function() {

    });
</script>
@endpush
