@extends('sap::layouts.app')
@section('content')
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="btn-toolbar justify-content-between" role="toolbar">
               <div class="btn-group" role="group">
                {{ \Breadcrumbs::render('breadcrumb') }}
            </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <x-sap::orderable-datatable :data="$html" :url="$getUrl" :actUrl="$actUrl" />
            </div>
        </div>
    </div>
@endsection
