@extends('sap::layouts.app')
@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <div class="btn-toolbar justify-content-between" role="toolbar">
            <div class="btn-group" role="group">
                <a href="{{ route('permission.list') }}" class="btn btn-link">
                <i class="fas fa-angle-double-left mr-2"></i></a>
                <h3 class="m-0 font-weight-bold text-primary">Show Component</h3>
            </div>
            <div class="btn-group" role="group">
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="table">
            <div class="row">
                <div class="col">
                    <form novalidate method="POST" action="{{ route('component.try',[$model->id]) }}" id="component-try-form">
                        @csrf
                        <x-sap::textarea-field name="code" id="code" label="Try It" :class="['']" :attribute_tags="[]" value="<x-{{ strtolower($model->brand->name) }}::{{ \Str::kebab($model->name) }}></x-{{ strtolower($model->brand->name) }}::{{ \Str::kebab($model->name) }}>"/>
                        <button type="submit" class="btn btn-primary" id="submit-btn">Submit</button>
                    </form>
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
    $(document).on('submit', '#component-try-form', function(event) {
        event.preventDefault();
        let form = $(this);
        let action = form.attr('action');
        axios.post(action, new FormData(form[0])).then((response) => {
            $('#preview-div').html(response.data);
        });
    });
    $('#component-try-form').trigger('submit');
});
</script>
@endpush
