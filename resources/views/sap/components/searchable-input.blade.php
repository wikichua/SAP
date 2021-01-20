<form id="quickSearchForm" class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search" action="{{ route('global.search') }}">
    <div class="input-group">
        <input type="text" name="q" value="{{ request()->input('q','') }}" class="form-control bg-light border-0 small" placeholder="Search for..." id="quickSearch" autocomplete="off">
        <div class="input-group-append">
            <button class="btn btn-primary" type="submit">
            <i class="fas fa-search fa-sm"></i>
            </button>
        </div>
    </div>
    <div class="list-group position-absolute mt-3 shadow invisible w-25" style="z-index: 1;" id="quickSearchSuggest"></div>
</form>
@once
@push('scripts')
<script id="quickSearchTemplate" type="text/x-lodash-template">
<% _.forEach(data, function(item) { %>
<a href="<%- item.url %>" class="list-group-item list-group-item-action">
    <div class="d-flex w-100 justify-content-between">
        <strong class="mb-1"><%- item.title %></strong>
        <small><%- item.created_at %></small>
    </div>
    <p class="mb-1 small"><%= item.desc %></p>
</a>
<% }); %>
<button type="button" id="goQuickSearch" class="list-group-item list-group-item-action">
    <div class="d-flex w-100 justify-content-between">
        <strong class="mb-1">more...</strong>
    </div>
</button>
</script>
<script>
$(function() {
    $(document).on('keyup', '#quickSearch', function(event) {
        var searchTerm = $(this).val();
        if ($('#quickSearchSuggest').hasClass('invisible') == false) {
            $('#quickSearchSuggest').addClass('invisible');
        }
        if (searchTerm.length >= 4) {
            $('#quickSearchSuggest').removeClass('invisible');
            axios.post(route('global.suggest'), {
              q: searchTerm
            }).then((response) => {
                var templateFn = _.template($('#quickSearchTemplate').html());
                var templateHTML = templateFn(response);
                $('#quickSearchSuggest').html(templateHTML);
            }).catch((error) => {
              console.error(error);
            }).finally(() => {
              // TODO
            });
        }
    });
    $(document).on('click', '#goQuickSearch', function(event) {
        event.preventDefault();
        $('#quickSearchForm').submit();
    });
});
</script>
@endpush
@endonce
