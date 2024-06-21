<div class="wrap">
    <h1>Users List</h1>
    <table class="wp-list-table widefat fixed striped">
        <thead>
        <tr>
            <th>Username</th>
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
        </tr>
        </thead>
        <tbody>
        <?php  if ( ! empty( $users ) ) : ?>
            <?php foreach ( $users as $user ) : ?>
                <tr>
                    <td><?php echo esc_html( $user->user_login ); ?></td>
                    <td><?php echo esc_html( $user->display_name ); ?></td>
                    <td><?php echo esc_html( $user->user_email ); ?></td>
                    <td><?php echo esc_html( $user->roles[0] ); ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else : ?>
            <tr>
                <td colspan="3"><?php esc_html_e( 'No users found.', 'user-list' ); ?></td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>

    <?php
    if ( $total_pages > 1 ) {
        $current_url = admin_url( 'admin.php?page=users-list' );
        $pagination_args = array(
            'base' => add_query_arg( 'paged', '%#%', $current_url ),
            'format' => '',
            'prev_text' => __( '&laquo;', 'user-list' ),
            'next_text' => __( '&raquo;', 'user-list' ),
            'total' => $total_pages,
            'current' => $current_page,
        );
        $first_page_url = add_query_arg( 'paged', 1, $current_url );
        $last_page_url = add_query_arg( 'paged', $total_pages, $current_url );

        echo '<div class="tablenav"><div class="tablenav-pages">';
        echo '<a class="first-page page-numbers" href="' . esc_url( $first_page_url ) . '">' . __( 'First', 'user-list' ) . '</a>';
        echo paginate_links( $pagination_args );
        echo '<a class="last-page page-numbers" href="' . esc_url( $last_page_url ) . '">' . __( 'Last', 'user-list' ) . '</a>';
        echo '</div></div>';
    }
    ?>
</div>
