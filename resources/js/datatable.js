const loadDatatable = function (url,sort,direction,filters) {
  let params = {};
  if (_.isUndefined(sort) === false) {
    params['sort'] = sort;
    params['direction'] = direction;
  }
  if (_.isUndefined(filters) === false) {
    params['filters'] = filters;
  }
  let rowTemplate = Handlebars.compile($('#row-template').html());
  axios.get(url, {
    params: params,
  }).then((response) => {
    let resp = response.data;
    let data = resp.paginated.data; 
    let links = resp.links;
    let rowHtml = rowTemplate({data:data});
    $('#datatable-row').html(rowHtml);
    let paginationTemplate = Handlebars.compile(links);
    let paginationHtml = paginationTemplate(resp);
    $('#datatable-pagination').html(paginationHtml);
  }).catch((error) => {
    console.error(error);
  }).finally(() => {
      // TODO
    });
};

$(document).ready(function() {
  loadDatatable(url);
  $(document).on('click', '.page-link', function(event) {
    event.preventDefault();
    let link = $(this).attr('href');
    loadDatatable(link);
  });
  $(document).on('click', '.sortable', function(event) {
    event.preventDefault();
    let key = $(this).data('key');
    let direction = $(this).data('direction');
    if (_.isUndefined(key) === false) {
      loadDatatable(url,key, direction);
      let icon = $(this).find('.icon');
      $('.icon').removeClass('fa-sort-down').removeClass('fa-sort-up');
      if (direction == 'asc') {
        icon.addClass('fa-sort-down');
        $(this).data('direction','desc');
      } else {
        icon.addClass('fa-sort-up');
        $(this).data('direction','asc');
      }
    }
  });
});