@extends('sap::layouts.app')
@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <div class="btn-toolbar justify-content-between" role="toolbar">
            <div class="btn-group" role="group">
                <a href="{{ route('user.list') }}" class="btn btn-link">
                <i class="fas fa-angle-double-left mr-2"></i></a>
                <h3 class="m-0 font-weight-bold text-primary">Edit User</h3>
            </div>
            <div class="btn-group" role="group">
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <form novalidate data-ajax-form method="POST" action="{{ route('user.update',[$model->id]) }}" enctype="multipart/form-data">
                @csrf
                @method('PATCH')
                <x-sap-input-field type="text" name="name" id="name" label="Full Name" :class="[]" :value="$model->name"/>
                <x-sap-input-field type="email" name="email" id="email" label="Email" :class="[]" :value="$model->email"/>
                <x-sap-select-field name="type" id="type" label="Type" :class="[]" :data="['style'=>'border bg-white','live-search'=>false]" :options="settings('user_types')" :selected="$model->type"/>
                <x-sap-checkboxes-field name="roles" id="roles" label="Roles" :class="[]" :options="$roles->toArray()" :checked="$model->roles()->pluck('role_id')->toArray()" :isGroup="false"/>
                <button type="submit" class="btn btn-primary">
                Submit
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
