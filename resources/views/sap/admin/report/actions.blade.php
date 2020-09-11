<div class="text-right text-nowrap">
    <a href="{{ route('report.show', $model->id) }}" class="btn btn-link text-secondary p-1" title="Read"><i class="fas fa-lg fa-eye"></i></a>
    @can('Update Permissions')
        <a href="{{ route('report.edit', $model->id) }}" class="btn btn-link text-secondary p-1" title="Update"><i class="fas fa-lg fa-edit"></i></a>
    @endcan
    @can('Delete Permissions')
    <form method="POST" action="{{ route('report.destroy', $model->id) }}" class="d-inline-block" novalidate data-ajax-form data-confirm="You won't be able to revert this!">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-link text-secondary p-1" title="Delete">
            <i class="fas fa-lg fa-trash-alt"></i>
        </button>
    </form>
    @endcan
</div>
