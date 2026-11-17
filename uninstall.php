<?php
/**
 * Uninstall SureFeedback Child Plugin
 *
 * Deletes all the plugin data
 *
 * @package     SureFeedback Child
 * @subpackage  Uninstall
 * @copyright   Copyright (c) 2016, Andre Gagnon
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

// delete our options.
delete_option( 'ph_child_api_key' );
delete_option( 'ph_child_access_token' );
delete_option( 'ph_child_project_id' );
delete_option( 'ph_child_parent_url' );
delete_option( 'ph_child_signature' );
delete_option( 'ph_child_installed' );
delete_option( 'ph_child_admin_enabled' );
delete_option( 'ph_child_allow_guests' );
delete_option( 'ph_child_connection_status' );
delete_option( 'ph_child_commenters' );
delete_option( 'ph_child_manual_connection' );
delete_option( 'ph_child_id' );
delete_option( 'ph_child_admin' );
delete_option( 'ph_child_enabled_comment_roles' );
delete_option( 'dismissed-ph-review-comment-roles' );
delete_site_option( 'dismissed-ph-review-comment-roles' );

// white label options.
delete_option( 'ph_child_plugin_name' );
delete_option( 'ph_child_plugin_description' );
delete_option( 'ph_child_plugin_author' );
delete_option( 'ph_child_plugin_author_url' );
delete_option( 'ph_child_plugin_link' );
