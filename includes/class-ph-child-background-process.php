<?php

require_once plugin_dir_path( __DIR__ ) . 'includes/libraries/wp-background-processing/class-ph-wp-background-process.php';

class PH_Child_Background_Process extends \PH_WP_Background_Process {

	/**
	 * @var string
	 */
	protected $action = 'add_sub_sites_process';

	public $sites_added = array();

	/**
	 * Task
	 *
	 * Override this method to perform any actions required on each
	 * queue item. Return the modified item for further processing
	 * in the next pass through. Or, return false to remove the
	 * item from the queue.
	 *
	 * @param mixed $item Queue item to iterate over
	 *
	 * @return mixed
	 */
	protected function task( $item ) {
		$job = $item['job'];

		if ( $job === 'add' ) {
			$site = $item['data'];
			// Insert the page into the database
			$page_id = wp_insert_post(
				array(
					'post_title'  => get_blog_option( $site->blog_id, 'blogname' ),
					'post_status' => 'publish',
					'post_type'   => 'ph-website',
				)
			);

			// add meta
			update_post_meta( $page_id, 'ph_website_url', get_site_url( $site->blog_id ) );
			update_post_meta( $page_id, 'ph_installed', true );

			// maybe regenerate key
			$this->ph_generate_api_key( $page_id, false, get_site_url( $site->blog_id ) );

			// update post id
			update_blog_option( $site->blog_id, 'ph_site_post', $page_id );

			$data = array(
				'id'           => $page_id,
				'parent_url'   => apply_filters( 'ph_child_website_parent_url', get_home_url() ),
				'api_key'      => get_post_meta( $page_id, 'ph_website_api', true ),
				'access_token' => ph_get_post_access_token( $page_id ),
				'signature'    => ph_post_signature_key( $page_id ),
				'child_url'    => get_post_meta( $page_id, 'website_url', true ),
			);

			foreach ( $data as $key => $value ) {
				update_blog_option( $site->blog_id, 'ph_child_' . $key, $value );
			}
		} elseif ( $job === 'remove' ) {
			$ph_post_id = $item['data'];
			wp_delete_post( $ph_post_id, true );
		}

		return false;
	}

	/**
	 * Complete
	 *
	 * Override if applicable, but ensure that the below actions are
	 * performed, or, call parent::complete().
	 */
	public function complete() {
		parent::complete();

		// Show notice to user or perform some other arbitrary task...
	}

	public function is_queue_empty() {
		return parent::is_queue_empty();
	}

	/**
	 * This generates the API key for the site.
	 *
	 * @param $post_id
	 * @param $post
	 * @param $url
	 *
	 * @return false|string
	 */
	public function ph_generate_api_key( $post_id, $post = false, $url = false ) {
		$requested  = isset( $_REQUEST['ph_website_url'] ) ? $_REQUEST['ph_website_url'] : false;
		$url        = $url == false ? $requested : $url;
		$stored_api = get_post_meta( $post_id, 'ph_website_api', true );

		// we need a website url
		if ( ! isset( $url ) || ! $url ) {
			return false;
		}

		// if there's no api set yet
		if ( ! $stored_api ) {
			// generate api key based on post id, url and time
			$api_key = md5( $post_id . $url . time() );

			// update post meta
			update_post_meta( $post_id, 'ph_website_api', sanitize_text_field( $api_key ) );
		} else {
			$api_key = $stored_api;
		}

		return isset( $api_key ) ? sanitize_text_field( $api_key ) : false;
	}
}
