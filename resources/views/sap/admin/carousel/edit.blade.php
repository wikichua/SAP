@extends('sap::layouts.app')
@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <div class="btn-toolbar justify-content-between" role="toolbar">
            <div class="btn-group" role="group">
                <a href="{{ route('carousel.list') }}" class="btn btn-link">
                <i class="fas fa-angle-double-left mr-2"></i></a>
                <h3 class="m-0 font-weight-bold text-primary">Edit Carousel</h3>
            </div>
            <div class="btn-group" role="group">
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <form novalidate data-ajax-form method="POST" action="{{ route('carousel.update',[$model->id]) }}" enctype="multipart/form-data">
                @csrf
                @method('PATCH')
                <x-sap::input-field type="text" name="slug" id="slug" label="Slug" :class="['']" :attribute_tags="[]" :value="$model->slug ?? ''"/>
                <x-sap::select-field name="brand_id" id="brand_id" label="Brand" :class="['']" :attribute_tags="[]" :data="['style'=>'border bg-white','live-search'=>false]" :options="app(config('sap.models.brand'))->query()->pluck('name','id')->toArray()" :selected="$model->brand_id ?? []"/>
                <x-sap::image-field type="image" name="image_url" id="image_url" label="Image" :class="['']" :attribute_tags="[]" :value="$model->image_url ?? ''"/>
                <x-sap::textarea-field name="caption" id="caption" label="Caption" :class="['']" :attribute_tags="[]" :value="$model->caption ?? ''"/>
                <x-sap::input-field type="number" name="seq" id="seq" label="Ordering" :class="['']" :attribute_tags="['min'=>'1', 'max'=>'100']" :value="$model->seq ?? ''"/>
                <x-sap::select-field name="tags" id="tags" label="Tags" :class="['']" :attribute_tags="['multiple'=>'multiple']" :data="['style'=>'border bg-white','live-search'=>false]" :options="settings('carousel_tags')" :selected="$model->tags ?? []"/>
                <x-sap::date-field name="published_at" id="published_at" label="Published Date" :class="['']" :attribute_tags="[]" :value="$model->published_at ?? ''"/>
                <x-sap::date-field name="expired_at" id="expired_at" label="Expired Date" :class="['']" :attribute_tags="[]" :value="$model->expired_at ?? ''"/>
                <x-sap::select-field name="status" id="status" label="Status" :class="['']" :attribute_tags="[]" :data="['style'=>'border bg-white','live-search'=>false]" :options="settings('carousel_status')" :selected="$model->status ?? []"/>
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
