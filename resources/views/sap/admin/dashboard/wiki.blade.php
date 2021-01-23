@extends('sap::layouts.app')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap pt-1 pb-2 mb-1 row">
        <div class="btn-toolbar col-md-10">
            <span class="h2">
                <span id="subTitle">Wiki Docs</span>
            </span>
        </div>
    </div>
    <div class="container">
        <div class="card">
            <div class="card-body">
                {!! markdown($md) !!}
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    $(function() {

    });
</script>
@endpush
