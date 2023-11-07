<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://ays-pro.com
 * @since      1.0.0
 *
 * @package    Quiz_Maker_Track_Users
 * @subpackage Quiz_Maker_Track_Users/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Quiz_Maker_Track_Users
 * @subpackage Quiz_Maker_Track_Users/admin
 * @author     Quiz Maker team <info@ays-pro.com>
 */
class Quiz_Maker_Track_Users_Admin {

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
	private $user_tracks_obj;

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
	public function enqueue_styles($hook_suffix) {

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
        wp_enqueue_style($this->plugin_name . '-sweetalert-css', QUIZ_MAKER_TRACK_USERS_ADMIN_URL . '/css/quiz-maker-track-users-sweetalert2.min.css', array(), $this->version, 'all');
		
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/quiz-maker-track-users-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts($hook_suffix) {

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

		if (false !== strpos($hook_suffix, "plugins.php")){
            wp_enqueue_script($this->plugin_name . '-sweetalert-js', QUIZ_MAKER_TRACK_USERS_ADMIN_URL . '/js/quiz-maker-track-users-sweetalert2.all.min.js', array('jquery'), $this->version, true );

            wp_enqueue_script($this->plugin_name . '-admin', plugin_dir_url(__FILE__) . 'js/admin.js', array('jquery'), $this->version, true);

            wp_localize_script($this->plugin_name . '-admin',  'quiz_maker_track_users_admin_ajax', array('ajax_url' => admin_url('admin-ajax.php')));
        }

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/quiz-maker-track-users-admin.js', array( 'jquery' ), $this->version, false );

	}

	// public function add_plugin_track_users_submenu(){
    //     $hook_quiz_maker_track_users = add_submenu_page(
	// 		PARENT_QUIZ_MAKER_NAME,
	// 		__('Track Users', $this->plugin_name),
    //         __('Track Users', $this->plugin_name),
    //         'manage_options',
    //         PARENT_QUIZ_MAKER_NAME . "-track-users",
    //         array($this, 'display_add_on_page')
    //     );

    //     add_action("load-$hook_quiz_maker_track_users", array($this, 'screen_option_track_users'));
    // }

	// public function display_add_on_page(){
	// 	include_once('partials/quiz-maker-track-users-admin-display.php');
	// }

	// public function screen_option_track_users(){
    //     $option = 'per_page';
    //     $args = array(
    //         'label' => __('Track Users', $this->plugin_name),
    //         'default' => 20,
    //         'option' => 'track_users_per_page'
    //     );
		
	// 	add_screen_option($option, $args);
	//     $this->user_tracks_obj = new Track_Users_List_Table( $this->plugin_name );
    // }

	public function deactivate_plugin_option_qm_track_users(){
        error_reporting(0);

        $request_value = $_REQUEST['upgrade_plugin'];
        $upgrade_option = get_option('ays_quiz_track_user_upgrade_plugin','');

        if($upgrade_option === ''){
            add_option('ays_quiz_track_user_upgrade_plugin',$request_value);
        }else{
            update_option('ays_quiz_track_user_upgrade_plugin',$request_value);
        }

        ob_end_clean();
        $ob_get_clean = ob_get_clean();
        echo json_encode(
			array(
				'option'=>get_option('ays_quiz_track_user_upgrade_plugin','')
			)
		);
        wp_die();
    }

	public function display_track_user_row($content, $id){
		global $wpdb;

		$quiz_track_users_table = $wpdb->prefix .'aysquiz_track_users';

		$sql = "SELECT * FROM {$quiz_track_users_table} WHERE result_id = ".$id;
		$results = $wpdb->get_row( $sql, 'ARRAY_A' );
		
		//Tab Change Duration
		$tab_change_duration = (isset($results['tab_change_time']) && $results['tab_change_time'] != '') ? intval($results['tab_change_time']) : 0;
		
		//Tab Change Count 
		$tab_change_count = (isset($results['tab_change_count']) && $results['tab_change_count'] != '') ? intval($results['tab_change_count']) : 0;

		//Hint Count 
		$hint_count = (isset($results['hint_count']) && $results['hint_count'] != '') ? intval($results['hint_count']) : 0;

		//Copy Count
		$copy_count = (isset($results['copy_count']) && $results['copy_count'] != '') ? intval($results['copy_count']) : 0;

		$row[] = '<tr class="ays_result_element">';
			$row[] = '<td><h1>' . __('Tracking Information',$this->plugin_name) . '</h1></td>';
		$row[] = '</tr>';
		$row[] = '<tr class="ays_result_element">';
			$row[] = '<td>'.__( 'Tab changing duration by seconds', $this->plugin_name ).'</td>';
			$row[] = '<td colspan="3">'. $tab_change_duration .'s</td>';
		$row[] = '</tr>';
		$row[] = '<tr class="ays_result_element">';
			$row[] = '<td>'. __('Tab changing count', $this->plugin_name ) .'</td>';
			$row[] = '<td colspan="3">'.$tab_change_count.'</td>';
		$row[] = '</tr>';
		$row[] = '<tr class="ays_result_element">';
			$row[] = '<td>'. __('Used hint count', $this->plugin_name) .'</td>';
			$row[] = '<td colspan="3">'.$hint_count.'</td>';
		$row[] = '</tr>';
		$row[] = '<tr class="ays_result_element">';
			$row[] = '<td>'. __( 'Copied text count', $this->plugin_name ) .'</td>';
			$row[] = '<td colspan="3">'.$copy_count.'</td>';
		$row[] = '</tr>';
		
		$row = implode( '', $row );

		$content = $row;
		
		return $content;
	}
}
