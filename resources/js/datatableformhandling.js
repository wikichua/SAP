let url = '';
let currentUrl = '';
const loadDatatable = function(url, sort, direction, filters) {
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
        onUploadProgress: function(progressEvent) {
            $('#overlayLoader').show();
        },
    }).then((response) => {
        let resp = response.data;
        let data = resp.paginated.data;
        let links = resp.links;
        let rowHtml = rowTemplate({ data: data });
        $('#datatable-row').html(rowHtml);
        let paginationTemplate = Handlebars.compile(links);
        let paginationHtml = paginationTemplate(resp);
        $('#datatable-pagination').html(paginationHtml);
        currentUrl = resp.currentUrl;
    }).catch((error) => {
        // console.error(error);
    }).finally(() => {
        $('#overlayLoader').hide();
    });
};
const flashMessage = function() {
    let flash_message = cookies.get('flash-message');
    let flash_status = cookies.get('flash-status');
    if (_.isUndefined(flash_message) === false && _.isNull(flash_message) === false) {
        Toast.fire({
            icon: flash_status,
            title: flash_message
        });
        cookies.del('flash-message');
        cookies.del('flash-status');
    }
};
const commitPost = function(form) {
    let action = form.attr('action');
    if (_.isUndefined(action) === false) {
        form.find('.form-control').removeClass('is-invalid').addClass('is-valid');
        axios.request({
            method: 'POST',
            url: action,
            data: new FormData(form[0]),
            headers: {
                'Content-Type': 'multipart/form-data',
                'X-Requested-With': 'XMLHttpRequest'
            },
            onUploadProgress: function(progressEvent) {
                $('#overlayLoader').show();
            },
        }).then((response) => {
            let resp = response.data;
            // flash message
            if (resp.hasOwnProperty('flash') && _.isString(resp.flash)) {
                cookies.set('flash-status', resp.status);
                cookies.set('flash-message', resp.flash);
            }
            // redirect to page
            if (resp.hasOwnProperty('redirect') && resp.redirect) {
                $(location).attr('href', resp.redirect);
            }
            // reload current page
            if (resp.hasOwnProperty('reload') && resp.reload) {
                location.reload();
            }
            // reload datatable
            if (resp.hasOwnProperty('relist') && resp.relist) {
                flashMessage();
                loadDatatable(currentUrl);
            }
        }).catch((error) => {
            let resp = error.response.data;
            let errors = resp.errors;
            let message = resp.message;
            _.forEach(errors, function(array, fieldname) {
                $('[name=' + fieldname + ']').addClass('is-invalid');
                $('#' + fieldname + '-alert').html(_.join(array, "<br>"));
            });
            Toast.fire({
                icon: 'error',
                title: 'Opps! Something went wrong!'
            });
        }).finally(() => {
            // TODO
            $('#overlayLoader').hide();
        });
    }
};
$(document).ready(function() {
    // display flash-message
    _.attempt(flashMessage);
    $('#overlayLoader').hide();
    // datatable
    if (typeof url !== undefined) {
        _.attempt(loadDatatable, url);
    }
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
            loadDatatable(url, key, direction);
            let icon = $(this).find('.icon');
            $('.icon').removeClass('fa-sort-down').removeClass('fa-sort-up');
            if (direction == 'asc') {
                icon.addClass('fa-sort-down');
                $(this).data('direction', 'desc');
            } else {
                icon.addClass('fa-sort-up');
                $(this).data('direction', 'asc');
            }
        }
    });
    // form handler
    $(document).on('submit', 'form[data-ajax-form]', function(event) {
        event.preventDefault();
        let form = $(this);
        let confirm = form.data('confirm');
        if (_.isUndefined(confirm) === false) {
            Swal.fire({
                title: 'Are you sure?',
                text: confirm,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, please!'
            }).then((result) => {
                if (result.value) {
                    commitPost(form);
                }
            })
        } else {
            commitPost(form);
        }
    });
});
