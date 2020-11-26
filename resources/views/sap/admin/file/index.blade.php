@extends('sap::layouts.app')
@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <div class="btn-toolbar justify-content-between" role="toolbar">
            <div class="btn-group" role="group">
                <a href="javascript:void();" class="btn btn-link">
                <i class="fas fa-angle-double-left mr-2"></i></a>
                <h3 class="m-0 font-weight-bold text-primary">Files Listing</h3>
            </div>
            <div class="btn-group float-right" role="group" id="toolbar-primary">
                @can('Upload Files')
                <a class="btn btn-outline-secondary" href="{{ route('file.upload') }}" id="uploadFileLink">
                    <i class="fas fa-folder-plus mr-2"></i>Upload file to <span class="onDirectory"></span>
                </a>
                @endcan
                {{-- <button type="button" class="btn btn-outline-secondary" data-toggle="modal" data-target="#filterModalCenter">
                <i class="fas fa-search mr-2"></i>Filter
                </button> --}}
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-2">
                <div class="list-group">
                    <button data-href="{{ $path ?? '' }}" class="goToDirectory invisible" id="goToPath" data-title="Top"></button>
                    <button data-href="" class="list-group-item list-group-item-action goToDirectory" id="goToTopDirectory" data-title="Top">Top</button>
                    <div id="directoriesContent"></div>
                </div>
            </div>
            <div class="col">
                <div class="table-responsive">
                    <strong>Files in <span class="onDirectory"></span> directory.</strong>
                    <x-sap::datatable :data="$html" :url="$getUrl"/>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script id="directoriesTemplate" type="text/x-lodash-template">
<% _.forEach(data, function(item) { %>
<button data-href="<%- item.path %>" class="list-group-item list-group-item-action goToDirectory" data-title="<%- item.label %>"><%= item.title %></button>
<% }); %>
</script>
<script>
$(function() {
    $(document).on('dblclick', '.goToDirectory', function(event) {
        event.preventDefault();
        let path = $(this).data('href');
        axios.post(route('file.directories'),{
            path: path
        }).then((response) => {
            var templateFn = _.template($('#directoriesTemplate').html());
            var templateHTML = templateFn(response);
            $('#directoriesContent').html(templateHTML);
        }).catch((error) => {
          console.error(error);
        }).finally(() => {
            let params = {
                path: path
            };
            loadDatatable(url, params);
            let onDirectory = $(this).data('title');
            $('.onDirectory').html(onDirectory);
            $('#uploadFileLink').attr('href', route('file.upload',[path]));
        });
    });
    @if (isset($path) && $path != '')
    $('#goToPath').trigger('dblclick');
    @else
    $('#goToTopDirectory').trigger('dblclick');
    @endif
});
</script>
@endpush
