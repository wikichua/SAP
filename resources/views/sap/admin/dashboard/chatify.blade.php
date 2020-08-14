@extends('sap::layouts.app')

@section('content')
    <div class="embed-responsive embed-responsive-16by9">
        <iframe src="{{ route('chatify') }}" class="embed-responsive-item"></iframe>
    </div>
@endsection

@push('scripts')
<script>
    $(function() {

    });
</script>
@endpush
