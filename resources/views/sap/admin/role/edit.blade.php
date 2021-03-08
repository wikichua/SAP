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
            <x-sap::form ajax="true" method="PATCH" action="{{ route('role.update',[$model->id]) }}">
                <x-sap::input-field type="text" name="name" id="name" label="Name" :class="[]" :value="$model->name"/>
                <x-sap::select-field name="admin" id="admin" label="Is Admin" :class="[]" :data="['style'=>'border bg-white','live-search'=>false]" :options="[false=>'No',true=>'Yes']" :selected="[$model->admin]"/>
                <x-sap::checkboxes-field name="permissions" id="permissions" label="Permissions" :class="[]" :options="$group_permissions" :isGroup="true" :checked="$model->permissions->pluck('id')->toArray()"/>
                <button type="submit" class="btn btn-primary">
                Submit
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
