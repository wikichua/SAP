@extends('sap::layouts.app')
@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <div class="btn-toolbar justify-content-between" role="toolbar">
            <div class="btn-group" role="group">
                <a href="{{ route('role.list') }}" class="btn btn-link">
                <i class="fas fa-angle-double-left mr-2"></i></a>
                <h3 class="m-0 font-weight-bold text-primary">New Role</h3>
            </div>
            <div class="btn-group" role="group">
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <form novalidate data-ajax-form method="POST" action="{{ route('role.store') }}" enctype="multipart/form-data">
                @csrf
                <x-sap-input-field type="text" name="name" id="name" label="Name" :class="[]" value=""/>
                <x-sap-select-field name="admin" id="admin" label="Is Admin" :class="[]" :data="['style'=>'border bg-white','live-search'=>false]" :options="[false=>'No',true=>'Yes']" :selected="[]"/>
				<x-sap-checkboxes-field name="permissions" id="permissions" label="Permissions" :class="[]" :options="$group_permissions" :isGroup="true"/>
              	<button type="submit" class="btn btn-primary">Submit</button>
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
