@extends('sap::layouts.app')
@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <div class="btn-toolbar justify-content-between" role="toolbar">
            <div class="btn-group" role="group">
                {{ \Breadcrumbs::render('breadcrumb') }}
            </div>
            <div class="btn-group" role="group">
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <x-sap::display-field type="text" name="id" id="id" label="ID" :value="$model->id"/>
            <x-sap::display-field type="text" name="name" id="name" label="Name" :value="$model->name"/>
            <x-sap::display-field type="text" name="admin" id="admin" label="Is Admin" :value="$model->isAdmin"/>
            <x-sap::display-field type="text" name="created_at" id="created_at" label="Created At" :value="$model->created_at"/>
            <x-sap::display-field type="text" name="created_by" id="created_by" label="Created By" :value="$model->creator->name"/>
            <x-sap::display-field type="text" name="updated_at" id="updated_at" label="Updated At" :value="$model->updated_at"/>
            <x-sap::display-field type="text" name="updated_by" id="updated_by" label="Updated By" :value="$model->modifier->name"/>
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
