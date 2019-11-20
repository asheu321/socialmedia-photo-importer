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
		$screen = get_current_screen();
		if ( is_object($screen) && $screen->id == 'settings_page_smpi_settings' ) {
			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/smpi-admin.js', array( 'jquery' ), $this->version, false );
		}
		
		wp_localize_script( $this->plugin_name, 'SMPIAdminJs', array(
			'ajaxUrl' => admin_url('admin-ajax.php'),
			'current_screen' => get_current_screen()
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
	 */
	public function instagram_submit_form() {
		$username 	= $_POST['username'];
		$has_next 	= $_POST['has_next'];
		$max_id 	= $_POST['max_id'];

		require_once SMPI_LIB_DIR . 'InstagramMediaScraper/vendor/autoload.php';

		require_once SMPI_LIB_DIR . 'InstagramMediaScraper/InstagramScraper.php';

		$instagram = new \InstagramScraper\Instagram();

		if ($has_next == 'true') {

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
		
		echo json_encode( array( 'status' => 'success', 'message' => 'All good!', 'username' => $username, 'count' => count($images), 'images' => $images, 'has_next' => $has_next, 'max_id' => $max_id ) );
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

}
