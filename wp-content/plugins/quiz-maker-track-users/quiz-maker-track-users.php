<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://ays-pro.com
 * @since             1.0.0
 * @package           Quiz_Maker_Track_Users
 *
 * @wordpress-plugin
 * Plugin Name:       Quiz Maker Add-on - Track users
 * Plugin URI:        https://ays-pro.com/track-users-addon
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Quiz Maker team
 * Author URI:        https://ays-pro.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       quiz-maker-track-users
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if( in_array('quiz-maker/quiz-maker.php', apply_filters('active_plugins', get_option('active_plugins')))){

	if ( ! defined( 'PARENT_QUIZ_MAKER_VERSION' ) ) {
		$quiz_db_version = get_option('ays_quiz_db_version', null);
		if($quiz_db_version !== null){
			$quiz_version_parts = explode('.', $quiz_db_version);
			if(!empty($quiz_version_parts)){
				$quiz_version = intval($quiz_version_parts[0]);
			}
			if($quiz_version < 7){
				define( 'PARENT_QUIZ_MAKER_VERSION', 'free' );
			}else if($quiz_version >= 7 && $quiz_version < 20){
				define( 'PARENT_QUIZ_MAKER_VERSION', 'pro' );
			}else if($quiz_version >= 20){
				define( 'PARENT_QUIZ_MAKER_VERSION', 'dev' );
			}
		}
	}

	/**
	 * Currently plugin version.
	 * Start at version 1.0.0 and use SemVer - https://semver.org
	 * Rename this for your plugin and update it as you release new versions.
	 */
	define( 'QUIZ_MAKER_TRACK_USERS_VERSION', '1.0.0' );
	define( 'QUIZ_MAKER_TRACK_USERS_NAME_VERSION', '1.0.0'  );
	define( 'QUIZ_MAKER_TRACK_USERS_NAME', 'quiz-maker-track-users' );

	if( ! defined( 'QUIZ_MAKER_TRACK_USERS_BASENAME' ) )
		define( 'QUIZ_MAKER_TRACK_USERS_BASENAME', plugin_basename( __FILE__ ) );

	if( ! defined( 'QUIZ_MAKER_TRACK_USERS_DIR' ) )
		define( 'QUIZ_MAKER_TRACK_USERS_DIR', plugin_dir_path( __FILE__ ) );

	if( ! defined( 'QUIZ_MAKER_TRACK_USERS_BASE_URL' ) ) {
		define( 'QUIZ_MAKER_TRACK_USERS_BASE_URL', plugin_dir_url(__FILE__ ) );
	}
	if( ! defined( 'QUIZ_MAKER_TRACK_USERS_ADMIN_PATH' ) )
		define( 'QUIZ_MAKER_TRACK_USERS_ADMIN_PATH', plugin_dir_path( __FILE__ ) . 'admin' );

	if( ! defined( 'QUIZ_MAKER_TRACK_USERS_ADMIN_URL' ) )
		define( 'QUIZ_MAKER_TRACK_USERS_ADMIN_URL', plugin_dir_url( __FILE__ ) . 'admin' );

	if( ! defined( 'QUIZ_MAKER_TRACK_USERS_PUBLIC_PATH' ) )
		define( 'QUIZ_MAKER_TRACK_USERS_PUBLIC_PATH', plugin_dir_path( __FILE__ ) . 'public' );

	if( ! defined( 'QUIZ_MAKER_TRACK_USERS_PUBLIC_URL' ) )
		define( 'QUIZ_MAKER_TRACK_USERS_PUBLIC_URL', plugin_dir_url( __FILE__ ) . 'public' );

	/**
	 * The code that runs during plugin activation.
	 * This action is documented in includes/class-quiz-maker-track-users-activator.php
	 */
	function activate_quiz_maker_track_users() {
		require_once plugin_dir_path( __FILE__ ) . 'includes/class-quiz-maker-track-users-activator.php';
		Quiz_Maker_Track_Users_Activator::activate();
	}

	/**
	 * The code that runs during plugin deactivation.
	 * This action is documented in includes/class-quiz-maker-track-users-deactivator.php
	 */
	function deactivate_quiz_maker_track_users() {
		require_once plugin_dir_path( __FILE__ ) . 'includes/class-quiz-maker-track-users-deactivator.php';
		Quiz_Maker_Track_Users_Deactivator::deactivate();
	}

	register_activation_hook( __FILE__, 'activate_quiz_maker_track_users' );
	register_deactivation_hook( __FILE__, 'deactivate_quiz_maker_track_users' );

	/**
	 * The core plugin class that is used to define internationalization,
	 * admin-specific hooks, and public-facing site hooks.
	 */
	require plugin_dir_path( __FILE__ ) . 'includes/class-quiz-maker-track-users.php';

	/**
	 * Begins execution of the plugin.
	 *
	 * Since everything within the plugin is registered via hooks,
	 * then kicking off the plugin from this point in the file does
	 * not affect the page life cycle.
	 *
	 * @since    1.0.0
	 */
	function run_quiz_maker_track_users() {

		$plugin = new Quiz_Maker_Track_Users();
		$plugin->run();

	}
	run_quiz_maker_track_users();
}