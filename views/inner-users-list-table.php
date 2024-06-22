<?php if (!empty($users)) : ?>
    <?php foreach ($users as $user) : ?>
        <tr>
            <td><?php echo esc_html($user->user_login); ?></td>
            <td><?php echo esc_html($user->display_name); ?></td>
            <td><?php echo esc_html($user->user_email); ?></td>
            <td><?php if (!empty($user->roles)): echo esc_html($user->roles[0]); endif; ?></td>
        </tr>
    <?php endforeach; ?>
<?php else : ?>
    <tr>
        <td colspan="3"><?php esc_html_e('No users found.', 'user-list'); ?></td>
    </tr>
<?php endif; ?>