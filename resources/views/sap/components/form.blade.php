<form
  enctype="multipart/form-data"
  novalidate
  {{ $ajax }}
  method="{{ in_array($method, ['POST','GET'])? $method:'POST' }}"
  action="{{ $action }}"
  class="{{ $class }}"
  id="{{ $id }}"
  name="{{ $name }}"
  @if ($confirm != '')
    data-confirm="{{ $confirm }}"
  @endif
  >
  @if (!in_array($method, ['POST','GET']))
  @method($method)
  @endif
  @csrf
  @honeypot
  {{ $slot }}
</form>
