<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://ays-pro.com
 * @since      1.0.0
 *
 * @package    Quiz_Maker_Track_Users
 * @subpackage Quiz_Maker_Track_Users/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Quiz_Maker_Track_Users
 * @subpackage Quiz_Maker_Track_Users/includes
 * @author     Quiz Maker team <info@ays-pro.com>
 */
class Quiz_Maker_Track_Users {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Quiz_Maker_Track_Users_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'QUIZ_MAKER_TRACK_USERS_VERSION' ) ) {
			$this->version = QUIZ_MAKER_TRACK_USERS_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'quiz-maker-track-users';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Quiz_Maker_Track_Users_Loader. Orchestrates the hooks of the plugin.
	 * - Quiz_Maker_Track_Users_i18n. Defines internationalization functionality.
	 * - Quiz_Maker_Track_Users_Admin. Defines all hooks for the admin area.
	 * - Quiz_Maker_Track_Users_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {
		if ( ! class_exists( 'WP_List_Table' ) ) {
            require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
        }

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-quiz-maker-track-users-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-quiz-maker-track-users-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-quiz-maker-track-users-admin.php';

		//Track Users List Table
		// require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/lists/class-quiz-maker-track-users-list-table.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-quiz-maker-track-users-public.php';

		$this->loader = new Quiz_Maker_Track_Users_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Quiz_Maker_Track_Users_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Quiz_Maker_Track_Users_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Quiz_Maker_Track_Users_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		// Add menu item
        // $this->loader->add_action( 'admin_menu', $plugin_admin, 'add_plugin_track_users_submenu', 97);

		//Action hook
		//Advanced user dashboard
		$this->loader->add_filter( 'ays_qm_track_users_contents', $plugin_admin, 'display_track_user_row', 1, 2);

		//Deactivate Plugin AJAX 
		$this->loader->add_action( 'wp_ajax_deactivate_plugin_option_qm_track_users', $plugin_admin, 'deactivate_plugin_option_qm_track_users' );
        $this->loader->add_action( 'wp_ajax_nopriv_deactivate_plugin_option_qm_track_users', $plugin_admin, 'deactivate_plugin_option_qm_track_users' );
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Quiz_Maker_Track_Users_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

		//Get Track Users AJAX 
		$this->loader->add_action( 'wp_ajax_get_track_users_hidden_tab', $plugin_public, 'get_track_users_hidden_tab' );
        $this->loader->add_action( 'wp_ajax_nopriv_get_track_users_hidden_tab', $plugin_public, 'get_track_users_hidden_tab' );

		//Get Track Users AJAX 
		$this->loader->add_action( 'wp_ajax_get_track_users_start_quiz_date', $plugin_public, 'get_track_users_start_quiz_date' );
        $this->loader->add_action( 'wp_ajax_nopriv_get_track_users_start_quiz_date', $plugin_public, 'get_track_users_start_quiz_date' );

		//Get Track Users AJAX 
		$this->loader->add_action( 'wp_ajax_get_track_users_end_quiz_date', $plugin_public, 'get_track_users_end_quiz_date' );
        $this->loader->add_action( 'wp_ajax_nopriv_get_track_users_end_quiz_date', $plugin_public, 'get_track_users_end_quiz_date' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Quiz_Maker_Track_Users_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
