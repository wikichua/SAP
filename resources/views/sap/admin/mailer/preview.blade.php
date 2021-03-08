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
        <div class="table">
            <div class="row">
                <div class="col">
                    <x-sap::form ajax="true" method="POST" action="{{ route('mailer.preview',[$model->id]) }}" id="preview-form">
                        @foreach ($params as $param)
                        <x-sap::textarea-field type="text" name="{{ $param }}" id="{{ $param }}" label="{{ $param }}" :class="[]" :value="request($param,'')"/>
                        @endforeach
                        <button type="submit" class="btn btn-primary">
                        Preview
                        </button>
                    </x-sap::form>
                </div>
                <div class="col">
                    <h6>Preview</h6>
                    <div id="preview-div"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
$(document).ready(function() {
    $(document).on('submit', '#preview-form', function(event) {
        event.preventDefault();
        let form = $(this);
        let action = form.attr('action');
        axios.post(action, new FormData(form[0])).then((response) => {
            $('#preview-div').html(response.data);
        });
    });
    $('#preview-form').trigger('submit');
});
</script>
@endpush
