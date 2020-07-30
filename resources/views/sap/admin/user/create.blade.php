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
                <x-sap-input-field type="text" name="name" id="name" label="Full Name" :class="[]"/>
                <x-sap-input-field type="email" name="email" id="email" label="Email" :class="[]"/>
                <x-sap-input-field type="password" name="password" id="password" label="Password" :class="[]"/>
                <x-sap-input-field type="password" name="password_confirmation" id="password_confirmation" label="Confirm Password" :class="[]"/>
                <x-sap-select-field name="type" id="type" label="Type" :class="[]" :data="['style'=>'border bg-white','live-search'=>false]" :options="settings('user_types')"/>
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
        console.log(form[0]);
        let action = form.attr('action');
        if (_.isUndefined(action) === false) {
            let _method = form.find('input[name=_method]').val();
            if (_.isUndefined(_method)) {
                _method = form.attr('method');
            }
            axios.request({
				method: _method,
				url: action,
				data: new FormData(form[0]),
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
