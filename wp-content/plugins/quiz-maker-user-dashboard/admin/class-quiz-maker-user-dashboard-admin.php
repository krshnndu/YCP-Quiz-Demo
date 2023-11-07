<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://ays-pro.com
 * @since      1.0.0
 *
 * @package    Quiz_Maker_User_Dashboard
 * @subpackage Quiz_Maker_User_Dashboard/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Quiz_Maker_User_Dashboard
 * @subpackage Quiz_Maker_User_Dashboard/admin
 * @author     Quiz Maker team <info@ays-pro.com>
 */
class Quiz_Maker_User_Dashboard_Admin {

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
		 * defined in Quiz_Maker_User_Dashboard_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Quiz_Maker_User_Dashboard_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/quiz-maker-user-dashboard-admin.css', array(), $this->version, 'all' );

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
		 * defined in Quiz_Maker_User_Dashboard_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Quiz_Maker_User_Dashboard_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/quiz-maker-user-dashboard-admin.js', array( 'jquery' ), $this->version, false );

	}

		// Shorocde quiz page action hook
		public function ays_qm_settings_page_advanced_user_dashboard($args){

			$shortcodes_contents = apply_filters( 'ays_qm_settings_page_advanced_user_dashboard_contents', array(), $args );
			$shortcodes = array();

			foreach ($shortcodes_contents as $key => $shortcodes_content) {
				$content = '<fieldset>';
				if(isset($shortcodes_content['title'])){
					$content .= '<legend>';
					if(isset($shortcodes_content['icon'])){
						$content .= '<strong style="font-size:30px;">'. $shortcodes_content['icon'] .'</strong>';
					}
					$content .= '<h5 style="margin: 0 4px;">'. $shortcodes_content['title'] .'</h5></legend>';
				}
				$content .= $shortcodes_content['content'];
				
				$content .= '</fieldset>';
				
				$shortcodes[] = $content;
			}
			
			echo implode('<hr/>', $shortcodes);
		}
		
		////////////////////////////////////////////////////////////////////////////////////////
		//====================================================================================//
		////////////////////////////////////////////////////////////////////////////////////////
	
	
		/*
		==========================================
			Advanced User Dashboard | Start
		==========================================
		*/

		public static function ays_get_setting( $meta_key ){
			global $wpdb;
	
			$settings_table  = esc_sql( $wpdb->prefix . "aysquiz_settings" );
	
			$sql = "SELECT meta_value FROM ".$settings_table." WHERE meta_key = '".$meta_key."'";
			$result = $wpdb->get_var($sql);
	
			if($result != ""){
				return $result;
			}
			return false;
		}
	
		public function ays_settings_page_advanced_user_dashboard($shortcodes){
			$icon  = '[ ]';
			$title = __( 'Advanced User Dashboard Settings', $this->plugin_name );
	
			$shortcode_str = esc_attr( '[ays_quiz_advanced_user_dashboard]' );
	
			$content = array();
	
			$content[] = '<div class="form-group row" style="padding:0px;margin:0;">';
				$content[] = '<div class="col-sm-12" style="padding:20px;">';
					$content[] = '<div class="form-group row">';
	
						$content[] = '<div class="col-sm-4">';
							$content[] = '<label for="ays_advanced_user_dashboard">';
								$content[] = __( "Shortcode", $this->plugin_name );
							$content[] = '</label>';
						$content[] = '</div>';
	
						$content[] = '<div class="col-sm-8">';
							$content[] = '<input type="text" id="ays_advanced_user_dashboard" class="ays-text-input" onclick="this.setSelectionRange(0, this.value.length)" readonly="" value="'. $shortcode_str .'">';
						$content[] = '</div>';

					$content[] = '</div>';

					$content[] = '<hr>';

					$content[] = '<blockquote>';
						$content[] = '<ul class="ays-quiz-general-settings-blockquote-ul">';

							$content[] = '<li>';
								$content[] = __( 'Copy the shortcode and insert it into any post. It will show all the activities of the user (Activity per Day, User Results, The Results by the quiz, Best Score, User Progress).', $this->plugin_name );
							$content[] = '</li>';

						$content[] = '</ul>';

	            	$content[] = '</blockquote>';
				$content[] = '</div>';
			$content[] = '</div>';
	
			$content = implode( '', $content );
	
			$shortcodes['advanced_user_dashboard'] = array(
				'content' => $content,
				'icon'    => $icon,
				'title'   => $title,
			);
			return $shortcodes;
		}
}
