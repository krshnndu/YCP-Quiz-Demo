<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://ays-pro.com
 * @since      1.0.0
 *
 * @package    Quiz_Maker_Track_Users
 * @subpackage Quiz_Maker_Track_Users/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Quiz_Maker_Track_Users
 * @subpackage Quiz_Maker_Track_Users/public
 * @author     Quiz Maker team <info@ays-pro.com>
 */
class Quiz_Maker_Track_Users_Public {

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
	private $html_class_prefix = 'ays-quiz-track-users-';
    private $html_name_prefix = 'ays-quiz-';
    private $name_prefix = 'ays_quiz_';

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Quiz_Maker_Track_Users_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Quiz_Maker_Track_Users_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/quiz-maker-track-users-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Quiz_Maker_Track_Users_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Quiz_Maker_Track_Users_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/quiz-maker-track-users-public.js', array( 'jquery' ), $this->version, false );

		wp_enqueue_script( $this->plugin_name.'-ajax', plugin_dir_url( __FILE__ ) . 'js/quiz-maker-track-users-ajax.js', array( 'jquery' ), $this->version, false );

		wp_localize_script($this->plugin_name . '-ajax',  'quiz_maker_track_users_public_ajax', array('ajax_url' => admin_url('admin-ajax.php')));
	}

	public function get_track_users_hidden_tab(){
		error_reporting(0);

		if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'get_track_users_hidden_tab'){
			$hidden_time = (isset($_REQUEST['visibility']) && $_REQUEST['visibility'] != '') ? 
			absint(intval($_REQUEST['visibility'])) : 0;
	
			if($hidden_time != 0){
				$hidden_time_to_seconds = $hidden_time / 1000;
			}

			ob_end_clean();
			$ob_get_clean = ob_get_clean();
			echo json_encode(array(
				"status" => true,
				"result" => $hidden_time_to_seconds,
			));
			wp_die();
		}else{
			echo json_encode(array(
                "status" => false,
                "result" => '',
            ));
            wp_die();
		}
	}

	public function get_track_users_start_quiz_date(){
		error_reporting(0);

		if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'get_track_users_start_quiz_date'){
			global $wpdb;
			$quiz_track_users_table = $wpdb->prefix .'aysquiz_track_users';

			$quiz_Id = (isset($_REQUEST['quiz_Id']) && $_REQUEST['quiz_Id'] != '') ? intval($_REQUEST['quiz_Id']) : null;

			if($quiz_Id == null){
				return;
			}

			$start_date = (isset($_REQUEST['start_date']) && $_REQUEST['start_date'] != '') ? $_REQUEST['start_date'] : '';
			
			$user_ip = Quiz_Maker_Data::get_user_ip();
			
			$quiz_track_users = $wpdb->insert(
				$quiz_track_users_table,
				array(
					'user_id'           => get_current_user_id(),
					'quiz_id'           => $quiz_Id,
					'user_ip'       	=> $user_ip,
					'start_date'        => $start_date,
				),
				array(
					'%d', // user_id
					'%d', // quiz_id
					'%s', // user_ip
					'%s', // start_date
					)
				);
				
				$last_insert_id = $wpdb->insert_id;			
				
				ob_end_clean();
				$ob_get_clean = ob_get_clean();
				echo json_encode(array(
					"status" => true,
					"last_inserted_id" => $last_insert_id,
					"quiz_id" => $quiz_Id,
				));
				wp_die();
		}else{
				echo json_encode(array(
					"status" => false,
				));
				wp_die();
		}
	}


	public function get_track_users_end_quiz_date(){
		error_reporting(0);
		
		if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'get_track_users_end_quiz_date'){
			global $wpdb;
			$quiz_track_users_table = $wpdb->prefix .'aysquiz_track_users';
			
			//Quiz ID 
			$quiz_Id = (isset($_REQUEST['quiz_Id']) && $_REQUEST['quiz_Id'] != '') ? intval($_REQUEST['quiz_Id']) : null;
			
			if($quiz_Id === null){
				return;
			}
			
			//result_id 
			$result_id = (isset($_REQUEST['result_id']) && $_REQUEST['result_id'] != '') ? intval($_REQUEST['result_id']) : null;

			if ($result_id === null) {
				return;
			}

			//End Date 
			$end_date = (isset($_REQUEST['end_date']) && $_REQUEST['end_date'] != '') ? $_REQUEST['end_date'] : '';

			//hidden tab change
			$hidden_tab = (isset($_REQUEST['hidden_tab']) && $_REQUEST['hidden_tab'] != '') ? $_REQUEST['hidden_tab'] : 0;
		
			//tab change count
			$tab_change_count = (isset($_REQUEST['hidden_tab_count']) && $_REQUEST['hidden_tab_count'] != '') ? $_REQUEST['hidden_tab_count'] : 0;

			//Copy Count
			$copy_count = (isset($_REQUEST['copy_count']) && $_REQUEST['copy_count'] != '') ? $_REQUEST['copy_count'] : 0;

			//hint Count
			$hint_count = (isset($_REQUEST['hint_count']) && $_REQUEST['hint_count'] != '') ? $_REQUEST['hint_count'] : 0;
		
			$sql = "SELECT MAX(id) FROM {$quiz_track_users_table} WHERE quiz_id=".$quiz_Id;
			$id = $wpdb->get_var($sql);

			$quiz_track_users_result = $wpdb->update(
				$quiz_track_users_table,
				array(
					'end_date' 		   => $end_date,
					'tab_change_time'  => $hidden_tab,
					'tab_change_count' => $tab_change_count,
					'copy_count' 	   => $copy_count,
					'hint_count' 	   => $hint_count,
					'result_id' 	   => $result_id,
				),
				array( 'id' => intval($id) ),
				array( '%s'), //end date
				array( '%d'), //tab change time
				array( '%d'), //tab change count
				array( '%d'), //copy count
				array( '%d'), //hint count
				array( '%d'), //result_id
				array( '%d' ) //ID
			);

			ob_end_clean();
			$ob_get_clean = ob_get_clean();
			echo json_encode(array(
				"status" => true,
			));
			wp_die();
		}else{
			echo json_encode(array(
				"status" => false,
			));
			wp_die();
		}
	}
}
