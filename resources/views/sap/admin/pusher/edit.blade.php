@extends('sap::layouts.app')
@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <div class="btn-toolbar justify-content-between" role="toolbar">
            <div class="btn-group" role="group">
                <a href="{{ route('pusher.list') }}" class="btn btn-link">
                <i class="fas fa-angle-double-left mr-2"></i></a>
                <h3 class="m-0 font-weight-bold text-primary">Edit Pusher</h3>
            </div>
            <div class="btn-group" role="group">
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <x-sap::form ajax="true" method="PATCH" action="{{ route('pusher.update',[$model->id]) }}">
                <x-sap::select-field name="brand_id" id="brand_id" label="Brand" :class="['']" :attribute_tags="[]" :data="['style'=>'border bg-white','live-search'=>false]" :options="app(config('sap.models.brand'))->query()->pluck('name','id')->toArray()" :selected="$model->brand_id ?? []"/>
                <x-sap::select-field name="locale" id="locale" label="Locale" :class="['']" :attribute_tags="[]" :data="['style'=>'border bg-white','live-search'=>false]" :options="settings('locales')" :selected="$model->locale ?? []"/>
                <x-sap::input-field type="text" name="title" id="title" label="Title" :class="[]" value="{{ $model->title ?? '' }}"/>
                <x-sap::textarea-field name="message" id="message" label="Message" :class="[]" value="{{ $model->message ?? '' }}"/>
                <x-sap::image-field name="icon" id="icon" label="Icon" :class="['']" :attribute_tags="[]" :value="$model->icon ?? ''"/>
                <x-sap::input-field  type="text" name="link" id="link" label="Link" :class="[]" value="{{ $model->link ?? '' }}"/>
                <x-sap::input-field  type="number" name="timeout" id="timeout" label="Time Out (ms)" :class="[]" value="{{ $model->timeout ?? '5000' }}"/>
                <x-sap::datetime-field name="scheduled_at" id="scheduled_at" label="Scheduled Date" :class="['']" :attribute_tags="[]" :value="$model->scheduled_at ?? ''"/>
                <x-sap::select-field name="event" id="event" label="Event" :class="['']" :attribute_tags="[]" :data="['style'=>'border bg-white','live-search'=>false]" :options="settings('pusher_events')" :selected="$model->event ?? []"/>
                <x-sap::select-field name="status" id="status" label="Status" :class="['']" :attribute_tags="[]" :data="['style'=>'border bg-white','live-search'=>false]" :options="settings('pusher_status')" :selected="$model->status ?? []"/>
                <button type="submit" class="btn btn-primary">Submit</button>
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
