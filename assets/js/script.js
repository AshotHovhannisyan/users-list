jQuery(document).ready(function ($) {
    let orderby = '';
    let order = 'DESC';
    let paged = 1;
    $('.sort').on('click', function (e) {
        e.preventDefault();

        orderby = $(this).data('orderby');
        order = $(this).data('order');

        $.ajax({
            url: userList.ajax_url,
            type: 'POST',
            data: {
                action: 'sort_users',
                orderby,
                order,
                paged,
                nonce: userList.nonce
            },
            success: response => {
                if (response.success) {
                    $('#user-list-table-body').html(response.data);

                    const upArrow = $(this).next('.sort-icons').find('i.up-arrow');
                    const downArrow = $(this).next('.sort-icons').find('i.down-arrow');
                    const sort = $('.sort');
                    if (order === 'ASC') {
                        sort.data('order', 'DESC');
                        sort.next('.sort-icons').find('i').css('border-top-color', '#222');
                        upArrow.css('border-bottom-color', '#fff');
                    } else {
                        sort.data('order', 'ASC');
                        sort.next('.sort-icons').find('i').css('border-bottom-color', '#222');
                        downArrow.css('border-top-color', '#fff');
                    }


                    $('.pagination-numbers .page-numbers').removeClass('current');
                    $(`.pagination-numbers [data-page="${paged}"]`).addClass('current');
                } else
                    $('#user-list-table-body').html('');
            },
            error: () => {
                alert('An error occurred.');
            }
        });
    });

    const sortByRole = $('.sort-by-role input');
    let isPage = 0;
    // Pagination click event
    $(document).on('click', '.pagination-links a', function (e) {
        e.preventDefault();

        paged = $(this).data('page');
        checkPos(order, paged);
        if('' !== sortByRole.val()){
            isPage = 1;
            sortByRole.trigger('keyup');
        }else {

            $.ajax({
                url: userList.ajax_url,
                type: 'POST',
                data: {
                    action: 'sort_users',
                    orderby,
                    order,
                    paged,
                    nonce: userList.nonce
                },
                success: response => {
                    if (response.success) {
                        $('#user-list-table-body').html(response.data);
                    } else
                        $('#user-list-table-body').html('');
                },
                error: () => {
                    alert('An error occurred.');
                }
            });
        }
    });

    sortByRole.on('keyup', function () {
        const roleValue = $(this).val();

        if (!isPage) paged = 1;
        isPage = 0;
        $.ajax({
            url: userList.ajax_url,
            type: 'POST',
            data: {
                action: 'filter_by_role',
                roleValue,
                paged,
                nonce: userList.nonce
            },
            success: response => {
                if (response.success) {
                    $('#user-list-table-body').html(response.data.users);
                    $('.pagination-numbers .page-numbers').removeClass('current');
                    $(`.pagination-numbers [data-page="${paged}"]`).addClass('current');

                    updatePagination(response.data.total_pages, paged);
                } else
                    $('#user-list-table-body').html('');
            },
            error: () => {
                alert('An error occurred.');
            }
        });
    })

    function updatePagination(totalPages, paged) {
        $.ajax({
            url: userList.ajax_url,
            type: 'POST',
            data: {
                action: 'gat_pagination_page',
                totalPages,
                paged,
                nonce: userList.nonce
            },
            success: response => {
                if (response.success){
                    $('.pagination').html(response.data);
                }
                else
                    $('.pagination').html('');
            },
            error: () => {
                alert('An error occurred.');
            }
        });
    }

    function checkPos(order, paged) {
        $('.pagination-numbers .page-numbers').removeClass('current');
        $(`.pagination-numbers [data-page="${paged}"]`).addClass('current');
        $('.pagination-links .prev-page').data('page', Math.max(1, paged - 1));
        $('.pagination-links .next-page').data('page', Math.min(4, paged + 1));
    }
});