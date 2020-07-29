<div>
	<div class="list-group shadow">
	    <div class="list-group-item">
	        <div class="form-group row mb-0">
	            <label class="col-md-3 col-form-label">ID</label>
	            <div class="col-md-9">
	                <div class="form-control-plaintext" wire:model="modelId">{{ $modelId }}</div>
	            </div>
	        </div>
	    </div>
	    <div class="list-group-item">
	        <div class="form-group row mb-0">
	            <label class="col-md-3 col-form-label">Name</label>
	            <div class="col-md-9">
	                <div class="form-control-plaintext" wire:model="name">{{ $name }}</div>
	            </div>
	        </div>
	    </div>
	    <div class="list-group-item bg-light text-left text-md-right pb-1">
	    	<button type="button" class="btn btn-danger mb-2 float-left" wire:click="back()">Back</button>
	    </div>
	</div>
</div>