@extends('sap::layouts.app')
@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <div class="btn-toolbar justify-content-between" role="toolbar">
            <div class="btn-group" role="group">
                <a href="{{ route('report.list') }}" class="btn btn-link">
                <i class="fas fa-angle-double-left mr-2"></i></a>
                <h3 class="m-0 font-weight-bold text-primary">Edit Report</h3>
            </div>
            <div class="btn-group" role="group">
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <form novalidate data-ajax-form method="POST" action="{{ route('report.update',[$model->id]) }}" enctype="multipart/form-data">
                @csrf
                @method('PATCH')
                <x-sap-input-field type="text" name="name" id="name" label="Name" :class="[]" :value="$model->name ?? ''"/>
                <x-sap-input-field type="number" name="cache_ttl" id="cache_ttl" label="TTL (Seconds)" :class="[]" :value="$model->cache_ttl ?? '300'"/>
                @include('sap::admin.report.queryInput')
                <x-sap-select-field name="status" id="status" label="Status" :class="['']" :attribute_tags="[]" :data="['style'=>'border bg-white','live-search'=>false]" :options="settings('report_status')" :selected="$model->status ?? []"/>
                <button type="submit" class="btn btn-primary">
                Save & Show
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
    $(document).ready(function() {
    });
</script>
@endpush
