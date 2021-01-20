<div class="text-right text-nowrap">
    <a href="{{ route('nav.show', $model->id) }}" class="btn btn-link text-secondary p-1" title="Read"><i class="fas fa-lg fa-eye"></i></a>
    <a href="{{ route('nav.orderable',$model->group_slug) }}" class="btn btn-link text-secondary p-1" title="Reorder List"><i class="fas fa-sort-numeric-up"></i></a>
    @can('Update Permissions')
        <a href="{{ route('nav.edit', $model->id) }}" class="btn btn-link text-secondary p-1" title="Update"><i class="fas fa-lg fa-edit"></i></a>
    @endcan
    @can('Delete Permissions')
    <x-sap::form ajax="true" method="POST" action="{{ route('nav.replicate', $model->id) }}" class="d-inline-block" confirm="Please confirm to replicate this page!">
        <button type="submit" class="btn btn-link text-secondary p-1" title="Replicate">
            <i class="fas fa-lg fa-clone"></i>
        </button>
    </x-sap::form>
    @endcan
    @can('Delete Permissions')
    <x-sap::form ajax="true" method="DELETE" action="{{ route('nav.destroy', $model->id) }}" class="d-inline-block" confirm="You won't be able to revert this!">
        <button type="submit" class="btn btn-link text-secondary p-1" title="Delete">
            <i class="fas fa-lg fa-trash-alt"></i>
        </button>
    </x-sap::form>
    @endcan
</div>
