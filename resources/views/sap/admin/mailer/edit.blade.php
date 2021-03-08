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
            <x-sap::form ajax="true" method="PATCH" action="{{ route('mailer.update',[$model->id]) }}">
                <x-sap::textarea-field type="text" name="subject" id="subject" label="Subject" :class="[]" :value="$model->subject ?? ''"/>
                <x-sap::textarea-field type="text" name="text_template" id="text_template" label="Plain Text Template" :class="[]" :value="$model->text_template ?? ''"/>
                <x-sap::editor-field type="text" name="html_template" id="html_template" label="HTML Template" :class="[]" :value="$model->html_template ?? ''"/>
                <button type="submit" class="btn btn-primary">
                Save & Show
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
