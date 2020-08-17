<a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
</a>
@routes
<script src="{{ asset('js/app.js') }}"></script>
<script src="{{ asset('js/all.js') }}"></script>
<script src="{{ asset('js/datatableformhandling.min.js') }}"></script>
<script>
$(function() {
    @if (env('PUSHER_APP_KEY') != '')
    Pusher.logToConsole = true;
    let pusher = new Pusher('{{ env('PUSHER_APP_KEY') }}', {
      cluster: 'ap1',
      forceTLS: true
    });
    let pusher_callback = function(data) {
        let icon = '{{ asset('sap/logo.png') }}';
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
        let title = '{{ env('APP_NAME') }} Web Notification';
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
        Push.create(title, {
            body: message,
            icon: icon,
            link: link,
            timeout: timeout,
            onClick: function () {
                window.focus();
                this.close();
            }
        });
    }
    let channel = pusher.subscribe('{{ sha1(env('APP_NAME')) }}');
    channel.bind('{{ sha1('general') }}', pusher_callback);
    @endif
});
</script>
