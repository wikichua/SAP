@extends('sap::layouts.app')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap pt-1 pb-2 mb-1 row">
        <div class="btn-toolbar col-md-10">
            <span class="h2">
                <span id="subTitle">Dashboard</span>
            </span>
        </div>
    </div>
    <div class="container">
        <x-sap::queue-dashboard />
    </div>
@endsection

@push('scripts')
@endpush
