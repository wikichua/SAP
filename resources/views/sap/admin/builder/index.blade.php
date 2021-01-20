@extends('sap::layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap pt-1 pb-2 mb-1 row">
    <div class="btn-toolbar col-md-10">
        <span class="h2">
            <span id="subTitle">Builder</span>
        </span>
    </div>
</div>
<div class="container-fluid" id="gjs">
    {{-- template --}}
    <div class="txt-red">Hello world!</div>
    <style>.txt-red{color: red}</style>
    {{-- end template --}}
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/grapesjs@0.16.30/dist/css/grapes.min.css">
<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/grapesjs-preset-webpage@0.1.11/dist/grapesjs-preset-webpage.min.css">
@endpush
@push('scripts')
<script src="//cdn.jsdelivr.net/npm/grapesjs@0.16.30/dist/grapes.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/grapesjs-preset-webpage@0.1.11/dist/grapesjs-preset-webpage.min.js"></script>
<script type="text/javascript">
  var editor = grapesjs.init({
      container : '#gjs',
      fromElement: true,
      plugins: ['gjs-preset-webpage']
  });
  editor.StorageManager.add('local', {
  // New logic for the local storage
  load() {
    // ...
  },

  store() {
    // ...
  },
});

</script>
@endpush
