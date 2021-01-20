@extends('sap::layouts.app')
@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <div class="btn-toolbar justify-content-between" role="toolbar">
            <div class="btn-group" role="group">
                <a href="{{ route('nav.list') }}" class="btn btn-link">
                <i class="fas fa-angle-double-left mr-2"></i></a>
                <h3 class="m-0 font-weight-bold text-primary">Show Nav</h3>
            </div>
            <div class="btn-group" role="group">
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            @php
                $link = '<a href="'.route_slug(strtolower($model->brand->name).'.page',$model->route_slug,$model->route_params, $model->locale).'" target="_blank">'.$model->name.'</a>';
            @endphp
            <x-sap::display-field type="text" name="sample" id="sample" label="Display" :value="$link"/>
            <x-sap::display-field type="text" name="id" id="id" label="ID" :value="$model->id"/>
            <x-sap::display-field type="text" name="seq" id="seq" label="Ordering" :value="$model->seq"/>
            <x-sap::display-field type="text" name="name" id="name" label="Name" :value="$model->name"/>
            <x-sap::display-field type="text" name="brand_id" id="brand_id" label="Brand" :value="$model->brand->name"/>
            <x-sap::display-field type="text" name="locale" id="locale" label="Locale" :value="$model->locale"/>
            <x-sap::display-field type="text" name="group_slug" id="group_slug" label="Group Slug" :value="$model->group_slug"/>
            <x-sap::display-field type="text" name="icon" id="icon" label="Icon" :value="$model->icon"/>
            <x-sap::display-field type="text" name="route_slug" id="route_slug" label="Route Slug" :value="$model->route_slug"/>
            <x-sap::display-field type="text" name="route_params" id="route_params" label="Route Params" :value="$model->route_params" :type="is_array($model->route_params)? 'list':''"/>
            <x-sap::display-field type="text" name="status" id="status" label="Status" :value="$model->status_name"/>
            <x-sap::display-field type="text" name="created_at" id="created_at" label="Created At" :value="$model->created_at"/>
            <x-sap::display-field type="text" name="created_by" id="created_by" label="Created By" :value="$model->creator->name ?? ''"/>
            <x-sap::display-field type="text" name="updated_at" id="updated_at" label="Updated At" :value="$model->updated_at"/>
            <x-sap::display-field type="text" name="updated_by" id="updated_by" label="Updated By" :value="$model->modifier->name ?? ''"/>
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
