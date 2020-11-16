<form method="POST" action="{{ route('{%brand_string%}.login') }}">
  @csrf()
  @honeypot
  <!-- Modal -->
  <div class="modal fade" id="elegantModalForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">
    <div class="modal-dialog form-dark" role="document">
      <!--Content-->
      <div class="modal-content form-elegant">
        <!--Header-->
        <div class="modal-header text-center">
          <h3 class="modal-title w-100 dark-grey-text font-weight-bold my-3" id="myModalLabel"><strong>Sign in</strong></h3>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <!--Body-->
        <div class="modal-body mx-4">
          <!--Body-->
          <div class="md-form mb-5">
            <input type="email" name="email" id="email" class="form-control validate">
            <label data-error="wrong" data-success="right" for="email">Your email</label>
          </div>
          <div class="md-form pb-3">
            <input type="password" name="password" id="password" class="form-control validate">
            <label data-error="wrong" data-success="right" for="password">Your password</label>
            <p class="font-small blue-text d-flex justify-content-end">Forgot <a href="#" class="blue-text ml-1">
            Password?</a></p>
          </div>
          <div class="text-center mb-3">
            <button type="button" class="btn blue-gradient btn-block btn-rounded z-depth-1a">Sign in</button>
          </div>
          <p class="font-small dark-grey-text text-right d-flex justify-content-center mb-3 pt-2"> or Sign in
          with:</p>
          <div class="row my-3 d-flex justify-content-center">
            @foreach(array_keys(config('services')) as $provider)
                @if (config("services.{$provider}.client_secret",'') != '')
                <a href="{{ route('{%brand_string%}.social.login', [$provider]) }}" class="btn btn-link btn-white btn-rounded mr-md-3 z-depth-1a"><i class="fab fa-{{ $provider }} text-center"></i></a>
                @endif
            @endforeach
          </div>
        </div>
        <!--Footer-->
        <div class="modal-footer mx-5 pt-3 mb-1">
          <p class="font-small grey-text d-flex justify-content-end">Not a member? <a href="#" class="blue-text ml-1">
          Sign Up</a></p>
        </div>
      </div>
      <!--/.Content-->
    </div>
  </div>
  <!-- Modal -->
</form>
