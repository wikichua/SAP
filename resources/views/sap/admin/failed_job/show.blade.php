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
            <x-sap::display-field type="text" name="queue" id="queue" label="Queue" :value="$model->queue ?? ''"/>
            <x-sap::display-field type="code" name="payload" id="payload" label="Payload" :value="$model->payload ?? ''"/>
            <x-sap::display-field type="code" name="exception" id="exception" label="Exception" :value="$model->exception ?? ''"/>
            <x-sap::display-field type="text" name="failed_at" id="failed_at" label="Failed At" :value="$model->failed_at ?? ''"/>
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
