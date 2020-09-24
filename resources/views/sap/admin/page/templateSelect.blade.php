<x-sap-select-field name="template" id="template" label="Template" :class="['']" :attribute_tags="[]" :data="['style'=>'border bg-white','live-search'=>false]" :options="[]" :selected="[]"/>
@push('scripts')
<script id="page-template" type="text/x-lodash-template">
<option value=""></option>
<% _.forEach(data, function(value, key) { %>
<option value="<%- key %>"><%- value %></option>
<% }); %>
</script>
<script>
$(function () {
    $(document).on('change','#brand_id',function() {
        let brand_id = $(this).val();
        axios.get(route('page.templates',brand_id)).then((response) => {
            var template = $('#page-template').html();
            var templateFn = _.template(template);
            var templateHTML = templateFn(response);
            $('#template').html(templateHTML);
            $('#template').selectpicker('refresh');
            @if (isset($model->template) && $model->template != '')
            $('#template').selectpicker('val', '{{ $model->template }}');
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
