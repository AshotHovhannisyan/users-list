<div class="wrap">
    <h1>Users List</h1>
    <?php echo do_shortcode('[import-users]') ?>
    <div class="sort-by-role">
        <input type="text">
    </div>
    <div class="ah-users-list-table">
        <table class="ah-list-table">
            <thead>
            <tr>
                <th>
                    <span class="sort" data-orderby="user_login" data-order="DESC">Username</span>
                    <span class="sort-icons">
                        <i class="up-arrow"></i>
                        <i class="down-arrow"></i>
                    </span>
                </th>
                <th>
                    <span class="sort" data-orderby="display_name" data-order="DESC">Name</span>
                    <span class="sort-icons">
                        <i class="up-arrow"></i>
                        <i class="down-arrow"></i>
                    </span>
                </th>
                <th>Email</th>
                <th>Role</th>
            </tr>
            </thead>
            <tbody id="user-list-table-body">
            <?php include plugin_dir_path(__FILE__) . 'inner-users-list-table.php'; ?>
            </tbody>
        </table>
    </div>

    <div class="pagination">
        <?php include plugin_dir_path(__FILE__) . 'pagination.php'; ?>
    </div>

</div>
