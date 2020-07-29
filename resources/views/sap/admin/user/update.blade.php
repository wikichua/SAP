@extends('sap::layouts.app')
@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <div class="btn-toolbar justify-content-between" role="toolbar">
            <div class="btn-group" role="group">
                <a href="{{ route('user') }}" class="btn btn-link">
                    <i class="fas fa-angle-double-left mr-2"></i></a>
                    <h3 class="m-0 font-weight-bold text-primary">New User</h3>
                </div>
                <div class="btn-group" role="group">

                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <form novalidate data-ajax-form method="POST" action="{{ route('user.update', [$model->id]) }}" enctype="multipart/form-data">
                    @method('patch')
                    @csrf
                    <div class="form-group">
                        <label for="name">Full Name</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" required
                        name="name" value="{{ $model->name }}">
                        @error('name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" required
                        name="email" value="{{ $model->email }}">
                        @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="user_type">Type</label>
                        <select name="user_type" id="user_type" class="selectpicker form-control @error('user_type') is-invalid @enderror"
                        data-style="border bg-white" data-live-search="false">
                        <option value=""></option>
                        @foreach(settings('user_types') as $key => $val)
                        <option value="{{ $key }}">{{ $val }}</option>
                        @endforeach
                    </select>
                    @error('user_type')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label>Roles</label>
                    <div class="form-control-plaintext">
                        @foreach($roles as $role)
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" name="roles[]" id="role_{{ $role->id }}"
                            class="custom-control-input" value="{{ $role->id }}"
                            {{ $model->roles->contains('id', $role->id) ? ' checked' : '' }}>
                            <label for="role_{{ $role->id }}" class="custom-control-label">{{ $role->name }}</label>
                        </div>
                        @endforeach
                    </div>
                    <div>
                        @error('roles') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>
                <button type="submit" class="btn btn-primary btn-user">
                    Submit
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
@push('scripts')
@endpush
