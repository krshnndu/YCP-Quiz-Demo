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
class Quiz_Maker_User_Dashboard_User_Progress_By_Quiz
{

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $plugin_name The ID of this plugin.
     */
    private $plugin_name;

    private $version;
    private $html_class_prefix = 'ays-quiz-user-dashboard-';
    private $html_name_prefix = 'ays-quiz-';
    private $name_prefix = 'ays_quiz_';
    private $unique_id;
    private $unique_id_in_class;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string $plugin_name The name of the plugin.
     * @param      string $version The version of this plugin.
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
        
    }


    /*
    ==========================================
    User Progress shortcode
    ==========================================
    */

    public function get_user_reports_info( $user_id, $quiz_id ){
        global $wpdb;

        $quizes_table  = esc_sql( $wpdb->prefix . "aysquiz_quizes" );
        $reports_table = esc_sql( $wpdb->prefix . "aysquiz_reports" );

        if($user_id == 0){
            return null;
        }

        if ( is_null( $quiz_id ) || $quiz_id == 0 ) {
            return null;
        }

        $sql = "SELECT r.score AS Score, r.points AS Points
                FROM $reports_table AS r
                WHERE r.user_id = {$user_id} && r.quiz_id = {$quiz_id}
                ORDER BY r.id ASC";

        $results = $wpdb->get_results($sql, "ARRAY_A");

        return $results;

    }

    public function ays_user_progress_html( $user_id, $quiz_id, $progress_mode, $unique_id ){
        $this->unique_id = $unique_id;

        $results = array();
        $content = array();
        $obj     = array();

        if ( ! is_null( $quiz_id ) && $quiz_id > 0 ) {
            $results = $this->get_user_reports_info( $user_id, $quiz_id );
        }

        if ( is_null( $results ) || empty($results)) {
            $content = '';

            return $content;
        }

        switch ($progress_mode) {
            case 'Points':
                $mode = __( "Points", $this->plugin_name );
                break;
            case 'Score':
            default:
                $mode = __( "Score", $this->plugin_name );
                break;
        }

        foreach ($results as $key => $result) {
            $val =  ( isset( $result[$mode] ) && $result[$mode] != '' ) ? absint( $result[$mode] ) : null;

            if ( is_null( $val ) ) {
               continue;
            }

            $obj[] = array(
                ($key+1), $val //, '<div style="min-width:80px;padding:10px;"><div>Attempt: '.($key+1).'</div><div>Score: '. $val .'%</div></div>'
            );
        }

        $script = '<script type="text/javascript">';
        $script .= "
                if(typeof aysQuizPublicUserProgressData === 'undefined'){
                    var aysQuizPublicUserProgressData = [];
                }
                aysQuizPublicUserProgressData['" . $this->unique_id . "']  = '" . base64_encode( json_encode( $obj ) ) . "';";
        $script .= '</script>';


        $content[] = '<div class="'. $this->html_class_prefix .'user-progress-container" id="'. $this->html_class_prefix .'user-progress-container-' . $this->unique_id_in_class . '" data-id="' . $this->unique_id . '" data-mode="'. $mode .'" style="margin: 20px auto;">';

            $content[] = '<div class="'. $this->html_class_prefix .'user-progress-box" id="'. $this->html_class_prefix . 'user-progress-chart-'. $mode .'-'. $this->unique_id .'"></div>';
            $content[] = $script;

        $content[] = '</div>';

        $content = implode( '', $content );

        return $content;
    }

}
