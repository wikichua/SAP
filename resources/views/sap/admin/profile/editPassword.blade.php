@extends('sap::layouts.app')
@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <div class="btn-toolbar justify-content-between" role="toolbar">
            <div class="btn-group" role="group">
                <h3 class="m-0 font-weight-bold text-primary">Change Password</h3>
            </div>
            <div class="btn-group" role="group">
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <form novalidate data-ajax-form method="POST" action="{{ route('profile.updatePassword') }}" enctype="multipart/form-data">
                @csrf
                @method('PATCH')
                <x-sap-input-field type="password" name="current_password" id="current_password" label="Current Password" :class="[]"/>
                <x-sap-input-field type="password" name="password" id="password" label="Password" :class="[]"/>
                <x-sap-input-field type="password" name="password_confirmation" id="password_confirmation" label="Confirm Password" :class="[]"/>
                <button type="submit" class="btn btn-primary">
                Submit
                </button>
            </form>
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
