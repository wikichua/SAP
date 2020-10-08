@extends('sap::layouts.app')
@section('content')
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="btn-toolbar justify-content-between" role="toolbar">
                <div class="btn-group" role="group">
                    <a href="javascript:void();" class="btn btn-link">
                        <i class="fas fa-angle-double-left mr-2"></i></a>
                    <h3 class="m-0 font-weight-bold text-primary">Page Listing</h3>
                </div>
                <div class="btn-group float-right" role="group" id="toolbar-primary">
                    <a class="btn btn-outline-secondary" href="{{ route('page.create') }}">
                        <i class="fas fa-folder-plus mr-2"></i>New
                    </a>
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-toggle="modal" data-target="#filterModalCenter">
                        <i class="fas fa-search mr-2"></i>Filter
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <x-sap::datatable :data="$html" :url="$getUrl"/>
            </div>
        </div>
    </div>
    @include('sap::admin.page.search')
@endsection
