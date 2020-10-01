@extends('sap::layouts.app')
@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <div class="btn-toolbar justify-content-between" role="toolbar">
            <div class="btn-group" role="group">
                <a href="{{ route('nav.list') }}" class="btn btn-link">
                <i class="fas fa-angle-double-left mr-2"></i></a>
                <h3 class="m-0 font-weight-bold text-primary">Edit Nav</h3>
            </div>
            <div class="btn-group" role="group">
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <form novalidate data-ajax-form method="POST" action="{{ route('nav.update',[$model->id]) }}" enctype="multipart/form-data">
                @csrf
                @method('PATCH')
                <x-sap::select-field name="brand_id" id="brand_id" label="Brand" :class="['']" :attribute_tags="[]" :data="['style'=>'border bg-white','live-search'=>false]" :options="app(config('sap.models.brand'))->query()->pluck('name','id')->toArray()" :selected="$model->brand_id ?? []"/>
                <x-sap::select-field name="locale" id="locale" label="Locale" :class="['']" :attribute_tags="[]" :data="['style'=>'border bg-white','live-search'=>false]" :options="settings('locales')" :selected="$model->locale ?? []"/>
                <x-sap::input-field type="text" name="name" id="name" label="Name" :class="[]" :value="$model->name ?? ''"/>
                <x-sap::input-field type="text" name="group_slug" id="group_slug" label="Group Slug" :class="[]" :value="$model->group_slug ?? ''"/>
                <x-sap::input-field type="number" name="seq" id="seq" label="Ordering" :class="[]" :value="$model->seq ?? '1'"/>
                <x-sap::input-field type="text" name="icon" id="icon" label="Icon" :class="[]" :value="$model->icon ?? ''"/>
                @include('sap::admin.nav.routeSlugSelect')
                @include('sap::admin.nav.routeParamsInput')
                <x-sap::select-field name="status" id="status" label="Status" :class="['']" :attribute_tags="[]" :data="['style'=>'border bg-white','live-search'=>false]" :options="settings('nav_status')" :selected="$model->status ?? []"/>
                <button type="submit" class="btn btn-primary">
                Save
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
