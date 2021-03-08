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
            <x-sap::display-field type="text" name="slug" id="slug" label="Slug" :value="$model->slug"/>
            <x-sap::display-field type="text" name="brand_id" id="brand_id" label="Brand" :value="$model->brand_id"/>
            <x-sap::display-field type="text" name="image_url" id="image_url" label="Image" :value="$model->image_url"/>
            <x-sap::display-field type="text" name="caption" id="caption" label="Caption" :value="$model->caption"/>
            <x-sap::display-field type="text" name="seq" id="seq" label="Ordering" :value="$model->seq"/>
            <x-sap::display-field type="text" name="tags" id="tags" label="Tags" :value="$model->tags"/>
            <x-sap::display-field type="text" name="published_at" id="published_at" label="Published Date" :value="$model->published_at"/>
            <x-sap::display-field type="text" name="expired_at" id="expired_at" label="Expired Date" :value="$model->expired_at"/>
            <x-sap::display-field type="text" name="status" id="status" label="Status" :value="$model->status"/>
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
