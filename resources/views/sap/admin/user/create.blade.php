@extends('sap::layouts.app')
@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <div class="btn-toolbar justify-content-between" role="toolbar">
            <div class="btn-group" role="group">
                <a href="{{ route('user.list') }}" class="btn btn-link">
                    <i class="fas fa-angle-double-left mr-2"></i></a>
                    <h3 class="m-0 font-weight-bold text-primary">New User</h3>
                </div>
                <div class="btn-group" role="group">

                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <form novalidate data-ajax-form method="POST" action="{{ route('user.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="name">Full Name</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" required
                        name="name">
                        @error('name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" required
                        name="email">
                        @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password"
                        required name="password">
                        @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="password_confirmation">Confirm Password</label>
                        <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror"
                        id="password_confirmation" required name="password_confirmation">
                        @error('password_confirmation')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="user_type">Type</label>
                        <select name="user_type" id="user_type" class="selectpicker form-control @error('user_type') is-invalid @enderror" data-style="border bg-white" data-live-search="false">
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
                                <input type="checkbox" name="roles[]" id="role_{{ $role->id }}" class="custom-control-input" value="{{ $role->id }}">
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
    <script>
        $(function() {
            $(document).on('submit', 'form[data-ajax-form]', function(event) {
                event.preventDefault();
                let form = $(this);
                console.log(form);
                let action = form.attr('action');
                if (_.isUndefined(action) === false) {
                    let _method = form.find('input[name=_method]').val();
                    if (_.isUndefined(_method)) {
                        _method = form.attr('method');
                    }
                    axios.request({
                      method: _method,
                      url: action,
                      data: form,
                      data: bodyFormData,
                      headers: {
                        'Content-Type': 'multipart/form-data',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    onUploadProgress: function (progressEvent) {
                    // Do whatever you want with the native progress event
                },
            }).then((response) => {
                  // TODO
              }).catch((error) => {
                  console.error(error);
              }).finally(() => {
                  // TODO
              });
          }
      });
        });
    </script>
    @endpush
