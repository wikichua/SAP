<div class="text-right text-nowrap">
    <a href="{{ route('user.show', $model->id) }}" class="btn btn-link text-secondary p-1" title="Read"><i class="fas fa-lg fa-eye"></i></a>
    @can('Update Users')
        <a href="{{ route('user.update', $model->id) }}" class="btn btn-link text-secondary p-1" title="Update"><i class="fas fa-lg fa-edit"></i></a>
    @endcan
    @can('Update Users Password')
        <a href="{{ route('user.editPassword', $model->id) }}" class="btn btn-link text-secondary p-1" title="Change Password"><i class="fas fa-lg fa-unlock-alt"></i></a>
    @endcan
    @if ($model->id != auth()->user()->id)
        @can('Delete Users')
        <form method="POST" action="{{ route('user.destroy', $model->id) }}" class="d-inline-block" novalidate data-ajax-form>
            @csrf
            @method('DELETE')
            <button type="submit" name="_submit" class="btn btn-link text-secondary p-1" title="Delete" value="reload_datatables" data-confirm>
                <i class="fas fa-lg fa-trash-alt"></i>
            </button>
        </form>
        @endcan
    @endif
</div>
