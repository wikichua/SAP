<x-sap::select-field name="route_slug" id="route_slug" label="Route Slug" :class="['']" :attribute_tags="[]" :data="['style'=>'border bg-white','live-search'=>false]" :options="[]" :selected="[]"/>
@push('scripts')
<script id="route_slug-template" type="text/x-lodash-template">
<option value=""></option>
<% _.forEach(data, function(value, key) { %>
<option value="<%- key %>"><%- value %></option>
<% }); %>
</script>
<script>
$(function () {
    $(document).on('change','#brand_id',function() {
        let brand_id = $(this).val();
        axios.get(route('nav.pages',brand_id)).then((response) => {
            var template = $('#route_slug-template').html();
            var templateFn = _.template(template);
            var templateHTML = templateFn(response);
            $('#route_slug').html(templateHTML);
            $('#route_slug').selectpicker('refresh');
            @if (isset($model->route_slug) && $model->route_slug != '')
            $('#route_slug').selectpicker('val', '{{ $model->route_slug }}');
            @endif
        }).catch((error) => {
          console.error(error);
        }).finally(() => {

        });
    });
    $('#brand_id').trigger('change');
});
</script>
@endpush
