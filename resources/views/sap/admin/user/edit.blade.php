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
            <x-sap::form ajax="true" method="PATCH" action="{{ route('user.update',[$model->id]) }}">
                <x-sap::select-field name="brand_id" id="brand_id" label="Brand" :class="['']" :attribute_tags="[]" :data="['style'=>'border bg-white','live-search'=>false]" :options="app(config('sap.models.brand'))->query()->pluck('name','id')->toArray()" :selected="$model->brand_id ?? []"/>
                <x-sap::input-field type="text" name="name" id="name" label="Full Name" :class="[]" :value="$model->name"/>
                <x-sap::input-field type="email" name="email" id="email" label="Email" :class="[]" :value="$model->email"/>
                <x-sap::select-field name="timezone" id="timezone" label="Timezone" :class="[]" :data="['style'=>'border bg-white','live-search'=>true]" :options="Help::timezones()" :selected="$model->timezone ?? []"/>
                <x-sap::select-field name="type" id="type" label="Type" :class="[]" :data="['style'=>'border bg-white','live-search'=>false]" :options="settings('user_types')" :selected="$model->type"/>
                <x-sap::checkboxes-field name="roles" id="roles" label="Roles" :class="[]" :options="$roles->toArray()" :checked="$model->roles()->pluck('role_id')->toArray()" :isGroup="false"/>
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
