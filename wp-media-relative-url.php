<?php if ( ! defined( 'ABSPATH' ) ) { die( 'Forbidden' ); }
/**
 * Plugin Name: Inoforest Relative Url Update
 * Plugin URI: https://github.com/hlaporthein/wp-media-relative-url
 * Description: Inoforest Relative Media Url Update
 * Version: 1.0
 * Author: Hla Por Thein
 * Author URI: http://hlaporthein.me
 * License: GPL2+
 * Text Domain: vd
 */

define('WPMRU_LANG', 'wpmru');
/**
 * Activate require functions when activated plugins
 */
function ino_wpmru_plugin_activate() {
    _ino_wpmru_update_url_and_path_for_media();
}

register_activation_hook( __FILE__, 'ino_wpmru_plugin_activate' );

/**
 * Activate require functions when deactivated plugins
 */
function ino_wpmru_plugin_deactivate() {
    delete_transient('ino_wpmru_upload_path_success_message');
    delete_transient('wpmru-url-alreadyconfigure-message');
}

register_deactivation_hook( __FILE__, 'ino_wpmru_plugin_deactivate' );

add_action( 'admin_notices', 'ino_wpmru_upload_path_success_message' );
function ino_wpmru_upload_path_success_message() {
    if( get_transient( 'ino_wpmru_upload_path_success_message' ) ){
        ?>
        <div class="notice notice-success is-dismissible">
            <p><?php _e( 'We have updated upload_path.', 'WPMRU_LANG' ); ?></p>
        </div>
        <?php
        delete_transient('ino_wpmru_upload_path_success_message');
    }
}

add_action( 'admin_notices', 'ino_wpmru_upload_url_path_success_message' );
function ino_wpmru_upload_url_path_success_message() {
    if( get_transient( 'ino_wpmru_upload_url_path_success_message' ) ){
        ?>
        <div class="notice notice-success is-dismissible">
            <p><?php _e( 'We have updated upload_url_path.', 'WPMRU_LANG' ); ?></p>
        </div>
        <?php
        delete_transient('ino_wpmru_upload_url_path_success_message');
    }
}

add_action( 'admin_notices', 'ino_wpmru_upload_path_value_print_message' );
function ino_wpmru_upload_path_value_print_message() {
    if( get_transient( 'wpmru-url-alreadyconfigure-message' ) ){
        ?>
        <div class="notice notice-warning is-dismissible">
            <p>It has already configure relative url with another way.</p>
            <p><b>Upload Path</b> - <code><?php echo get_option('upload_path'); ?></code></p>
            <p><b>Upload Url Path</b> - <code><?php echo get_option('upload_url_path'); ?></code></p>
        </div>
        <?php
        delete_transient('wpmru-url-alreadyconfigure-message');
    }
}


function _ino_wpmru_update_url_and_path_for_media() {
    $upload_path = get_option('upload_path');
    $upload_url_path = get_option('upload_url_path');

    if ( empty($upload_path) && empty($upload_url_path) ) {

        if ( empty($upload_path) ) {
            update_option('upload_path', 'wp-content/uploads');
            set_transient( 'ino_wpmru_upload_path_success_message', true, 5 );
        } else {
            if ( !empty($upload_path) && ( $upload_path === "wp-content/uploads") ) {
                set_transient( 'ino_wpmru_upload_path_success_message', true, 5 );
            } else {
                set_transient('wpmru-url-alreadyconfigure-message', 5);
            }
        }
    
    
        if ( empty($upload_url_path) ) {
            update_option('upload_url_path', '/wp-content/uploads');
            set_transient( 'ino_wpmru_upload_url_path_success_message', true, 5 );
        } else {
            if ( !empty($upload_url_path) && ( $upload_url_path === "/wp-content/uploads") ) {
                set_transient( 'ino_wpmru_upload_url_path_success_message', true, 5 );
            } else {
                set_transient('wpmru-url-alreadyconfigure-message', 5);
            }
        }

    } else {

        set_transient('wpmru-url-alreadyconfigure-message', 5);

    }

}