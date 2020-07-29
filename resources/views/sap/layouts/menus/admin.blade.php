<li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true"
        aria-controls="collapseTwo">
        <i class="fas fa-fw fa-cog"></i>
        <span>Administrative</span>
    </a>
    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
            {{-- <h6 class="collapse-header">Custom Components:</h6> --}}
            <a class="collapse-item" href="{{ route('user.list') }}">User</a>
            {{-- <a class="collapse-item" href="{{ route('role') }}">Role</a>
            <a class="collapse-item" href="{{ route('permission') }}">Permission</a>
            <a class="collapse-item" href="{{ route('setting') }}">Setting</a> --}}
        </div>
    </div>
</li>
