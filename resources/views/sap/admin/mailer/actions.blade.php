<div class="text-right text-nowrap">
    <a href="{{ route('mailer.show', $model->id) }}" class="btn btn-link text-secondary p-1" title="Read"><i class="fas fa-lg fa-eye"></i></a>
    @can('Preview Mailers')
    <a href="{{ route('mailer.preview', $model->id) }}" class="btn btn-link text-secondary p-1" title="Preview"><i class="fas fa-lg fa-laptop-code"></i></a>
    @endcan
    @can('Update Mailers')
    <a href="{{ route('mailer.edit', $model->id) }}" class="btn btn-link text-secondary p-1" title="Update"><i class="fas fa-lg fa-edit"></i></a>
    @endcan
    @can('Delete Mailers')
    <x-sap::form ajax="true" method="DELETE" action="{{ route('mailer.destroy', $model->id) }}" class="d-inline-block" confirm="You won't be able to revert this!">
        <button type="submit" class="btn btn-link text-secondary p-1" title="Delete">
        <i class="fas fa-lg fa-trash-alt"></i>
        </button>
    </x-sap::form>
    @endcan
</div>
