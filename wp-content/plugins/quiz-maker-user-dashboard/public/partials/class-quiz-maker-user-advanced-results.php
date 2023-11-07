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
class Quiz_Maker_User_Dashboard_All_Results{

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $plugin_name The ID of this plugin.
     */
    protected $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $version The current version of this plugin.
     */
    private $version;
    
    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string $plugin_name The name of the plugin.
     * @param      string $version The version of this plugin.
     */

    private $html_class_prefix = 'ays-quiz-user-dashboard-';
    private $html_name_prefix = 'ays-quiz-';
    private $name_prefix = 'ays_quiz_';
    private $unique_id;
    private $unique_id_in_class;

    public function __construct($plugin_name, $version){

        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    public function enqueue_styles(){
        wp_enqueue_style($this->plugin_name . '-dataTable-min', QUIZ_MAKER_USER_DASHBOARD_PUBLIC_URL . '/css/user-dashboard-dataTables.min.css', array(), $this->version, 'all');
    }

    public function enqueue_scripts(){
        wp_enqueue_script( $this->plugin_name . '-datatable-min', QUIZ_MAKER_USER_DASHBOARD_PUBLIC_URL . '/js/partials/user-dashboard-datatable.min.js', array('jquery'), $this->version, true);
        
        wp_enqueue_script( $this->plugin_name . '-all-results', QUIZ_MAKER_USER_DASHBOARD_PUBLIC_URL . '/js/partials/user-dashboard-all-results.js', array('jquery'), $this->version, true);

        wp_localize_script( $this->plugin_name . '-datatable-min', 'quizUserDashboardLangDataTableObj', array(
            "sEmptyTable"           => __( "No data available in table", $this->plugin_name ),
            "sInfo"                 => __( "Showing _START_ to _END_ of _TOTAL_ entries", $this->plugin_name ),
            "sInfoEmpty"            => __( "Showing 0 to 0 of 0 entries", $this->plugin_name ),
            "sInfoFiltered"         => __( "(filtered from _MAX_ total entries)", $this->plugin_name ),
            // "sInfoPostFix":          => __( "", $this->plugin_name ),
            // "sInfoThousands":        => __( ",", $this->plugin_name ),
            "sLengthMenu"           => __( "Show _MENU_ entries", $this->plugin_name ),
            "sLoadingRecords"       => __( "Loading...", $this->plugin_name ),
            "sProcessing"           => __( "Processing...", $this->plugin_name ),
            "sSearch"               => __( "Search:", $this->plugin_name ),
            // "sUrl":                  => __( "", $this->plugin_name ),
            "sZeroRecords"          => __( "No matching records found", $this->plugin_name ),
            "sFirst"                => __( "First", $this->plugin_name ),
            "sLast"                 => __( "Last", $this->plugin_name ),
            "sNext"                 => __( "Next", $this->plugin_name ),
            "sPrevious"             => __( "Previous", $this->plugin_name ),
            "sSortAscending"        => __( ": activate to sort column ascending", $this->plugin_name ),
            "sSortDescending"       => __( ": activate to sort column descending", $this->plugin_name ),
        ) );
    }

    public function get_user_reports_info( $user_id ){
        global $wpdb;

        $reports_table = $wpdb->prefix . "aysquiz_reports";
        $quizes_table = $wpdb->prefix . "aysquiz_quizes";
        $sql = "SELECT r.quiz_id, q.title, r.start_date, r.end_date, r.duration, r.score, r.id, r.user_name, r.user_id,
                    TIMESTAMPDIFF(second, r.start_date, r.end_date) AS duration_2
                FROM {$reports_table} AS r
                LEFT JOIN {$quizes_table} AS q
                ON r.quiz_id = q.id
                WHERE r.user_id = {$user_id}
                ORDER BY r.id DESC";
        $results = $wpdb->get_results($sql, "ARRAY_A");
        return $results;

    }

    public function ays_user_dashboard_all_results_html( $user_id ){
        global $wpdb;

        $this->enqueue_styles();
        $this->enqueue_scripts();

        $quizes_table = $wpdb->prefix . "aysquiz_quizes";

        $content = array();

        $user_results = $this->get_user_reports_info( $user_id );

        if($user_results === null){
            $content[] = "<p style='text-align: center;font-style:italic;'>" . __( "You must log in to see your results.", $this->plugin_name ) . "</p>";

            $content = implode( '', $content );
            
            return $content;
        }
        
        if( empty( $user_results ) ){
            $content[] = "<p style='text-align: center;font-style:italic;'>" . __( "There are no results yet.", $this->plugin_name ) . "</p>";

            $content = implode( '', $content );

            return $content;
        }

        // SVG icon | Pass
        $pass_svg = '<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="green"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M9 16.2L4.8 12l-1.4 1.4L9 19 21 7l-1.4-1.4L9 16.2z"/></svg>';

        // SVG icon | Fail
        $fail_svg = '<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="brown"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12 19 6.41z"/></svg>';

        $quiz_pass_score_arr = array();
        $content[] = $this->get_styles();

        $content[] = '<table id="ays_quiz_user_dashboard_all_results">';
            $content[] = '<thead>';
                $content[] = '<tr>';

                    $content[] = '<th>';
                        $content[] = __('User Name', $this->plugin_name);
                    $content[] = '</th>';

                    $content[] = '<th>';
                        $content[] = __('Quiz Name', $this->plugin_name);
                    $content[] = '</th>';

                    $content[] = '<th>';
                        $content[] = __('Start Date', $this->plugin_name);
                    $content[] = '</th>';

                    $content[] = '<th>';
                        $content[] = __('End Date', $this->plugin_name);
                    $content[] = '</th>';

                    $content[] = '<th>';
                        $content[] = __('Duration', $this->plugin_name);
                    $content[] = '</th>';

                    $content[] = '<th>';
                        $content[] = __('Score', $this->plugin_name);
                    $content[] = '</th>';

                    $content[] = '<th>';
                        $content[] = __('Status', $this->plugin_name);
                    $content[] = '</th>';

                $content[] = '</tr>';
            $content[] = '</thead>';
            $content[] = '<tbody>';
                foreach ($user_results as $key => $user_result){
                    //id 
                    $id = isset($user_result['id']) ? $user_result['id'] : null;

                    //quiz_id
                    $quiz_id = isset($user_result['quiz_id']) ? absint($user_result['quiz_id']) : null;

                    //user_id
                    // $user_id = isset($result['user_id']) ? intval($result['user_id']) : 0;

                    $score  = isset($user_result['score']) ? $user_result['score'] : 0;

                    //Quiz Title
                    $quiz_title = (isset($user_result['title']) && $user_result['title'] != '') ? sanitize_text_field(stripslashes($user_result['title'])) : '';

                    //User Name
                    $user_name = (isset($user_result['user_name']) && $user_result['user_name'] != '') ? sanitize_text_field(stripslashes($user_result['user_name'])) : '';
                    
                    //Start Date
                    $start_date = (isset($user_result['start_date']) && $user_result['start_date'] != '') ? $user_result['start_date'] : '';

                    //End Date
                    $end_date = (isset($user_result['end_date']) && $user_result['end_date'] != '') ? $user_result['end_date'] : '';

                    //Duration
                    $quiz_duration = (isset($user_result['duration']) && $user_result['duration'] != '') ? absint($user_result['duration']) : 0;

                    //Score
                    $quiz_score = (isset($user_result['score']) && $user_result['score'] != '') ? absint($user_result['score']) : 0;

                    $start_date_for_ordering = strtotime($user_result['start_date']);
                    $end_date_for_ordering = strtotime($user_result['end_date']);
                    $duration_for_ordering = $quiz_duration;

                    //Check Duration
                    if ($quiz_duration == null) {
                        $quiz_duration = isset($user_result['duration_2']) ? $user_result['duration_2'] : 0;
                    }

                    $quiz_duration = Quiz_Maker_Data::secondsToWords($quiz_duration);
                    if ($quiz_duration == '') {
                        $quiz_duration = '0 ' . __( 'second' , $this->plugin_name );
                    }

                    // Check Logged In User
                    // if ($user_id != 0) {
                        $user = get_userdata( $user_id );
                        if($user !== false){
                            $user_name = $user->data->display_name ? $user->data->display_name : $user->user_login;
                        }else{
                            continue;
                        }
                    // }

                    //Status
                    $status     = '';
                    $pass_score = 0;
                    if ( ! is_null( $quiz_id ) || ! empty( $quiz_id ) ) {
                        if ( ! array_key_exists( $quiz_id , $quiz_pass_score_arr ) ) {

                            $sql = "SELECT options FROM " . $quizes_table . " WHERE id=" . intval( $quiz_id );
                            $quiz_options = $wpdb->get_var( $sql );
                            $quiz_options = $quiz_options != '' ? json_decode( $quiz_options, true ) : array();
                            $pass_score = isset( $quiz_options['pass_score'] ) && $quiz_options['pass_score'] != '' ? absint( $quiz_options['pass_score'] ) : 0;

                            $quiz_pass_score_arr[ $quiz_id ] = $pass_score;

                        } else {
                            $pass_score = ( isset( $quiz_pass_score_arr[ $quiz_id ] ) && $quiz_pass_score_arr[ $quiz_id ] != '' ) ? absint( $quiz_pass_score_arr[ $quiz_id ] ) : 0;
                        }

                        $user_score = absint( $score );


                        $status = array();
                        if( $pass_score != 0 ){
                            if( $user_score >= $pass_score ){
                                $status[] = "<div class='ays-quiz-user-dashboard-score-column-check-box'>";
                                    $status[] = $pass_svg;
                                    $status[] = "<span class='ays-quiz-user-dashboard-score-column-check' style='color:green;'> " . __( "Passed", $this->plugin_name ) . "</span>";
                                $status[] = "</div>";
                            }else{
                                $status[] = "<div class='ays-quiz-user-dashboard-score-column-check-box'>";
                                    $status[] = $fail_svg;
                                    $status[] = "<span class='ays-quiz-user-dashboard-score-column-times' style='color:red;'> " . __( "Failed", $this->plugin_name ) . "</span>";
                                $status[] = "</div>";

                            }
                        }

                        $status = implode('', $status);
                    }

                    //User Data TD
                    $content[] = '<tr>';
                        //User Name
                        $content[] = '<td>';
                            $content[] = $user_name;
                        $content[] = '</td>';

                        //Quiz title
                        $content[] = '<td>';
                            $content[] = $quiz_title;
                        $content[] = '</td>';

                        //Start Date
                        $content[] = '<td data-order="'. $start_date_for_ordering .'">';
                            $content[] = $start_date;
                        $content[] = '</td>';

                        //End Date
                        $content[] = '<td data-order="'. $end_date_for_ordering .'">';
                            $content[] = $end_date;
                        $content[] = '</td>';

                        //Duration
                        $content[] = '<td data-order="'. $duration_for_ordering .'">';
                            $content[] = $quiz_duration;
                        $content[] = '</td>';

                        //Score
                        $content[] = '<td>';
                            $content[] = $quiz_score .'%';
                        $content[] = '</td>';

                        //Status
                        $content[] = '<td>';
                            $content[] = $status;
                        $content[] = '</td>';
                    $content[] = '</tr>';

                }
            $content[] = '</tbody>';
        $content[] = '</table>';
        
        $content = implode( '', $content );

        return $content;
    }

    public function get_styles( ){

        $content = array();

        $default_user_page_column_names = array(
            "user_name" => __( 'User name', $this->plugin_name ),
            "title" => __( 'Quiz name', $this->plugin_name ),
            "start_date" => __( 'Start date', $this->plugin_name ),
            "end_date" => __( 'End date', $this->plugin_name ),
            "duration" => __( 'Duration', $this->plugin_name ),
            "score" => __( 'Score', $this->plugin_name ),
            "status" => __( 'Certificate', $this->plugin_name ),
        );

        $content[] = '<style type="text/css">';

        $content[] = "
            #". $this->html_name_prefix ."user-results-container-". $this->unique_id_in_class ." {
                margin: 20px auto;
            }

            /*
             * User reports info table
             */
            table#ays_quiz_user_dashboard_all_results {
                margin: 0 !important;
            }
            table#ays_quiz_user_dashboard_all_results thead th {
                font-size: 16px;
            }
            table#ays_quiz_user_dashboard_all_results td {
                font-size: 14px;
            }
            table#ays_quiz_user_dashboard_all_results thead th,
            table#ays_quiz_user_dashboard_all_results td {
                word-break: initial !important;
                word-wrap: break-word;
                vertical-align: middle;
                text-align: center;
                white-space: nowrap;
            }

            button.". $this->html_name_prefix ."-details {
                background-color: #222;
                border: none;
                -webkit-border-radius: 4px;
                border-radius: 4px;
                -webkit-box-shadow: none;
                box-shadow: none;
                color: #fff;
                cursor: pointer;
                display: inline-block;
                font-size: 14px;
                line-height: 1;
                padding: 13px 25px;
                text-shadow: none;
                -webkit-transition: background 0.2s;
                transition: background 0.2s;
                white-space: nowrap;
                margin: auto;
                display: block;
            }

            button.". $this->html_name_prefix ."-details:hover{
                background-color: #aaaaaaab;
                color: #222;
            }
            td.". $this->html_name_prefix ."duration-column,
            td.". $this->html_name_prefix ."score-column {
                text-align: center;
            }

            .". $this->html_name_prefix ."user-results-container {
                background-color: #F6F8EF;
                overflow-x: auto;
            }

            table#ays_quiz_user_dashboard_all_results {
                width: 100%;
                border-collapse: collapse;
            }

            /* Zebra striping */
            table#ays_quiz_user_dashboard_all_results tr:nth-of-type(odd) {
                background: #eee;
            }

            table#ays_quiz_user_dashboard_all_results th {
                color:  #333;
                font-weight: bold;
            }

            table#ays_quiz_user_dashboard_all_results td,
            table#ays_quiz_user_dashboard_all_results th {
                /*padding: 6px;*/
                border: 1px solid #ccc;
            }

            /* All Results table export to pdf button */
            table#ays-results-table tr td:first-child div#quiz-export-pdf-". $this->unique_id ." button[class='". $this->html_name_prefix ."-export-pdf']{
                background: #007cba;
                border-color: #007cba;
                color: #fff !important;
                text-decoration: none;
                text-shadow: none;
                display: inline-block;
                margin-left: 5px;
                margin-right: 5px;
                vertical-align: baseline;
                font-size: 13px;
                line-height: 1;
                min-height: 30px;
                margin: 0;
                padding: 0 10px;
                cursor: pointer;
                border-width: 1px;
                border-style: solid;
                -webkit-appearance: none;
                border-radius: 3px;
                white-space: nowrap;
                box-sizing: border-box;
            }

            table#ays-results-table tr td:first-child span[class='ays-pdf-export-text']{
                padding: 0 5px;
            }

            table#ays-results-table tr td:first-child div#quiz-export-pdf-". $this->unique_id ." button[class='". $this->html_name_prefix ."-export-pdf']:hover{
                background: #0071a1;
                border-color: #0071a1;
                color: #fff !important;
            }
            /**/

            @media only screen and (max-width: 760px), (min-device-width: 768px) and (max-device-width: 1024px)  {

                /* Force table to not be like tables anymore */
                table#table#ays_quiz_user_dashboard_all_results,
                table#ays_quiz_user_dashboard_all_results thead,
                table#ays_quiz_user_dashboard_all_results tbody,
                table#ays_quiz_user_dashboard_all_results th,
                table#ays_quiz_user_dashboard_all_results td,
                table#ays_quiz_user_dashboard_all_results tr {
                    display: block;
                }

                /* Hide table headers (but not display: none;, for accessibility) */
                table#ays_quiz_user_dashboard_all_results thead tr {
                    position: absolute;
                    top: -9999px;
                    left: -9999px;
                }

                table#ays_quiz_user_dashboard_all_results tr { border: 1px solid #ccc; }

                table#ays_quiz_user_dashboard_all_results td {
                    /* Behave  like a row */
                    border: none;
                    border-bottom: 1px solid #eee;
                    position: relative;
                    padding-left: 50%;
                    text-align: left;
                }

                table#ays_quiz_user_dashboard_all_results td:before {
                    /* Now like a table header */
                    position: absolute;
                    /* Top/left values mimic padding */
                    top: 6px;
                    left: 6px;
                    width: 45%;
                    padding-right: 10px;
                    white-space: nowrap;
                }

                table#ays_quiz_user_dashboard_all_results td:empty { display: none !important; }
                table#ays_quiz_user_dashboard_all_results td.". $this->html_name_prefix ."name-column:before { content: '" . $default_user_page_column_names['user_name'] . "'; }
                table#ays_quiz_user_dashboard_all_results td.". $this->html_name_prefix ."name-column:before { content: '" . $default_user_page_column_names['title'] . "'; }
                table#ays_quiz_user_dashboard_all_results td.". $this->html_name_prefix ."start-date-column:before { content: '" . $default_user_page_column_names['start_date'] . "'; }
                table#ays_quiz_user_dashboard_all_results td.". $this->html_name_prefix ."end-date-column:before { content: '" . $default_user_page_column_names['end_date'] . "'; }
                table#ays_quiz_user_dashboard_all_results td.". $this->html_name_prefix ."duration-column:before { content: '" . $default_user_page_column_names['duration'] . "'; }
                table#ays_quiz_user_dashboard_all_results td.". $this->html_name_prefix ."score-column:before { content: '" . $default_user_page_column_names['score'] . "'; }
                table#ays_quiz_user_dashboard_all_results td.". $this->html_name_prefix ."score-column:before { content: '" . $default_user_page_column_names['status'] . "'; }
            }

            ";
        
        $content[] = "</style>";

        $content = implode( '', $content );

        return $content;
    }
}
