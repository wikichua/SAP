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
            <x-sap::form ajax="true" method="PATCH" action="{{ route('setting.update',[$model->id]) }}">
                <x-sap::input-field type="text" name="key" id="key" label="Key" :class="[]" :value="$model->key" />
                <x-sap::checkbox-field name="protected" id="protected" label="Protected" :class="[]" :value="1" :checked="$model->protected ?? 0" subLabel="Apply Encryption" />
                @include('sap::admin.setting.valueInput')
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
