@extends('sap::layouts.app')
@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <div class="btn-toolbar justify-content-between" role="toolbar">
            <div class="btn-group" role="group">
                <a href="{{ route('cronjob.list') }}" class="btn btn-link">
                <i class="fas fa-angle-double-left mr-2"></i></a>
                <h3 class="m-0 font-weight-bold text-primary">Edit Cronjob</h3>
            </div>
            <div class="btn-group" role="group">
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <x-sap::form ajax="true" method="PATCH" action="{{ route('cronjob.update',[$model->id]) }}">
                <x-sap::input-field type="text" name="name" id="name" label="Name" :class="[]" :value="$model->name ?? ''"/>
                <x-sap::input-field type="text" name="command" id="command" label="Command" :class="[]" :value="$model->command ?? ''"/>
                <x-sap::select-field name="timezone" id="timezone" label="Timezone" :class="[]" :data="['style'=>'border bg-white','live-search'=>true]" :options="timezones()" :selected="$model->timezone ?? []"/>
                <x-sap::select-field name="frequency" id="frequency" label="Frequency" :class="[]" :data="['style'=>'border bg-white','live-search'=>true]" :options="cronjob_frequencies()" :selected="$model->frequency ?? []"/>
                <x-sap::select-field name="status" id="status" label="Status" :class="['']" :attribute_tags="[]" :data="['style'=>'border bg-white','live-search'=>false]" :options="settings('report_status')" :selected="$model->status ?? []"/>
                <button type="submit" class="btn btn-primary">
                Save & Show
                </button>
            </x-sap::form>
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
