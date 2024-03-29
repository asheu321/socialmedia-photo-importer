<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://aguspri.com
 * @since      1.0.0
 *
 * @package    Smpi
 * @subpackage Smpi/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Smpi
 * @subpackage Smpi/admin
 * @author     Agus Priyanto <asheu321@gmail.com>
 */
class Smpi_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Smpi_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Smpi_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/smpi-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Smpi_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Smpi_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/smpi-admin.js', array( 'jquery' ), $this->version, false );
	
		wp_localize_script( $this->plugin_name, 'SMPIAdminJs', array(
			'ajaxUrl' => admin_url('admin-ajax.php'),
			'current_screen' => get_current_screen(),
			'l10n' => array(
				'instagram' 					=> __('Instagram' , 'smpi' ),
				'image' 						=> __('Image', 'smpi'),
				'copy_paste' 					=> __('Copy & Paste' , 'smpi'),
				'instagram_record' 				=> __('Record' , 'smpi' ),
			),
			'options' => array(
				'mime_types'					=> array(
					'image/jpeg'	=> 'jpg',
					'image/png'		=> 'png',
				),
			),
		));

	}

	/**
	 * Add Submenu Link
	 */
	public function admin_menu() {
		add_submenu_page('options-general.php', 'Social Media Photo Importer', 'Social Media Photo Importer', 'manage_options', 'smpi_settings', array($this, 'smpi_admin_page'));
	}

	/**
	 * Admin settings page
	 */
	public function smpi_admin_page() {
		include 'partials/smpi-admin-settings.php';
	}

	/**
	 * Check instagram data
	 */
	public function check_instagram_data() {
		$instadata = get_option( 'smpi_instagram_account' );

		$data = '';
		if ( $instadata != '' && is_array($instadata) ) {
			$data = json_encode(array('data' => $instadata));
			echo $data;
			die();
		}

		echo json_encode(array('data' => ''));
		die();
	}

	/**
	 * Save instagram username / Ajax Callback
	 */
	public function save_instagram_username() {
		require_once SMPI_LIB_DIR . 'InstagramMediaScraper/vendor/autoload.php';

		require_once SMPI_LIB_DIR . 'InstagramMediaScraper/InstagramScraper.php';

		$username = isset($_POST['username']) && $_POST['username'] != '' ? $_POST['username'] : false;

		if ( !$username ) {
			echo json_encode( ['status' => '404', 'message' => 'Please enter your Instagram username', 'data' => []] );
			exit;
		}

		$instagram = new \InstagramScraper\Instagram();

		try {
			$account = $instagram->getAccount($username);

			$saved_account = array(
				'ID' => $account->getId(),
				'username' => $account->getUsername(),
				'full_name' => $account->getFullName(),
				'profile_picture' => $account->getProfilePicUrl(),
				'media_count' => $account->getMediaCount()
			);

			$not_found = false;
			
			update_option( 'smpi_instagram_account', $saved_account );
			
		} catch (InstagramScraper\Exception\InstagramNotFoundException $e) {
			$not_found = $e->getMessage();
		}

		if ( $not_found ) {
			echo json_encode( ['status' => '404', 'message' => $not_found, 'data' => []] );
			exit;
		}

		echo json_encode( ['status' => '200', 'message' => null, 'data' => $saved_account] );
		die();
	}

	/**
	 * Override WP Media
	 */
	public function wp_media_override() {
		add_action( 'admin_print_footer_scripts', array( $this, 'override_media_templates'), 11 );
	}

	/**
	 * Override WP Media template
	 */
	public function override_media_templates() {
		include 'partials/smpi-media-template.php';
	}

	/**
	 * Instagram featch media: Ajax submit form callback
	 * TODO: Currently it's fetch data everytime we open media uploader / instagram tab,
	 * Next time we need to cache the data so that we can get the data faster without 
	 * having to make an API request everytime.
	 */
	public function instagram_get_media() {
		
		$userdata = get_option( 'smpi_instagram_account' );

		if ( !$userdata || $userdata == '' ) {
			echo json_encode(array('status' => '404', 'message' => 'Please enter your Instagram username <a href="'.admin_url('options-general.php?page=smpi_settings').'">here</a>.'));
			die();
		}

		$username = $userdata['username'];

		require_once SMPI_LIB_DIR . 'InstagramMediaScraper/vendor/autoload.php';

		require_once SMPI_LIB_DIR . 'InstagramMediaScraper/InstagramScraper.php';

		$instagram = new \InstagramScraper\Instagram();
		$has_next = false;
		if ($has_next) {

			try {
				$medias = $instagram->getPaginateMedias($username, $max_id);
				$not_found = false;
			} catch (InstagramScraper\Exception\InstagramNotFoundException $e) {
				$not_found = $e->getMessage();
			}
			
		} else {

			try {
				$medias = $instagram->getPaginateMedias($username);
				$not_found = false;
			} catch (InstagramScraper\Exception\InstagramNotFoundException $e) {
				$not_found = $e->getMessage();
			}

		}

		if ( $not_found ) {
			echo json_encode( array( 'status' => 'error', 'message' => $not_found, 'username' => $username, 'count' => 0, 'images' => [], 'has_next' => false, 'max_id' => false ) );
			exit;
		}

		$images = [];
		foreach ( $medias['medias'] as $media ) {
			$images[] = $media->getImageThumbnailUrl();
		}

		$has_next = $medias['hasNextPage'];
		$max_id = $medias['maxId'];
		$data_result = array( 
			'status' => 'success', 
			'message' => 'All good!', 
			'username' => $username, 
			'count' => count($images), 
			'images' => $images, 
			'has_next' => $has_next, 
			'max_id' => $max_id 
		);
		echo json_encode( $data_result );
		exit;
	}

	/**
	 * Delete instagram data / AJAX
	 */
	public function delete_instagram_data() {
		update_option( 'smpi_instagram_account', '' );
		echo json_encode(array('message' => 'Instagram account deleted sucessfully.'));
		die();
	}

	/**
	 * Just for testing
	 */
	public function test_admin() {
		include 'partials/smpi-admin-display.php';
		exit;
	}

	/**
	 * Import
	 */
	public function smpi_upload_process() {
		$url = $_POST['url'];
		$attach_id = media_sideload_image( $url, 0, null, 'id' );
		/*$response = wp_remote_get($url, array( 'timeout' => 120 ) );
		if( is_wp_error( $response ) ){ exit; }
			
		$bits = wp_remote_retrieve_body( $response );
		$content_type = wp_remote_retrieve_header( $response, 'content-type' );
		if ( $content_type == 'image/jpeg' ) {
			$ext = '.jpg';
		} else {
			$ext = '.png';
		}
		$filename = strtotime("now").'_'.uniqid().$ext;
		
		$last_modified = wp_remote_retrieve_header( $response, 'last-modified' );
		$upload = array(
			'filename' => $filename,
			'file' => $bits,
			'content_type' => $content_type,
			'last_modified' => $last_modified
		);
		$upload = wp_upload_bits( $filename, null, $bits );
		$data['guid'] = $upload['url'];
		$data['post_mime_type'] = $upload['type'];
		$attach_id = wp_insert_attachment( $data, $upload['file'], 0 );
		$upload['attach_id'] = $attach_id;*/

		echo json_encode(array('id' => $attach_id));
		exit;
	}

}
