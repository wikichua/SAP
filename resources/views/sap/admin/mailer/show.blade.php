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
            <x-sap::display-field name="mailable" id="mailable" label="Mailable" :value="$model->mailable" type="text"/>
            <x-sap::display-field name="subject" id="subject" label="Subject" :value="$model->subject ?? ''" type="text"/>
            <x-sap::display-field name="text_template" id="text_template" label="Plain Text" :value="$model->text_template ?? ''" type="text"/>
            <x-sap::display-field name="html_template" id="html_template" label="HTML" :value="$model->html_template ?? ''" type="editor"/>
            <x-sap::display-field type="text" name="created_at" id="created_at" label="Created At" :value="$model->created_at"/>
            <x-sap::display-field type="text" name="created_by" id="created_by" label="Created By" :value="$model->creator->name"/>
            <x-sap::display-field type="text" name="updated_at" id="updated_at" label="Updated At" :value="$model->updated_at"/>
            <x-sap::display-field type="text" name="updated_by" id="updated_by" label="Updated By" :value="$model->modifier->name"/>
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
