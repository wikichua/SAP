@extends('sap::layouts.app')
@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <div class="btn-toolbar justify-content-between" role="toolbar">
            <div class="btn-group" role="group">
                <a href="{{ route('report.list') }}" class="btn btn-link">
                <i class="fas fa-angle-double-left mr-2"></i></a>
                <h3 class="m-0 font-weight-bold text-primary">{{ $model->name }} Report</h3>
            </div>
            <div class="btn-group" role="group">
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <nav>
                <div class="nav nav-tabs nav-fills" id="nav-tab" role="tablist">
                    @foreach ($models as $model)
                    <a class="nav-link {{ $loop->iteration == 1? 'active':'' }}" id="nav-sql-{{ $loop->iteration }}-tab" data-toggle="tab" href="#nav-sql-{{ $loop->iteration }}" role="tab" aria-controls="nav-sql-{{ $loop->iteration }}" aria-selected="false">SQL {{ $loop->iteration }}</a>
                    @endforeach
                </div>
            </nav>
            <div class="tab-content">
                @foreach ($models as $model)
                <div class="tab-pane fade {{ $loop->iteration == 1? 'show active':'' }}" id="nav-sql-{{ $loop->iteration }}" role="tabpanel" aria-labelledby="nav-sql-{{ $loop->iteration }}-tab">
                    <x-sap-report-table :data="$model"/>
                </div>
                @endforeach
            </div>
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
