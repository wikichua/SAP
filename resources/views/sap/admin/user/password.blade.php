<div id='componentId'>
    <form wire:submit.prevent="updatePassword">
        @method('PATCH')
        @csrf
        <div class="list-group shadow">
            <div class="list-group-item">
                <div class="form-group row mb-0">
                    <label for="password" class="col-md-3 col-form-label">Password</label>
                    <div class="col-md-9">
                        <input type="password" name="password" name="password" id="password" class="form-control">
                        <div>
                            @error('password') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="list-group-item">
                <div class="form-group row mb-0">
                    <label for="password_confirmation" class="col-md-3 col-form-label">Confirm Password</label>
                    <div class="col-md-9">
                        <input type="password" name="password_confirmation" name="password_confirmation" id="password_confirmation" class="form-control">
                        <div>
                            @error('password_confirmation') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>
            </div>
            <div class="list-group-item bg-light text-left text-md-right pb-1">
                <button type="button" class="btn btn-danger mb-2 float-left" wire:click="back()">Back</button>
                <button type="submit" class="btn btn-primary mb-2">Save</button>
            </div>
        </div>
    </form>
</div>