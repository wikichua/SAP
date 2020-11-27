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
                @can('Create Folder')
                <button type="button" class="btn btn-outline-secondary" id="createFolderBtn" data-href="" data-dirname="">
                    <i class="fas fa-folder-plus mr-2"></i>Create folder at <span class="onDirectory"></span>
                </button>
                @endcan
                @can('Upload Files')
                <button type="button" class="btn btn-outline-secondary" id="uploadFileBtn" data-toggle="modal" data-target="#uploadFileModal">
                    <i class="fas fa-folder-plus mr-2"></i>Upload file to <span class="onDirectory"></span>
                </button>
                @endcan
                {{-- <button type="button" class="btn btn-outline-secondary" data-toggle="modal" data-target="#filterModalCenter">
                <i class="fas fa-search mr-2"></i>Filter
                </button> --}}
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-3">
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
@can('Upload Files')
<div class="modal fade" id="uploadFileModal">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="uploadFileModalLabel">Upload File to <span class="onDirectory"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <x-sap::form ajax="true" method="POST" action="" id="uploadFileForm">
                <x-sap::file-field type="image" name="files" id="files" label="File" :class="['']" :attribute_tags="['multiple'=>'multiple']" value=""/>
                <button type="submit" class="btn btn-primary">Upload</button>
                </x-sap::form>
            </div>
        </div>
    </div>
</div>
@endcan
@endsection

@push('scripts')
<script id="directoriesTemplate" type="text/x-lodash-template">
<% _.forEach(data, function(item) { %>
{{-- <button data-href="<%- item.path %>" class="list-group-item list-group-item-action goToDirectory" data-title="<%- item.label %>"><%= item.title %></button> --}}
<%= item.view %>
<% }); %>
</script>
<script>
$(function() {
    $(document).on('click', '.goToDirectory', function(event) {
        event.preventDefault();
        let path = $(this).data('href');
        axios.post(route('folder.directories'),{
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
            $('#uploadFileForm').attr('action', route('file.upload',[path]));
            $('#createFolderBtn').data('href', route('folder.make',[path]));
        });
    });
    @if (isset($path) && $path != '')
    $('#goToPath').trigger('click');
    @else
    $('#goToTopDirectory').trigger('click');
    @endif
    $(document).on('click', '.duplicateBtn, .renameBtn', function(event) {
        event.preventDefault();
        let filename = $(this).data('filename');
        let href = $(this).data('href');
        let title = 'File duplicate as...';
        if ($(this).hasClass('renameBtn')) {
            title = 'File rename to...';
        }
        Swal.fire({
            title: title,
            input: 'text',
            inputValue: filename,
            inputAttributes: {
                autocapitalize: 'off'
            },
            showCancelButton: true,
            confirmButtonText: 'Save',
            showLoaderOnConfirm: true,
            preConfirm: (filename) => {
                return axios.put(href, {
                  name: filename,
                }).then((response) => {
                    let resp = response.data;
                    if (_.isUndefined(resp.status) == false && resp.status == 'success') {
                        if (_.isUndefined(resp.flash) == false && _.isString(resp.flash)) {
                            cookies.set('flash-status', resp.status);
                            cookies.set('flash-message', resp.flash);
                        }
                        if (_.isUndefined(resp.relist) == false && resp.relist) {
                            flashMessage();
                            loadDatatable(currentUrl);
                        }
                    }
                }).catch((error) => {
                    let resp = error.response.data;
                    if (_.isUndefined(resp.message) == false && resp.message) {
                        Toast.fire({
                            icon: 'error',
                            title: resp.message
                        });
                    }
                }).finally(() => {
                  // TODO
                });
            },
            allowOutsideClick: () => !Swal.isLoading()
        });
    });
    $(document).on('click', '.deleteFolderBtn, .renameFolderBtn, .copyFolderBtn, #createFolderBtn', function(event) {
        event.preventDefault();
        let dirname = $(this).data('dirname');
        let href = $(this).data('href');
        let title = 'Create New Folder as...';
        if ($(this).hasClass('renameFolderBtn')) {
            title = 'Folder rename to...';
        } else if ($(this).hasClass('copyFolderBtn')) {
            title = 'Folder duplicate to...';
        }

        if ($(this).hasClass('deleteFolderBtn')) {
            Swal.fire({
                title: 'Are you sure to delete this folder?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.delete(href).then((response) => {
                        let resp = response.data;
                        if (_.isUndefined(resp.status) == false && resp.status == 'success') {
                            if (_.isUndefined(resp.flash) == false && _.isString(resp.flash)) {
                                cookies.set('flash-status', resp.status);
                                cookies.set('flash-message', resp.flash);
                            }
                            if (_.isUndefined(resp.relist) == false && resp.relist) {
                                flashMessage();
                                $('.goToDirectory').eq(3).trigger('click');
                            }
                        }
                    }).catch((error) => {
                        let resp = error.response.data;
                        if (_.isUndefined(resp.message) == false && resp.message) {
                            Toast.fire({
                                icon: 'error',
                                title: resp.message
                            });
                        }
                    }).finally(() => {
                      // TODO
                    });
                }
            })
        } else {
            Swal.fire({
                title: title,
                input: 'text',
                inputValue: dirname,
                inputAttributes: {
                    autocapitalize: 'off'
                },
                showCancelButton: true,
                confirmButtonText: 'Save',
                showLoaderOnConfirm: true,
                preConfirm: (dirname) => {
                    return axios.put(href, {
                      name: dirname,
                    }).then((response) => {
                        let resp = response.data;
                        if (_.isUndefined(resp.status) == false && resp.status == 'success') {
                            if (_.isUndefined(resp.flash) == false && _.isString(resp.flash)) {
                                cookies.set('flash-status', resp.status);
                                cookies.set('flash-message', resp.flash);
                            }
                            if (_.isUndefined(resp.relist) == false && resp.relist) {
                                flashMessage();
                                $('.goToDirectory').eq(3).trigger('click');
                            }
                        }
                    }).catch((error) => {
                        let resp = error.response.data;
                        if (_.isUndefined(resp.message) == false && resp.message) {
                            Toast.fire({
                                icon: 'error',
                                title: resp.message
                            });
                        }
                    }).finally(() => {
                      // TODO
                    });
                },
                allowOutsideClick: () => !Swal.isLoading()
            });
        }
    });
});
</script>
@endpush
