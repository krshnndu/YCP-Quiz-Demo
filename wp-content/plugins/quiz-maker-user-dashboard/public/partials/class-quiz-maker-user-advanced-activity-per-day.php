<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://ays-pro.com/
 * @since      1.0.0
 *
 * @package    Quiz_Maker
 * @subpackage Quiz_Maker/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Quiz_Maker
 * @subpackage Quiz_Maker/public
 * @author     AYS Pro LLC <info@ays-pro.com>
 */

class User_Advanced_Dashboard_User_Activity_Per_Day{

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
    private $html_class_prefix = 'ays-user-dashboard-';
    private $html_name_prefix = 'ays-quiz-';
    private $name_prefix = 'ays_quiz_';
    private $unique_id;
    private $unique_id_in_class;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */

    public function __construct($plugin_name, $version){
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
         * defined in Survey_Maker_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Survey_Maker_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

    }

    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts(){

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Survey_Maker_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Survey_Maker_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        
        wp_enqueue_script( $this->plugin_name . '-user-activity-per-day-google-chart', QUIZ_MAKER_USER_DASHBOARD_PUBLIC_URL . '/js/partials/google-chart.js', array('jquery'), $this->version, true);
        wp_enqueue_script( $this->plugin_name . '-user-activity-per-day', QUIZ_MAKER_USER_DASHBOARD_PUBLIC_URL . '/js/partials/user-dashboard-user-activity-per-day.js', array('jquery'), $this->version, true);

        wp_localize_script( $this->plugin_name . '-user-activity-per-day', 'AysQuizPageQuizPerDayLangObj', array(
            'count' => __( 'Count', $this->plugin_name ),
            'date'  => __( 'Date', $this->plugin_name ),
        ) );
    }

    /*
    ==========================================
        User Activite Per Day shortcode
    ==========================================
    */

    public function get_user_reports_info( $id ){
        global $wpdb;

        $reports_table = esc_sql( $wpdb->prefix . "aysquiz_reports" );

        if( is_null( $id ) && $id == 0){
            return null;
        }

        $sql = "SELECT DATE(`end_date`) AS date, COUNT(*) AS value FROM `{$reports_table}` WHERE `user_id` = ".$id." GROUP BY date";

        $result = $wpdb->get_results( $sql, 'ARRAY_A' );

        foreach ($result as $key => $value) {
            $value['value'] = intval($value['value']);
            $value = array_values($value);
        }

        return $result;

    }

    public function ays_user_activity_per_day_html( $id ){

        $this->enqueue_scripts();


        $unique_id = uniqid();

        $this->unique_id = $unique_id;
        $this->unique_id_in_class = $id . "-" . $unique_id;

        $results = array();
        $content = array();
        $obj     = array();

        if ( ! is_null( $id ) && $id > 0 ) {
            $results = $this->get_user_reports_info( $id );
        }

        if ( is_null( $results ) || empty($results) ) {
            $content = '';
            return $content;
        }

        foreach ($results as $key => $result) {
            $r_val  = ( isset( $result['value'] ) && $result['value'] != '' ) ? absint( $result['value'] ) : 0;
            $r_date = ( isset( $result['date'] ) && $result['date'] != '' ) ? sanitize_text_field( $result['date'] ) : null;

            if ( is_null( $r_date ) ) {
               continue;
            }

            $obj[] = array(
                $r_date, $r_val
            );
        }

        $script = '<script type="text/javascript">';
        $script .= "
                if(typeof aysQuizPublicActivityPerDayData === 'undefined'){
                    var aysQuizPublicActivityPerDayData = [];
                }
                aysQuizPublicActivityPerDayData['" . $this->unique_id . "']  = '" . base64_encode( json_encode( $obj ) ) . "';";
        $script .= '</script>';


        $content[] = '<div class="'. $this->html_class_prefix .'user-activity-per-day-container" id="'. $this->html_class_prefix .'user-activity-per-day-container-' . $this->unique_id_in_class . '" data-id="' . $this->unique_id . '" style="margin: 20px auto;">';

            $content[] = '<div class="'. $this->html_class_prefix .'user-activity-per-day-box" id="'. $this->html_class_prefix . 'user-activity-per-day-chart-'. $this->unique_id .'"></div>';
            $content[] = $script;

        $content[] = '</div>';

        $content = implode( '', $content );
        
        return $content;
    }
}