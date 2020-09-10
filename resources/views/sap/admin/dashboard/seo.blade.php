@extends('sap::layouts.app')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap pt-1 pb-2 mb-1 row">
        <div class="btn-toolbar col-md-10">
            <span class="h2">
                <span id="subTitle">SEO Manager</span>
            </span>
        </div>
    </div>
    <div class="embed-responsive embed-responsive-16by9">
        <iframe src="./seo-manager" class="embed-responsive-item"></iframe>
    </div>
@endsection

@push('scripts')
<script>
    $(function() {

    });
</script>
@endpush
