<div class="text-right text-nowrap">
    <a href="{{ route('user.show', $model->id) }}" class="btn btn-link text-secondary p-1" title="Read"><i class="fas fa-lg fa-eye"></i></a>
    @can('Update Users')
        <a href="{{ route('user.edit', $model->id) }}" class="btn btn-link text-secondary p-1" title="Update"><i class="fas fa-lg fa-edit"></i></a>
    @endcan
    @can('Update Users Password')
        <a href="{{ route('user.editPassword', $model->id) }}" class="btn btn-link text-secondary p-1" title="Change Password"><i class="fas fa-lg fa-unlock-alt"></i></a>
    @endcan
    @if ($model->id != auth()->user()->id)
        @can('Delete Users')
        <x-sap::form ajax="true" method="DELETE" action="{{ route('user.destroy', $model->id) }}" class="d-inline-block" confirm="You won't be able to revert this!">
            <button type="submit" class="btn btn-link text-secondary p-1" title="Delete">
                <i class="fas fa-lg fa-trash-alt"></i>
            </button>
        </x-sap::form>
        @endcan

        @can('Impersonate Users')
            @if ($model->hasPermission('Access Admin Panel',$model->id))
        <a href="{{ route('impersonate', $model->id) }}" class="btn btn-link text-secondary p-1" title="Read"><i class="fas fa-lg fa-people-arrows"></i></a>
            @endif
        @endcan
    @endif

    @can('Read Personal Access Token')
        <a href="{{ route('pat.list', $model->id) }}" class="btn btn-link text-secondary p-1" title="Personal Access Token"><i class="fas fa-lg fa-fingerprint"></i></a>
    @endcan
</div>
