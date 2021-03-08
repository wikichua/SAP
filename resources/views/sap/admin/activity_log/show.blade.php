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
            <x-sap::display-field type="text" name="id" id="id" label="ID" :value="$model->id ?? ''"/>
            <x-sap::display-field type="text" name="brand" id="brand" label="Brand" :value="$model->brand->name ?? ''"/>
            <x-sap::display-field type="text" name="user" id="user" label="User" :value="$model->user->name ?? ''"/>
            <x-sap::display-field type="text" name="model_id" id="model_id" label="Model ID" :value="$model->model_id ?? ''"/>
            <x-sap::display-field type="text" name="model_class" id="model_class" label="Model" :value="$model->model_class ?? ''"/>
            <x-sap::display-field type="text" name="created_at" id="created_at" label="Created At" :value="$model->created_at ?? ''"/>
            <x-sap::display-field type="text" name="message" id="message" label="Message" :value="$model->message ?? ''"/>
            <x-sap::display-field type="json" name="data" id="data" label="Data" :value="$model->data ?? ''"/>
            <x-sap::display-field type="json" name="agents" id="agents" label="Agents" :value="$model->agents ?? ''"/>
            <x-sap::display-field type="json" name="iplocation" id="iplocation" label="Ip Location" :value="$model->iplocation ?? ''"/>
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
