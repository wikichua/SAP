<!-- Nav Item - User Information -->
<li class="nav-item dropdown no-arrow">
    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <span
            class="mr-2 d-none d-lg-inline text-gray-600 small">{{ auth()->user()->name }}</span>
        <img class="img-profile rounded-circle" src="{{ auth()->user()->avatar ?? 'https://source.unsplash.com/60x60/?celebrity,human,animal' }}">
    </a>
    <!-- Dropdown - User Information -->
    <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
        aria-labelledby="userDropdown">
        <a class="dropdown-item" href="{{ route('profile.show') }}">
            <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
            My Profile
        </a>
        <a class="dropdown-item" href="{{ route('profile.edit') }}">
            <i class="fas fa-id-badge fa-sm fa-fw mr-2 text-gray-400"></i>
            Update Profile
        </a>
        <a class="dropdown-item" href="{{ route('profile.editPassword') }}">
            <i class="fas fa-lock fa-sm fa-fw mr-2 text-gray-400"></i>
            Change Password
        </a>
        <div class="dropdown-divider"></div>
        <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
            <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
            Logout
        </a>
    </div>
</li>

 <!-- Logout Modal-->
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="logoutModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="logoutModalLabel">Ready to Leave?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                <a class="btn btn-primary" href="{{ route('logout') }}">Logout</a>
            </div>
        </div>
    </div>
</div>