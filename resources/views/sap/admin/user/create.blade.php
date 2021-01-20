@extends('sap::layouts.app')
@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <div class="btn-toolbar justify-content-between" role="toolbar">
            <div class="btn-group" role="group">
                <a href="{{ route('user.list') }}" class="btn btn-link">
                <i class="fas fa-angle-double-left mr-2"></i></a>
                <h3 class="m-0 font-weight-bold text-primary">New User</h3>
            </div>
            <div class="btn-group" role="group">
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <x-sap::form ajax="true" method="POST" action="{{ route('user.store') }}">
                <x-sap::select-field name="brand_id" id="brand_id" label="Brand" :class="['']" :attribute_tags="[]" :data="['style'=>'border bg-white','live-search'=>false]" :options="app(config('sap.models.brand'))->query()->pluck('name','id')->toArray()" :selected="$model->brand_id ?? []"/>
                <x-sap::input-field type="text" name="name" id="name" label="Full Name" :class="[]" value=""/>
                <x-sap::input-field type="email" name="email" id="email" label="Email" :class="[]" value=""/>
                <x-sap::input-field type="password" name="password" id="password" label="Password" :class="[]"/>
                <x-sap::input-field type="password" name="password_confirmation" id="password_confirmation" label="Confirm Password" :class="[]"/>
                <x-sap::select-field name="timezone" id="timezone" label="Timezone" :class="[]" :data="['style'=>'border bg-white','live-search'=>true]" :options="Help::timezones()" :selected="[]"/>
                <x-sap::select-field name="type" id="type" label="Type" :class="[]" :data="['style'=>'border bg-white','live-search'=>false]" :options="settings('user_types')" :selected="[]"/>
                <x-sap::checkboxes-field name="roles" id="roles" label="Roles" :class="[]" :options="$roles->toArray()" :isGroup="false"/>
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
