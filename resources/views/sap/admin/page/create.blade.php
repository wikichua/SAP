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
            <x-sap::form ajax="true" method="POST" action="{{ route('page.store') }}">
                <x-sap::select-field name="brand_id" id="brand_id" label="Brand" :class="['']" :attribute_tags="[]" :data="['style'=>'border bg-white','live-search'=>false]" :options="app(config('sap.models.brand'))->query()->pluck('name','id')->toArray()" :selected="$model->brand_id ?? []"/>
                @include('sap::admin.page.templateSelect')
                <x-sap::input-field type="text" name="blade_file" id="blade_file" label="Blade File" :class="[]" :value="$model->blade_file ?? 'page'"/>
                <x-sap::select-field name="locale" id="locale" label="Locale" :class="['']" :attribute_tags="[]" :data="['style'=>'border bg-white','live-search'=>false]" :options="settings('locales')" :selected="$model->locale ?? []"/>
                <x-sap::input-field type="text" name="name" id="name" label="Name" :class="[]" :value="$model->name ?? ''"/>
                <x-sap::input-field type="text" name="slug" id="slug" label="Slug" :class="[]" :value="$model->slug ?? ''"/>
                @include('sap::admin.page.styleInput')
                <x-sap::editor-field name="blade" id="blade" label="Content" :class="['']" :attribute_tags="[]" :value="$model->blade ?? ''" :codeview='false'/>
                @include('sap::admin.page.scriptInput')
                <x-sap::date-field name="published_at" id="published_at" label="Published Date" :class="['']" :attribute_tags="[]" :value="$model->published_at ?? ''"/>
                <x-sap::date-field name="expired_at" id="expired_at" label="Expired Date" :class="['']" :attribute_tags="[]" :value="$model->expired_at ?? ''"/>
                <x-sap::select-field name="status" id="status" label="Status" :class="['']" :attribute_tags="[]" :data="['style'=>'border bg-white','live-search'=>false]" :options="settings('page_status')" :selected="$model->status ?? []"/>
                <button type="submit" class="btn btn-primary">
                Save & Preview
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
