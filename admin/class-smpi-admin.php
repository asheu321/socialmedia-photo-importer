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
			'ajaxUrl' => admin_url('admin-ajax.php')
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
	 * Save instagram username / Ajax Callback
	 */
	public function save_instagram_username() {
		parse_str( $_POST['data'], $var );
		$username = isset($var['instagram-username']) && $var['instagram-username'] != '' ? $var['instagram-username'] : false;

		die();
	}

	/**
	 * Validate instagram username / ajax callback
	 */
	public function validate_instagram_username() {
		$username = isset($_POST['username']) && $_POST['username'] != '' ? $_POST['username'] : false;
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
	 * Instagran: Ajax submit form callback
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
	 * Just for testing
	 */
	public function test_admin() {
		include 'partials/smpi-admin-display.php';
		exit;
	}

}
