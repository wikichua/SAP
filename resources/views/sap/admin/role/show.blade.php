@extends('sap::layouts.app')
@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <div class="btn-toolbar justify-content-between" role="toolbar">
            <div class="btn-group" role="group">
                <a href="{{ route('role.list') }}" class="btn btn-link">
                <i class="fas fa-angle-double-left mr-2"></i></a>
                <h3 class="m-0 font-weight-bold text-primary">Show Role</h3>
            </div>
            <div class="btn-group" role="group">
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <form novalidate data-ajax-form method="POST" action="{{ route('role.store') }}" enctype="multipart/form-data">
                @csrf
                <x-sap-display-field type="text" name="id" id="id" label="ID" :value="$model->id"/>
                <x-sap-display-field type="text" name="name" id="name" label="Name" :value="$model->name"/>
                <x-sap-display-field type="text" name="admin" id="admin" label="Is Admin" :value="$model->admin"/>
                <x-sap-display-field type="text" name="created_at" id="created_at" label="Created At" :value="$model->created_at"/>
                <x-sap-display-field type="text" name="created_by" id="created_by" label="Created By" :value="$model->creator->name"/>
                <x-sap-display-field type="text" name="updated_at" id="updated_at" label="Updated At" :value="$model->updated_at"/>
                <x-sap-display-field type="text" name="updated_by" id="updated_by" label="Updated By" :value="$model->modifier->name"/>
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
