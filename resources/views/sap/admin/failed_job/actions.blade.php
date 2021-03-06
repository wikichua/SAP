<div class="text-right text-nowrap">
    <a href="{{ route('failed_job.show', $model->id) }}" class="btn btn-link text-secondary p-1" title="Read"><i class="fas fa-lg fa-eye"></i></a>
    @can('Retry Failed Jobs')
    <x-sap::form ajax="true" method="POST" action="{{ route('failed_job.retry', $model->id) }}" class="d-inline-block"confirm="Are you sure you want to retry this queue?">
        <button type="submit" class="btn btn-link text-secondary p-1" title="Retry">
            <i class="fas fa-lg fa-redo-alt"></i>
        </button>
    </x-sap::form>
    @endcan
</div>
