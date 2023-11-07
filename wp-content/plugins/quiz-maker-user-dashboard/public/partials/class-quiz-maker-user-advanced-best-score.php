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
class Quiz_Maker_User_Dashboard_Best_Score_By_Quiz
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
        User Best Score shortcode
    ==========================================
    */

    public function get_user_max_result( $user_id, $quiz_id, $mode ){
        global $wpdb;

        $reports_table = esc_sql( $wpdb->prefix . "aysquiz_reports" );

        if (is_null($quiz_id) || $quiz_id == 0 ) {
            return null;
        }

        if ($mode == 'Points') {
            $quiz_mode = 'points';
        }else{
            $quiz_mode = 'score';
        }

        $sql = "SELECT MAX(CAST(".$quiz_mode." AS DECIMAL(10) )) AS avg_score
                FROM ". $reports_table ."
                WHERE user_id=". $user_id . " && quiz_id=".$quiz_id;

        $results = $wpdb->get_var($sql);

        return $results;

    }

    public function ays_user_bestscore_html( $user_id, $quiz_id, $mode ){

        $best_score = $this->get_user_max_result( $user_id, $quiz_id, $mode );

        // User bestscore message from admin
        $user_bestscore_message_admin = __( "Your Best  Score is %%best_points%%" , $this->plugin_name);

        // User bestscore message when not result yet
        $user_bestscore_message =  __( "There are no results yet" , $this->plugin_name);

        $content_html = array();

        $content_html[] = "<div class='". $this->html_name_prefix ."user-bestscore-conteiner' id='". $this->html_name_prefix ."user-bestscore-conteiner-". $this->unique_id_in_class ."'>";

        if (is_null($best_score)) {
            $content_html[] = "<div class='". $this->html_name_prefix ."user-bestscore-text'>" . $user_bestscore_message . "</div>";
        }else{
            if ($mode == 'Points') {
                if ( absint($best_score) === 1 || absint($best_score) === 0 ) {
                    $quiz_mode = __( 'point' , $this->plugin_name );
                }else{
                    $quiz_mode = __( 'points' , $this->plugin_name );
                }
            }else{
                $quiz_mode = '%';
            }

            $final_score_message = "<b class='". $this->html_name_prefix ."user-bestscore-text-bold'>". __( 'Your Best  Score is', $this->plugin_name)." ". $best_score ." ". $quiz_mode ."</b>";      

            $content_html[] = "<div class='". $this->html_name_prefix ."user-bestscore-text'>". $final_score_message ."</div>";
        }

        $content_html[] = $this->get_styles();
        $content_html[] = "</div>";

        $content_html = implode( '' , $content_html);

        return $content_html;
    }

    public function get_styles(){
        
        $content = array();
        $content[] = '<style type="text/css">';

        $content[] = "
            #". $this->html_name_prefix ."user-bestscore-conteiner-". $this->unique_id_in_class ." {
                margin: 20px auto;
            }

            #". $this->html_name_prefix ."user-bestscore-conteiner-". $this->unique_id_in_class ." .". $this->html_name_prefix ."user-bestscore-text {
                font-size: 16px;
                letter-spacing: normal;
                line-height: 1.3;
            }

            ";
        
        $content[] = "</style>";

        $content = implode( '', $content );

        return $content;
    }
}
