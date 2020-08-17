@extends('sap::layouts.app')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap pt-1 pb-2 mb-1 row">
        <div class="btn-toolbar col-md-10">
            <span class="h2">
                <span id="subTitle">Elastic Search</span>
            </span>
        </div>
    </div>
    <div class="card-columns">
    @forelse ($items as $model => $item)
        @foreach ($item as $itm)
        <div class="card text-center">
            <div class="card-body">
                <h5 class="card-title">{{ $model }}</h5>
                <a href="{{ $itm->readUrl ?? '#' }}" class="btn btn-link stretched-link">
                <p class="card-text">{{ $itm->{$itm->esField} ?? $itm->name ?? '' }}</p>
                </a>
                <p class="card-text"><small class="text-muted">{{ $itm->updated_at ?? $itm->created_at ?? '' }}</small></p>
            </div>
        </div>
        @endforeach
    @empty
        {{-- empty expr --}}
    @endforelse
    </div>
@endsection

@push('scripts')
@endpush
