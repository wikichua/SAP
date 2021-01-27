@php
  foreach ($attributes as $key => $val) {
    $$key = $val;
  }
@endphp
<script src="//cdnjs.cloudflare.com/ajax/libs/push.js/1.0.12/push.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/push.js/1.0.12/serviceWorker.min.js"></script>
@if ($driver == 'pusher')
<script src="//js.pusher.com/7.0/pusher.min.js"></script>
@endif
@if ($driver == 'ably')
<script src="//cdn.ably.io/lib/ably.min-1.2.4.js"></script>
@endif
