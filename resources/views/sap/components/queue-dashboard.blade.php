<h3>Queues / Jobs</h3>
<div class="card-deck">
    <span class="text-muted">{{ $msg ?? '' }}</span>
    @foreach ($queues as $key => $count)
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">{{ $key }}</h5>
            <p class="card-text">{{ $count }}</p>
        </div>
    </div>
    @endforeach
</div>
