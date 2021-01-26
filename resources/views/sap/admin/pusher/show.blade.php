@extends('sap::layouts.app')
@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <div class="btn-toolbar justify-content-between" role="toolbar">
            <div class="btn-group" role="group">
                <a href="{{ route('pusher.list') }}" class="btn btn-link">
                <i class="fas fa-angle-double-left mr-2"></i></a>
                <h3 class="m-0 font-weight-bold text-primary">Show Pusher</h3>
            </div>
            <div class="btn-group" role="group">
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <x-sap::display-field type="text" name="id" id="id" label="ID" :value="$model->id"/>
            <x-sap::display-field type="text" name="title" id="title" label="Title" :value="$model->title"/>
            <x-sap::display-field type="text" name="locale" id="locale" label="Locale" :value="$model->locale"/>
            <x-sap::display-field type="text" name="message" id="message" label="Message" :value="$model->message"/>
            <x-sap::display-field type="text" name="event" id="event" label="Event" :value="$model->event_name"/>
            <x-sap::display-field type="text" name="icon" id="icon" label="Icon" :value="$model->icon"/>
            <x-sap::display-field type="text" name="link" id="link" label="link" :value="$model->link"/>
            <x-sap::display-field type="text" name="timeout" id="timeout" label="Time Out (ms)" :value="$model->timeout"/>
            <x-sap::display-field type="text" name="scheduled_at" id="scheduled_at" label="Scheduled At" :value="$model->scheduled_at"/>
            <x-sap::display-field type="text" name="status" id="status" label="Status" :value="$model->status_name"/>
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
