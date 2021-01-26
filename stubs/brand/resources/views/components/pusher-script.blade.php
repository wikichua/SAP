@once
@push('scripts')
<script src="//cdnjs.cloudflare.com/ajax/libs/push.js/1.0.12/push.min.js" ></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/push.js/1.0.12/serviceWorker.min.js"></script>
<script src="//js.pusher.com/7.0/pusher.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@10.13.3/dist/sweetalert2.all.min.js"></script>
<script>
$(function() {
    if (Push.Permission.has() != true) {
        Push.Permission.request();
    }
    Pusher.logToConsole = '{{ config('app.debug') }}';
    let pusher = new Pusher('{{ $app_key }}', {
      cluster: '{{ $cluster }}',
      useTLS: true
    });
    let channel = pusher.subscribe('{{ $channel }}');

    let general_callback = function(data) {
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
    channel.bind('{{ $general_event }}', general_callback);
});
</script>
@endpush
@endonce
