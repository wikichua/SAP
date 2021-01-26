<div class="text-right text-nowrap">
    <a href="{{ route('pusher.show', $model->id) }}" class="btn btn-link text-secondary p-1" title="Read"><i class="fas fa-lg fa-eye"></i></a>
    @can('Push Pushers')
        <x-sap::form ajax="true" method="POST" action="{{ route('pusher.push', $model->id) }}" class="d-inline-block" confirm="You are going to push the message to the world!">
            <button type="submit" class="btn btn-link text-secondary p-1" title="Push Message">
                <i class="fas fa-lg fa-paper-plane"></i>
            </button>
        </x-sap::form>
    @endcan
    @can('Update Pushers')
        <a href="{{ route('pusher.edit', $model->id) }}" class="btn btn-link text-secondary p-1" title="Update"><i class="fas fa-lg fa-edit"></i></a>
    @endcan
    @can('Delete Pushers')
    <x-sap::form ajax="true" method="DELETE" action="{{ route('pusher.destroy', $model->id) }}" class="d-inline-block" confirm="You won't be able to revert this!">
        <button type="submit" class="btn btn-link text-secondary p-1" title="Delete">
            <i class="fas fa-lg fa-trash-alt"></i>
        </button>
    </x-sap::form>
    @endcan
</div>
