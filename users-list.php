<?php
/*
Plugin Name: User List
Description: User List Plugin is a simple WordPress plugin that displays a paginated list of users in the admin area.
Version: 1.0
Author: Ashot Hovhannisyan
Text Domain: user list
License: GPL-3
License URI: https://www.gnu.org/licenses/gpl-3.0.html
*/

if (!defined('ABSPATH')) exit;

if (!class_exists('Ah_users_List')) {

    class Ah_users_List
    {

        public function __construct()
        {
            add_action('admin_menu', array($this, 'add_admin_menu'));
            add_action('admin_enqueue_scripts', array($this, 'enqueue_styles'));
            add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts'));
            add_action('wp_ajax_sort_users', array($this, 'sort_users'));
            add_action('wp_ajax_nopriv_sort_users', array($this, 'sort_users'));
            add_action('wp_ajax_filter_by_role', array($this, 'filter_by_role'));
            add_action('wp_ajax_nopriv_filter_by_role', array($this, 'filter_by_role'));
            add_action('wp_ajax_gat_pagination_page', array($this, 'gat_pagination_page'));
            add_action('wp_ajax_nopriv_gat_pagination_page', array($this, 'gat_pagination_page'));
        }

        public function add_admin_menu()
        {
            add_menu_page(
                'Users List',
                'Users List',
                'manage_options',
                'users-list',
                array($this, 'display_users_list')
            );
        }

        public function enqueue_scripts()
        {
            wp_enqueue_script('ah-user-list-script', plugin_dir_url(__FILE__) . 'assets/js/script.js', array('jquery'), time(), true);
            wp_localize_script('ah-user-list-script', 'userList', array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('user_list_nonce')
            ));
        }

        public function enqueue_styles()
        {
            wp_enqueue_style('ah-users-list', plugin_dir_url(__FILE__) . 'assets/css/style.css', '', time());
        }

        public function display_users_list()
        {
            $current_page = isset($_GET['paged']) ? absint($_GET['paged']) : 1;
            $users_per_page = get_option('posts_per_page', 10);
            $offset = ($current_page - 1) * $users_per_page;

            $users = get_users(array(
                'number' => $users_per_page,
                'offset' => $offset
            ));

            $total_users = count_users();
            $total_pages = ceil($total_users['total_users'] / $users_per_page);

            include plugin_dir_path(__FILE__) . 'views/users-list.php';
        }

        public function sort_users()
        {
            check_ajax_referer('user_list_nonce', 'nonce');

            $users_per_page = get_option('posts_per_page', 10);
            $paged = $_POST['paged'];
            $offset = ($paged - 1) * $users_per_page;
            $order_by = $_POST['orderby'];
            $current_page = $_POST['paged'];
            $order = $_POST['order'];

            $users = get_users(array(
                'number' => $users_per_page,
                'offset' => $offset,
                'orderby' => $order_by,
                'order' => $order
            ));

            ob_start();

            include plugin_dir_path(__FILE__) . 'views/inner-users-list-table.php';

            $output = ob_get_clean();

            wp_send_json_success($output);
        }

        public function filter_by_role(){
            check_ajax_referer('user_list_nonce', 'nonce');
            $users_per_page = get_option('posts_per_page', 10);
            $roleValue = $_POST['roleValue'];
            $paged = $_POST['paged'];
            $offset = ($paged - 1) * $users_per_page;
            $roles = wp_roles()->roles;
            $matching_roles = [];
            foreach ($roles as $role_key => $role_data) {
                if (stripos($role_key, $roleValue) !== false) {
                    $matching_roles[] = $role_key;
                }
            }
            if (empty($matching_roles)) {
                echo '<p>No users found with roles matching "' . esc_html($roleValue) . '"</p>';
                wp_die();
            }

            $users_by_role = new WP_User_Query(array(
                'number' => $users_per_page,
                'offset' => $offset,
                'role__in' => $matching_roles
            ));
            $users = $users_by_role->get_results();

            $total_users = $users_by_role->get_total();
            $total_pages = ceil($total_users / $users_per_page);
            ob_start();

            include plugin_dir_path(__FILE__) . 'views/inner-users-list-table.php';
            $output = ob_get_clean();
            wp_send_json_success([
                'users' => $output,
                'total_pages' => $total_pages
            ]);
        }

        public function gat_pagination_page(){
            check_ajax_referer('user_list_nonce', 'nonce');
            $total_pages = $_POST['totalPages'];
            $current_page = $_POST['paged'];
            ob_start();
            include plugin_dir_path(__FILE__) . 'views/pagination.php';
            $output = ob_get_clean();
            wp_send_json_success($output);
        }
    }
}

new Ah_users_List();