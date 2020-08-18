@extends('sap::layouts.app')
@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <div class="btn-toolbar justify-content-between" role="toolbar">
            <div class="btn-group" role="group">
                <a href="{{ route('permission.list') }}" class="btn btn-link">
                <i class="fas fa-angle-double-left mr-2"></i></a>
                <h3 class="m-0 font-weight-bold text-primary">Show Permission</h3>
            </div>
            <div class="btn-group" role="group">
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            @csrf
            <x-sap-display-field type="text" name="name" id="name" label="Brand Name" :value="$model->name" type=""/>
                <x-sap-display-field type="text" name="published_at" id="published_at" label="Published Date" :value="$model->published_at" type=""/>
                <x-sap-display-field type="text" name="expired_at" id="expired_at" label="Expired Date" :value="$model->expired_at" type=""/>
                <x-sap-display-field type="text" name="status" id="status" label="Status" :value="$model->status" type=""/>
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
