@extends('sap::layouts.app')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap pt-1 pb-2 mb-1 row">
        <div class="btn-toolbar col-md-10">
            <span class="h2">
                <span id="subTitle">Global Search</span>
            </span>
        </div>
    </div>
    <div class="card-columns">
    @forelse ($items as $item)
        <div class="card text-center">
            <div class="card-body">
                <h5 class="card-title">{{ basename(str_replace('\\', '/',$item->model)) }}</h5>
                <a href="{{ app($item->model)->find($item->model_id)->readUrl ?? '#' }}" class="btn btn-link stretched-link text-decoration-none text-left">
                <p class="card-text">
                    <ul class="list-unstyled">
                        @foreach ($item->tags as $key => $val)
                        <li>{{ ucwords($key) }} : {{ $val }}</li>
                        @endforeach
                    </ul>
                </p>
                </a>
                <p class="card-text"><small class="text-muted">{{ $item->updated_at ?? $item->created_at ?? '' }}</small></p>
            </div>
        </div>
    @empty
        <p>No search result</p>
    @endforelse
    </div>
    <div class="align-content-center">{{ $items->links() }}</div>
@endsection

@push('scripts')
@endpush
