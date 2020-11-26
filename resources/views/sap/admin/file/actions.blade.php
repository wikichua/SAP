@can('Rename Files')
    <a href="{{ route('file.rename', $filepath) }}" class="btn btn-link text-secondary p-1" title="Rename"><i class="fas fa-lg fa-edit"></i></a>
@endcan
@can('Copy Files')
    <a href="{{ route('file.duplicate', $filepath) }}" class="btn btn-link text-secondary p-1" title="Duplicate"><i class="fas fa-lg fa-clone"></i></a>
@endcan
@can('Delete Files')
<x-sap::form ajax="true" method="DELETE" action="{{ route('file.destroy', $filepath) }}" class="d-inline-block" confirm="You won't be able to revert this!">
    <button type="submit" class="btn btn-link text-secondary p-1" title="Delete">
        <i class="fas fa-lg fa-trash-alt"></i>
    </button>
</x-sap::form>
@endcan
