<li class="nav-item dropdown no-arrow mx-1">
    <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-bell fa-fw"></i>
        <!-- Counter - Alerts -->
        <span class="badge badge-danger badge-counter">{{ $unread_count }}</span>
    </a>
    <!-- Dropdown - Alerts -->
    <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="alertsDropdown">
        <h6 class="dropdown-header">
            Alerts Center
        </h6>
        @forelse ($alerts as $alert)
        <button type="button" class="btn-link dropdown-item d-flex align-items-center alert-link" data-id="{{ $alert->id }}">
            <div class="mr-3">
                <div class="icon-circle bg-primary">
                    <i class="{{ $alert->icon ?? 'fas fa-bell' }} text-white"></i>
                </div>
            </div>
            <div>
                <div class="small text-gray-500">{{ \Carbon\Carbon::parse(strtotime($alert->created_at))->diffForHumans() }} ({{ \Carbon\Carbon::parse($alert->created_at)->toFormattedDateString() }})</div>
                <span class="font-weight-bold">{!! $alert->message !!}</span>
            </div>
        </button>
        @empty
            {{-- empty expr --}}
        @endforelse

    </div>
</li>

@push('scripts')
<script>
$(function() {
    $(document).on('click', '.alert-link', function(event) {
        event.preventDefault();
        axios.put(route('alert.set.read',[$(this).data('id')]))
        .then((response) => {
            if(response.data != '')
            {
                window.location = response.data;
            }
        }).catch((error) => {
          console.error(error);
        }).finally(() => {
          // TODO
        });
    });
});
</script>
@endpush
