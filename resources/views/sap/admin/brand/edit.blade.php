@extends('sap::layouts.app')
@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <div class="btn-toolbar justify-content-between" role="toolbar">
            <div class="btn-group" role="group">
                <a href="{{ route('brand.list') }}" class="btn btn-link">
                <i class="fas fa-angle-double-left mr-2"></i></a>
                <h3 class="m-0 font-weight-bold text-primary">Edit Brand</h3>
            </div>
            <div class="btn-group" role="group">
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <x-sap::form ajax="true" method="PATCH" action="{{ route('brand.update',[$model->id]) }}">
                <x-sap::input-field type="text" name="name" id="name" label="Brand Name" :class="['']" :attribute_tags="['disabled' => 'disabled']" :value="$model->name ?? ''"/>
                <x-sap::input-field type="text" name="domain" id="domain" label="Domain" :class="['']" :attribute_tags="[]" :value="$model->domain ?? ''"/>
                <x-sap::date-field name="published_at" id="published_at" label="Published Date" :class="['']" :attribute_tags="[]" :value="$model->published_at ?? ''"/>
                <x-sap::date-field name="expired_at" id="expired_at" label="Expired Date" :class="['']" :attribute_tags="[]" :value="$model->expired_at ?? ''"/>
                <x-sap::select-field name="status" id="status" label="Status" :class="['']" :attribute_tags="[]" :data="['style'=>'border bg-white','live-search'=>false]" :options="settings('brand_status')" :selected="$model->status ?? []"/>
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
