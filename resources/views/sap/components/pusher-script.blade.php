@push('scripts')
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
        if (_.isUndefined(data.sender_id) === false && data.sender_id != '{{ $my_encrypted_id }}') {
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
                webPush(title, message, icon, link, timeout);
            } else {
                toastPush(title, message, icon, link, timeout);
            }
        }
    }
    channel.bind('{{ $general_event }}', general_callback);
});
</script>
@endpush
