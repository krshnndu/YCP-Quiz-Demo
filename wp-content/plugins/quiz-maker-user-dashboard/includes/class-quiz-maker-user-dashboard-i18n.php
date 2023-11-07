<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://ays-pro.com
 * @since      1.0.0
 *
 * @package    Quiz_Maker_User_Dashboard
 * @subpackage Quiz_Maker_User_Dashboard/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Quiz_Maker_User_Dashboard
 * @subpackage Quiz_Maker_User_Dashboard/includes
 * @author     Quiz Maker team <info@ays-pro.com>
 */
class Quiz_Maker_User_Dashboard_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'quiz-maker-user-dashboard',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
