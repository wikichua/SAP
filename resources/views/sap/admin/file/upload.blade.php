@extends('sap::layouts.app')
@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <div class="btn-toolbar justify-content-between" role="toolbar">
            <div class="btn-group" role="group">
                <a href="{{ route('file.list',[$path]) }}" class="btn btn-link">
                <i class="fas fa-angle-double-left mr-2"></i></a>
                <h3 class="m-0 font-weight-bold text-primary">Upload file to {{ basename(str_replace(':', '/', $path)) }} directory</h3>
            </div>
            <div class="btn-group" role="group">
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <x-sap::form ajax="true" method="POST" action="{{ route('file.store',[$path]) }}">
                <x-sap::file-field type="image" name="files" id="files" label="File" :class="['']" :attribute_tags="['multiple'=>'multiple']" value=""/>
                <button type="submit" class="btn btn-primary">
                Upload
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
