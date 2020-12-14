@extends('sap::layouts.app')
@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <div class="btn-toolbar justify-content-between" role="toolbar">
            <div class="btn-group" role="group">
                <a href="{{ route('cronjob.list') }}" class="btn btn-link">
                <i class="fas fa-angle-double-left mr-2"></i></a>
                <h3 class="m-0 font-weight-bold text-primary">Show Cronjob</h3>
            </div>
            <div class="btn-group" role="group">
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
                <x-sap::display-field name="name" id="name" label="Name" :value="$model->name" type="text"/>
                <x-sap::display-field name="command" id="command" label="Command" :value="$model->command ?? ''" type="text"/>
                <x-sap::display-field name="timezone" id="timezone" label="Timezone" :value="$model->timezone ?? ''" type="text"/>
                <x-sap::display-field name="frequency" id="frequency" label="Frequency" :value="$model->frequency ?? ''" type="text"/>
                <x-sap::display-field name="status" id="status" label="Status" :value="$model->status_name" type="text"/>
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
