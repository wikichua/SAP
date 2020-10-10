@if (!$hasLogin)
<li class="nav-item">
    <button type="button" class="btn btn-link nav-link" data-toggle="modal" data-target="#elegantModalForm">
    <i class="fas fa-sign-in-alt mr-1"></i> Sign In
    </button>
</li>
@else
<li class="nav-item">
    <a href="#" class="btn btn-link nav-link">
    Hi, {{ auth('brand_web')->user()->name }}
    </a>
</li>
<li class="nav-item">
    <a href="{{ route('{%brand_string%}.logout') }}" class="btn btn-link nav-link">
        <i class="fas fa-sign-out-alt mr-1"></i> Sign Out
    </a>
</li>
@endif
