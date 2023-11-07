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
 * @package    Quiz_Maker_Track_Users
 * @subpackage Quiz_Maker_Track_Users/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Quiz_Maker_Track_Users
 * @subpackage Quiz_Maker_Track_Users/includes
 * @author     Quiz Maker team <info@ays-pro.com>
 */
class Quiz_Maker_Track_Users_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'quiz-maker-track-users',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
