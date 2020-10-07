let url = '';
let currentUrl = '';
let listTable;

const loadDatatable = function(url, filters, take) {
    let parameters = {};
    if (_.isUndefined(filters) === false) {
        parameters['filters'] = filters;
    }
    if (_.isUndefined(take) === false) {
        parameters['take'] = take;
    }
    axios.get(url, {
        params: parameters,
        onUploadProgress: function(progressEvent) {
            $('#overlayLoader').show();
        },
    }).then((response) => {
        let resp = response.data;
        let data = resp.paginated.data;
        let links = resp.links;
        let paginationTemplate = _.template(links);
        let paginationHtml = paginationTemplate();
        $('#datatable-pagination').html(paginationHtml);
        listTable.bootstrapTable('load', data);
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
        form.find('.form-control,.form-control-plaintext').removeClass('is-invalid').addClass('is-valid');
        $('.invalid-feedback').hide();
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
                $('#' + fieldname + '-alert').html(_.join(array, "<br>")).show();
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
const onImageUpload = async function(file, editor) {
    let formData = new FormData();
    formData.append('image', file, file.name);
    await axios({
        url: route('editor.upload_image'),
        method: "POST",
        data: formData
    }).then(result => {
        let url = result.data.url; // Get url from response
        editor.summernote('insertImage', url);
    }).catch(err => {
        console.error(err);
    });
};
const previewImage = function($this) {
    let dom = $this.get(0);
    let previewDiv = $this.closest('.form-group').find('.row').find('.img-preview').find('.row').empty();
    if (dom.files) {
        var filesAmount = dom.files.length;
        for (i = 0; i < filesAmount; i++) {
            var reader = new FileReader();
            reader.onload = function(event) {
                $($.parseHTML('<div class="col-2"><img src="' + event.target.result + '" class="img-thumbnail"></div>')).appendTo(previewDiv);
            }
            reader.readAsDataURL(dom.files[i]);
        }
    }
}
$(document).ready(function() {
    // display flash-message
    _.attempt(flashMessage);
    $('#overlayLoader').hide();
    // datatable
    if (_.isUndefined(url) === false) {
        listTable = $('.bootstrap-table').bootstrapTable();
        _.attempt(loadDatatable, url);
    }
    $(document).on('click', '.page-link', function(event) {
        event.preventDefault();
        let link = $(this).attr('href');
        loadDatatable(link);
    });
    $(document).on('change', '#pageTake', function(event) {
        event.preventDefault();
        let params = {};
        params['filter'] = $('.filterInput').serialize();
        loadDatatable(currentUrl, params, $(this).val());
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
    // search btn trigger
    $(document).on('click', '#filterBtn', function(event) {
        event.preventDefault();
        let params = {};
        params['filter'] = $('.filterInput').serialize();
        loadDatatable(url, params);
        $('#filterModalCenter').modal('hide');
    });
    // init selectpicker
    $('.selectpicker').selectpicker();
});
