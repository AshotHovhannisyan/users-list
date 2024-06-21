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

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'Ah_users_List' ) ) {

    class Ah_users_List {

        public function __construct() {
            add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
            add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_styles' ) );
        }

        public function add_admin_menu() {
            add_menu_page(
                'Users List',
                'Users List',
                'manage_options',
                'users-list',
                array( $this, 'display_users_list' )
            );
        }

        public function enqueue_styles() {
            wp_enqueue_style( 'ah-users-list', plugin_dir_url( __FILE__ ) . 'assets/css/style.css' );
        }

        public function display_users_list() {
            $current_page = isset( $_GET['paged'] ) ? absint( $_GET['paged'] ) : 1;
            $users_per_page = get_option( 'posts_per_page', 10);
            $offset = ( $current_page - 1 ) * $users_per_page;

            $users = get_users( array(
                'number' => $users_per_page,
                'offset' => $offset
            ) );

            $total_users = count_users();
            $total_pages = ceil( $total_users['total_users'] / $users_per_page );

            include plugin_dir_path( __FILE__ ) . 'views/users-list.php';
        }
    }
}

new Ah_users_List();