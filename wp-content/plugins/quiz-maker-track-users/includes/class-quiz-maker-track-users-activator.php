<?php
global $ays_quiz_track_user_db_version;
$ays_quiz_track_user_db_version = '1.0.0';

/**
 * Fired during plugin activation
 *
 * @link       https://ays-pro.com
 * @since      1.0.0
 *
 * @package    Quiz_Maker_Track_Users
 * @subpackage Quiz_Maker_Track_Users/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Quiz_Maker_Track_Users
 * @subpackage Quiz_Maker_Track_Users/includes
 * @author     Quiz Maker team <info@ays-pro.com>
 */
class Quiz_Maker_Track_Users_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		global $wpdb;
        global $ays_quiz_track_user_db_version;

		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

		$installed_ver = get_option( "ays_quiz_track_user_db_version" );
		$quiz_track_users_list_table = $wpdb->prefix . 'aysquiz_track_users';
		$charset_collate = $wpdb->get_charset_collate();

		if($installed_ver != $ays_quiz_track_user_db_version){
			
            $sql="CREATE TABLE `".$quiz_track_users_list_table."` (
                `id` INT(16) UNSIGNED NOT NULL AUTO_INCREMENT,
                `user_id` INT(11) NOT NULL,
                `quiz_id` INT(11) NOT NULL,
                `result_id` INT(11) NOT NULL,
                `user_ip` VARCHAR(256) NOT NULL,
                `start_date` DATETIME NOT NULL,
                `end_date` DATETIME NOT NULL,
                `tab_change_time` INT(11) NOT NULL,
                `tab_change_count` INT(11) NOT NULL,
                `hint_count` INT(11) NOT NULL,
                `copy_count` INT(11) NOT NULL,
                PRIMARY KEY (`id`)
            )$charset_collate;";

            $sql_schema = "SELECT * FROM INFORMATION_SCHEMA.TABLES
                           WHERE table_schema = '".DB_NAME."' AND table_name = '".$quiz_track_users_list_table."' ";
            $results = $wpdb->get_results($sql_schema);

            if(empty($results)){
                $wpdb->query( $sql );
            }else{
                dbDelta( $sql );
            }
		}
	}

	public static function ays_quiz_track_user_update_db_check() {
        global $ays_quiz_track_user_db_version;
        if ( get_site_option( 'ays_quiz_track_user_db_version' ) != $ays_quiz_track_user_db_version ) {
            self::activate();
            self::create_certificates_folder();
        }
    }

}
