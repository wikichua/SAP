<li class="list-group-item">{!! $data['title'] !!}
    <div class="float-right">
        <button data-href="{{ $data['path'] }}" class="btn btn-outline-primary btn-sm badge badge-pill goToDirectory" data-title="{{ $data['label'] }}" title="Open Folder">
        <i class="fas fa-folder-open"></i>
        </button>
        @can('Rename Folders')
        <button data-href="{{ route('folder.change', $data['path']) }}" data-dirname="{{ $data['dirname'] }}" class="btn btn-outline-primary btn-sm badge badge-pill renameFolderBtn" data-title="{{ $data['label'] }}" title="Rename Folder">
        <i class="fas fa-edit"></i>
        </button>
        @endcan
        @can('copy Folders')
        <button data-href="{{ route('folder.clone', $data['path']) }}" data-dirname="{{ $data['dirname'] }}" class="btn btn-outline-primary btn-sm badge badge-pill copyFolderBtn" data-title="{{ $data['label'] }}" title="Copy Folder">
        <i class="fas fa-clone"></i>
        </button>
        @endcan
        @can('Delete Folders')
        <button data-href="{{ route('folder.remove', $data['path']) }}" data-dirname="{{ $data['dirname'] }}" class="btn btn-outline-primary btn-sm badge badge-pill deleteFolderBtn" data-title="{{ $data['label'] }}" title="Delete Folder">
        <i class="fas fa-trash"></i>
        </button>
        @endcan
    </div>
</li>
