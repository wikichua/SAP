@once
@push('scripts')
<x-sap::pusherjs driver='{{ $driver }}' />
<script src="//cdn.jsdelivr.net/npm/sweetalert2@10.13.3/dist/sweetalert2.all.min.js"></script>
<script>
$(function() {
    if (Push.Permission.has() != true) {
        Push.Permission.request();
    }
    let general_callback = function(data) {
        if (_.isUndefined(data.data) === false) {
            data = data.data;
        }
        let icon = '{{ $app_logo }}';
        if (_.isUndefined(data.icon) === false) {
            icon = data.icon;
        }
        let link = '';
        if (_.isUndefined(data.link) === false) {
            link = data.link;
        }
        let timeout = 5000;
        if (_.isUndefined(data.timeout) === false) {
            timeout = data.timeout;
        }
        let title = '{{ $app_title }}';
        if (_.isUndefined(data.title) === false) {
            title = data.title;
        }
        let message = '';
        if (_.isUndefined(data.message) === false) {
            message = data.message;
        } else if (_.isArray(data)) {
            message = data.join("\n");
        } else if (_.isString(data)){
            message = data;
        }
        if (Push.Permission.has()) {
            Push.create(title,{
                    body: message,
                    icon: icon,
                    link: link,
                    timeout: timeout,
                    onClick: function () {
                        window.focus();
                        this.close();
                    }
                });
        } else {
            var NotiToast = Swal.mixin(
            {
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: timeout,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });
            NotiToast.fire({
                icon: 'success',
                title: title,
                html: message,
            });
        }
    }
    @if ($driver == 'pusher')
    Pusher.logToConsole = '{{ config('app.debug') }}';
    let pusher = new Pusher('{{ $app_key }}', {
      cluster: '{{ $cluster }}',
      useTLS: true
    });
    let channel = pusher.subscribe('{{ $channel }}');
    channel.bind('{{ $general_event }}', general_callback);
    @endif
    @if ($driver == 'ably')
    var ably = new Ably.Realtime('{{ $app_key }}');
    var channel = ably.channels.get('{{ $channel }}');
    channel.subscribe('{{ $general_event }}', general_callback);
    @endif
});
</script>
@endpush
@endonce
