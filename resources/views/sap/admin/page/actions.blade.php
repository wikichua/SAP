<div class="text-right text-nowrap">
    <a href="{{ route('page.show', $model->id) }}" class="btn btn-link text-secondary p-1" title="Read"><i class="fas fa-lg fa-eye"></i></a>
    @can('Update Permissions')
        <a href="{{ route('page.edit', $model->id) }}" class="btn btn-link text-secondary p-1" title="Update"><i class="fas fa-lg fa-edit"></i></a>
    @endcan
    @can('Delete Permissions')
    <x-sap::form ajax="true" method="POST" action="{{ route('page.replicate', $model->id) }}" class="d-inline-block" confirm="Please confirm to replicate this page!">
        <button type="submit" class="btn btn-link text-secondary p-1" title="Replicate">
            <i class="fas fa-lg fa-clone"></i>
        </button>
    </x-sap::form>
    @endcan
    @can('Delete Permissions')
    <x-sap::form ajax="true" method="DELETE" action="{{ route('page.destroy', $model->id) }}" class="d-inline-block" confirm="You won't be able to revert this!">
        <button type="submit" class="btn btn-link text-secondary p-1" title="Delete">
            <i class="fas fa-lg fa-trash-alt"></i>
        </button>
    </x-sap::form>
    @endcan
</div>
