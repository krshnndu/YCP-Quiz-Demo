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
class Quiz_Maker_User_Dashboard_Results_By_Quiz{

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

    public function enqueue_styles(){
    }

    public function enqueue_scripts(){
        // wp_enqueue_script( $this->plugin_name . '-user-page', QUIZ_MAKER_USER_DASHBOARD_PUBLIC_URL . '/js/partials/user-dashboard-user-page.js', array('jquery'), time(), true );

        // wp_localize_script( $this->plugin_name . '-user-page', 'user_dashboard_user_page_ajax', array(
        //     'ajax_url' => admin_url('admin-ajax.php'),
        // ));
    }

    /*
    ==========================================
        User History Quiz shortcode
    ==========================================
    */
    
    public function get_results_by_quiz( $user_id, $quiz_id ){
        global $wpdb;
        
        $reports_table = esc_sql( $wpdb->prefix . "aysquiz_reports" );
        $quizzes_table  = esc_sql( $wpdb->prefix . "aysquiz_quizes" );

        if($quiz_id == 0){
            return null;
        }
        
        if (is_null($user_id) || $user_id == 0 ) {
            return null;
        }
            
        $sql = "SELECT q.title, r.start_date, r.end_date, r.duration, r.score, r.id, r.points, r.options
            FROM {$reports_table} AS r
            LEFT JOIN {$quizzes_table} AS q
            ON r.quiz_id = q.id
            WHERE r.user_id = {$user_id} 
            AND   r.quiz_id = {$quiz_id}
            ORDER BY r.id DESC";
        $results = $wpdb->get_results($sql, "ARRAY_A");
        
        return $results;
    }

    public function user_results_by_quiz_html( $user_id, $quiz_id, $unique_id){
        $this->unique_id = $unique_id;
        $this->unique_id_in_class = $user_id . "-" . $unique_id;

        $results = $this->get_results_by_quiz( $user_id, $quiz_id );

        if($results === null){
            $content[] = "<p style='text-align: center;font-style:italic;'>" . __( "You must log in to see your results.", $this->plugin_name ) . "</p>";

            $content = implode( '', $content );
            
            return $content;
        }
        
        // SVG icon | Pass
        $pass_svg = '<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="green"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M9 16.2L4.8 12l-1.4 1.4L9 19 21 7l-1.4-1.4L9 16.2z"/></svg>';

        // SVG icon | Fail
        $fail_svg = '<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="brown"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12 19 6.41z"/></svg>';

        $quiz_pass_score_arr = array();

        $default_user_page_column_names = array(
            "user_name" => __( 'User name', $this->plugin_name ),
            "start_date" => __( 'Start date', $this->plugin_name ),
            "end_date" => __( 'End date', $this->plugin_name ),
            "duration" => __( 'Duration', $this->plugin_name ),
            "score" => __( 'Score', $this->plugin_name ),
            "download_certificate" => __( 'Certificate', $this->plugin_name ),
            "details" => __( 'Details', $this->plugin_name ),
            "points" => __( 'Points', $this->plugin_name ),
        );

        if( empty( $results ) ){
            $content[] = '<div class="'.$this->html_class_prefix.'results-by-quiz-tables">';
                $content[] = '<table data-result="empty">';
                        $content[] = '<th>';
                            $content[] = __('User Name', $this->plugin_name);
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
                            $content[] = __('Certificate', $this->plugin_name);
                        $content[] = '</th>';

                        $content[] = '<th>';
                            $content[] = __('Details', $this->plugin_name);
                        $content[] = '</th>';

                        $content[] = '<th>';
                            $content[] = __('Points', $this->plugin_name);
                        $content[] = '</th>';
                    $content[] = '</thead>';
                    $content[] = '<tbody>';
                        $content[] = "<tr><td style='text-align: center;font-style:italic;' colspan='8'>" . __( "There are no results yet.", $this->plugin_name ) . "</td></tr>";
                    $content[] = '</tbody>';
                $content[] = '</table>';
            $content[] = '</div>';
        }else{
            $content[] = '<div class="'.$this->html_class_prefix.'results-by-quiz-tables">';
                $content[] = '<table id="ays_quiz_user_dashboard_results_by_quiz_'.$quiz_id.'" data-result="not_empty">';
                    $content[] = '<thead>';
                        $content[] = '<tr>';
                            $content[] = '<th class="'. $this->html_name_prefix .'name-column">';
                                $content[] = __('User Name', $this->plugin_name);
                            $content[] = '</th>';

                            $content[] = '<th class="'. $this->html_name_prefix .'start-date-column">';
                                $content[] = __('Start Date', $this->plugin_name);
                            $content[] = '</th>';

                            $content[] = '<th class="'. $this->html_name_prefix .'end-date-column">';
                                $content[] = __('End Date', $this->plugin_name);
                            $content[] = '</th>';

                            $content[] = '<th class="'. $this->html_name_prefix .'duration-column">';
                                $content[] = __('Duration', $this->plugin_name);
                            $content[] = '</th>';

                            $content[] = '<th class="'. $this->html_name_prefix .'score-column">';
                                $content[] = __('Score', $this->plugin_name);
                            $content[] = '</th>';

                            $content[] = '<th class="'. $this->html_name_prefix .'cert-column">';
                                $content[] = __('Certificate', $this->plugin_name);
                            $content[] = '</th>';

                            $content[] = '<th class="'. $this->html_name_prefix .'details-column">';
                                $content[] = __('Details', $this->plugin_name);
                            $content[] = '</th>';

                            $content[] = '<th class="'. $this->html_name_prefix .'points-column">';
                                $content[] = __('Points', $this->plugin_name);
                            $content[] = '</th>';

                        $content[] = '</tr>';
                    $content[] = '</thead>';
                    $content[] = '<tbody>';
                        foreach ($results as $key => $user_result){
                            //id 
                            $id = isset($user_result['id']) ? $user_result['id'] : null;
            
                            //quiz_id
                            // $quiz_id = isset($user_result['quiz_id']) ? absint($user_result['quiz_id']) : null;
            
                            //user_id
                            // $user_id = isset($user_result['user_id']) ? intval($user_result['user_id']) : 0;
            
                            //Quiz Title
                            $quiz_title = (isset($user_result['title']) && $user_result['title'] != '') ? sanitize_text_field(stripslashes($user_result['title'])) : '';
            
                            //User Name
                            // $user_name = (isset($user_result['user_name']) && $user_result['user_name'] != '') ? sanitize_text_field(stripslashes($user_result['user_name'])) : '';
                            
                            //Start Date
                            $start_date = (isset($user_result['start_date']) && $user_result['start_date'] != '') ? $user_result['start_date'] : '';
            
                            //End Date
                            $end_date = (isset($user_result['end_date']) && $user_result['end_date'] != '') ? $user_result['end_date'] : '';
            
                            //Duration
                            $quiz_duration = (isset($user_result['duration']) && $user_result['duration'] != '') ? absint($user_result['duration']) : 0;
            
                            //Score
                            $quiz_score = (isset($user_result['score']) && $user_result['score'] != '') ? absint($user_result['score']) : 0;
                            
                            //Quiz Points
                            $quiz_points = (isset($user_result['points']) && $user_result['points'] != '') ? round( floatval( $user_result['points'] ), 2 ) : 0;

                            //Check Duration
                            if ($quiz_duration == null) {
                                $quiz_duration = isset($user_result['duration_2']) ? $user_result['duration_2'] : 0;
                            }
            
                            $start_date_for_ordering = strtotime($user_result['start_date']);
                            $end_date_for_ordering = strtotime($user_result['end_date']);
                            $duration_for_ordering = $quiz_duration;

                            $quiz_duration = Quiz_Maker_Data::secondsToWords($quiz_duration);
                            if ($quiz_duration == '') {
                                $quiz_duration = '0 ' . __( 'second' , $this->plugin_name );
                            }
                            
                            $cert_options = isset($user_result['options']) && $user_result['options'] != '' ? $user_result['options'] : array();
                            if(!empty($cert_options)){
                                $d_certificate = json_decode($cert_options, true);
                            }
                            $d_button = '';
                            $data_src = '';
                            if(isset($d_certificate['cert_file_url']) && $d_certificate['cert_file_url'] != ''){
                                $data_src = $d_certificate['cert_file_url'];
                                $d_button = "<a class='". $this->html_name_prefix ."user-d-cert' href=". $data_src ." download>".__("Download", $this->plugin_name)."</button>";
                            }

                            if($user_id != 0){
                                $user = get_userdata( $user_id );
                                if($user !== false){
                                    $user_name = $user->data->display_name ? $user->data->display_name : $user->user_login;
                                }else{
                                    continue;
                                }
                            }

                            //User Data TD
                            $content[] = '<tr>';
                                //User Name
                                $content[] = '<td class="'. $this->html_name_prefix .'name-column">';
                                    $content[] = $user_name;
                                $content[] = '</td>';

                                //Start Date
                                $content[] = '<td class="'. $this->html_name_prefix .'start-date-column" data-order="'. $start_date_for_ordering .'">';
                                    $content[] = $start_date;
                                $content[] = '</td>';
            
                                //End Date
                                $content[] = '<td class="'. $this->html_name_prefix .'end-date-column" data-order="'. $end_date_for_ordering .'">';
                                    $content[] = $end_date;
                                $content[] = '</td>';
            
                                //Duration
                                $content[] = '<td class="'. $this->html_name_prefix .'duration-column" data-order="'. $duration_for_ordering .'">';
                                    $content[] = $quiz_duration;
                                $content[] = '</td>';
            
                                //Score
                                $content[] = '<td class="'. $this->html_name_prefix .'score-column">';
                                    $content[] = $quiz_score .'%';
                                $content[] = '</td>';
            
                                //Certificate
                                $content[] = '<td class="'. $this->html_name_prefix .'cert-column">';
                                    $content[] = $d_button;
                                $content[] = '</td>';

                                //Details
                                $content[] = '<td class="'. $this->html_name_prefix .'details-column">';
                                    $content[] = '<button data-id="'.$id.'" class="'.$this->html_class_prefix.'details">Details</button>';
                                $content[] = '</td>';

                                //Points
                                $content[] = '<td class="'. $this->html_name_prefix .'points-column">';
                                    $content[] = $quiz_points;
                                $content[] = '</td>';
                            $content[] = '</tr>';
            
                        }
                    $content[] = '</tbody>';
                $content[] = '</table>';
        
                
                $content[] = $this->get_styles( $quiz_id );
                $content[] = "<div class='ays-modal ays-result-modal ays-results-modal-". $this->unique_id ."' data-id='".$this->unique_id."'>";
                    $content[] = "<div class='ays-modal-content'>";
                        $content[] = "<div class='". $this->html_class_prefix ."preloader'>";
                            $content[] = "<img class='loader' src='". QUIZ_MAKER_USER_DASHBOARD_ADMIN_URL."/images/loaders/tail-spin.svg'>";
                        $content[] = "</div>";
                        $content[] =  "<div class='ays-modal-header'>";
                            $content[] =  "<span class='ays-close' id='ays-close-results'>&times;</span>";
                        $content[] = "</div>";
                        $content[] = "<div class='ays-modal-body' id='ays-results-body'></div>";
                    $content[] = "</div>";

                    $content[] = "<style>";
                        $content[] = "@media only screen and (max-width: 760px),
                        (min-device-width: 768px) and (max-device-width: 1024px)  {";

                            $content[] = "table#ays_quiz_user_dashboard_results_by_quiz_'".$quiz_id."' td:empty { display: none !important; }";

                            $content[] = "table#ays_quiz_user_dashboard_results_by_quiz_'".$quiz_id."' td.'". $this->html_name_prefix ."'name-column:before { content: '" . $default_user_page_column_names['user_name'] . "'; }";

                            $content[] = "table#ays_quiz_user_dashboard_results_by_quiz_'".$quiz_id."' td.'". $this->html_name_prefix ."'start-date-column:before { content: '" . $default_user_page_column_names['start_date'] . "'; }";

                            $content[] = "table#ays_quiz_user_dashboard_results_by_quiz_'".$quiz_id."' td.'". $this->html_name_prefix ."'end-date-column:before { content: '" . $default_user_page_column_names['end_date'] . "'; }";

                            $content[] = "table#ays_quiz_user_dashboard_results_by_quiz_'".$quiz_id."' td.'". $this->html_name_prefix ."'duration-column:before { content: '" . $default_user_page_column_names['duration'] . "'; }";

                            $content[] = "table#ays_quiz_user_dashboard_results_by_quiz_'".$quiz_id."' td.'". $this->html_name_prefix ."'score-column:before { content: '" . $default_user_page_column_names['score'] . "'; }";

                            $content[] = "table#ays_quiz_user_dashboard_results_by_quiz_'".$quiz_id."' td.'". $this->html_name_prefix ."'cert-column:before { content: '" . $default_user_page_column_names['download_certificate'] . "'; }";

                            $content[] = "table#ays_quiz_user_dashboard_results_by_quiz_'".$quiz_id."' td.'". $this->html_name_prefix ."'details-column:before { content: '" . $default_user_page_column_names['details'] . "'; }";

                            $content[] = "table#ays_quiz_user_dashboard_results_by_quiz_'".$quiz_id."' td.'". $this->html_name_prefix ."'points-column:before { content: '" . $default_user_page_column_names['points'] . "'; }";

                            $content[] = "table#ays_quiz_user_dashboard_results_by_quiz_'".$quiz_id."' button.'". $this->html_name_prefix ."'details { margin: initial; }";
                        $content[] = "}";

                    $content[] = "/style";

                $content[] ="</div>";
			$content[] = '</div>';
        }
        
        $content = implode( '', $content );
        
        return $content;
    }

    public function get_user_reports_info_popup( $unique_id, $result_id ){
        global $wpdb;

        $results_table   = $wpdb->prefix . "aysquiz_reports";
        $questions_table = $wpdb->prefix . "aysquiz_questions";

        $setting_options = Quiz_Maker_User_Dashboard_Admin::ays_get_setting("options");
        $setting_options = json_decode($setting_options , true);
        $hide_correct_answer = isset($setting_options['quiz_hide_correct_answer_user_history_quiz']) && $setting_options['quiz_hide_correct_answer_user_history_quiz'] == "on" ? true : false;

        $results = $wpdb->get_row("SELECT * FROM {$results_table} WHERE id={$result_id}", "ARRAY_A");
        $user_id = intval($results['user_id']);
        $quiz_id = intval($results['quiz_id']);
        $user    = get_user_by('id', $user_id);

        // $user_ip = $results['user_ip'];
        $options     = json_decode($results['options']);
        // $user_attributes = $options->attributes_information;
        $start_date  = $results['start_date'];
        //$duration = $options->passed_time;
        $duration    = ( isset($results['duration']) && sanitize_text_field( $results['duration'] ) != '' ) ? sanitize_text_field( $results['duration'] ) : '';
        $rate_id     = isset($options->rate_id) ? $options->rate_id : null;
        $rate        = Quiz_Maker_Data::ays_quiz_rate($rate_id);
        $calc_method = isset($options->calc_method) ? $options->calc_method : 'by_correctness';

        // $json = json_decode(file_get_contents("http://ipinfo.io/{$user_ip}/json"));
        // $country = $json->country;
        // $region = $json->region;
        // $city = $json->city;
        // $from = $city . ', ' . $region . ', ' . $country . ', ' . $user_ip;

        $user_max_weight = isset($options->user_points) ? $options->user_points : '-';
        $quiz_max_weight = isset($options->max_points) ? $options->max_points : '-';
        $score = $calc_method == 'by_points' ? $user_max_weight . ' / ' . $quiz_max_weight : $results['score'] . '%';

        $duration = Quiz_Maker_Data::secondsToWords($duration);
        if ($duration == '') {
            $duration = '0 ' . __( 'second' , $this->plugin_name );
        }

        $row = "<table id='ays-results-table'>";

            $row .= '<tr class="ays_result_element">
                    <td colspan="4">
                        <div class="'. $this->html_name_prefix . 'report-table-header" id="quiz-export-pdf-'. $unique_id .'">
                            <h1>' . __('Quiz Information',$this->plugin_name) . '</h1>
                            <div>
                                <span class="ays-pdf-export-text">'.__("Export to" , $this->plugin_name).'</span>
                                <a download="" id="downloadFileF" hidden href=""></a>
                                <button type="button"  class="'. $this->html_name_prefix .'user-dasboard-export-pdf" data-unique-id="'. $unique_id .'" data-result="'.$result_id.'">'. __( "PDF", $this->plugin_name ) .'</button>
                            </div>
                        </div>
                    </td>
                </tr>';
            if(isset($rate['score'])){
                $rate_html = '<tr style="vertical-align: top;" class="ays_result_element">
                <td>'.__('Rate',$this->plugin_name).'</td>
                <td>'. __("Rate Score", $this->plugin_name).":<br>" . $rate['score'] . '</td>
                <td colspan="2" style="max-width: 200px;">'. __("Review", $this->plugin_name).":<br>" . $rate['review'] . '</td>
            </tr>';
            }else{
                $rate_html = '<tr class="ays_result_element">
                <td>'.__('Rate',$this->plugin_name).'</td>
                <td colspan="3">' . $rate['review'] . '</td>
            </tr>';
            }
            $row .= '<tr class="ays_result_element">
                    <td>'.__('Start date',$this->plugin_name).'</td>
                    <td colspan="3">' . $start_date . '</td>
                </tr>
                <tr class="ays_result_element">
                    <td>'.__('Duration',$this->plugin_name).'</td>
                    <td colspan="3">' . $duration . '</td>
                </tr>
                <tr class="ays_result_element">
                    <td>'.__('Score',$this->plugin_name).'</td>
                    <td colspan="3">' . $score . '</td>
                </tr>'.$rate_html;


            $row .= '<tr class="ays_result_element">
                    <td colspan="4"><h1>' . __('Questions',$this->plugin_name) . '</h1></td>
                </tr>';

            $index = 1;
            $user_exp = array();
            if($results['user_explanation'] != '' || $results['user_explanation'] !== null){
                $user_exp = json_decode($results['user_explanation'], true);
            }

            foreach ($options->correctness as $key => $option) {
                if (strpos($key, 'question_id_') !== false) {
                    $question_id = absint(sanitize_text_field(explode('_', $key)[2]));
                    $question = $wpdb->get_row("SELECT * FROM {$questions_table} WHERE id={$question_id}", "ARRAY_A");
                    $qoptions = isset($question['options']) && $question['options'] != '' ? json_decode($question['options'], true) : array();
                    $use_html = isset($qoptions['use_html']) && $qoptions['use_html'] == 'on' ? true : false;
                    $correct_answers = Quiz_Maker_Data::get_correct_answers($question_id);
                    $correct_answer_images = Quiz_Maker_Data::get_correct_answer_images($question_id);
                    $is_text_type = Quiz_Maker_Data::question_is_text_type($question_id);
                    $text_type = Quiz_Maker_Data::text_answer_is($question_id);
                    $not_multiple_text_types = array("number", "date");

                    if($is_text_type){
                        $user_answered = Quiz_Maker_Data::get_user_text_answered($options->user_answered, $key);
                        $user_answered_images = '';
                    }else{
                        $user_answered = Quiz_Maker_Data::get_user_answered($options->user_answered, $key);
                        $user_answered_images = Quiz_Maker_Data::get_user_answered_images($options->user_answered, $key);
                    }

                    $ans_point = $option;
                    $ans_point_class = 'success';
                    if(is_array($user_answered)){
                        $user_answered = $user_answered['message'];
                        $ans_point = '-';
                        $ans_point_class = 'error';
                    }

                    $tr_class = "ays_result_element";
                    if(isset($user_exp[$question_id])){
                        $tr_class = "";
                    }

                    $not_influence_to_score = isset($question['not_influence_to_score']) && $question['not_influence_to_score'] == 'on' ? true : false;
                    if ( $not_influence_to_score ) {
                        $not_influance_check_td = ' colspan="2" ';
                    }else{
                        $not_influance_check_td = '';
                    }

                    if($calc_method == 'by_correctness'){
                        $row .= '<tr class="'.$tr_class.'">
                            <td>'.__('Question', $this->plugin_name).' ' . $index . ' :<br/>' . stripslashes($question["question"]) . '</td>';

                        $status_class = 'error';
                        $correct_answers_status_class = 'success';
                        if ($option == true) {
                            $status_class = 'success';
                        }

                        if ($not_influence_to_score) {
                            $status_class = 'no_status';
                            $correct_answers_status_class = 'no_status';
                        }

                        if(!$hide_correct_answer){
                            if($is_text_type && ! in_array($text_type, $not_multiple_text_types)){
                                $c_answers = explode('%%%', $correct_answers);
                                $c_answer = $c_answers[0];
                                foreach($c_answers as $c_ans){
                                    if(strtolower(trim($user_answered)) == strtolower(trim($c_ans))){
                                        $c_answer = $c_ans;
                                        break;
                                    }
                                }
                                $row .= '<td class="ays-report-correct-answer">'.__('Correct answer',$this->plugin_name).':<br/>';
                                $row .= '<p class="success">' . htmlentities(stripslashes($c_answer)) . '<br>'.$correct_answer_images.'</p>';
                                $row .= '</td>';
                            }else{
                                if($text_type == 'date'){
                                    $correct_answers = date( 'm/d/Y', strtotime( $correct_answers ) );
                                }
                                $correct_answer_content = htmlentities( stripslashes( $correct_answers ) );
                                if($use_html){
                                    $correct_answer_content = stripslashes( $correct_answers );
                                }

                                $row .= '<td class="ays-report-correct-answer">'.__('Correct answer',$this->plugin_name).':<br/>
                                    <p class="'.$correct_answers_status_class.'">' . $correct_answer_content . '<br>'.$correct_answer_images.'</p>
                                </td>';
                            }
                        }

                        if($text_type == 'date'){
                            if(Quiz_Maker_Admin::validateDate($user_answered, 'Y-m-d')){
                                $user_answered = date( 'm/d/Y', strtotime( $user_answered ) );
                            }
                        }
                        $user_answer_content = htmlentities( stripslashes( $user_answered ) );
                        if($use_html){
                            $user_answer_content = stripslashes( $user_answered );
                        }

                        if($hide_correct_answer){
                            $status_class = "ays_quiz_user_history_quiz_hide_answer";
                        }

                        $row .= '<td '.$not_influance_check_td.' class="ays-report-user-answer">'.__('User answered',$this->plugin_name).':<br/>
                            <p class="'.$status_class.'">' . $user_answer_content . '</p>
                        </td>';

                        if (! $not_influence_to_score && !$hide_correct_answer) {
                            if ($option == true) {
                                    $row .= '<td class="ays-report-status-icon">
                                        <svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 130.2 130.2">
                                            <circle class="path circle" fill="none" stroke="#73AF55" stroke-width="6" stroke-miterlimit="10" cx="65.1" cy="65.1" r="62.1"/>
                                            <polyline class="path check" fill="none" stroke="#73AF55" stroke-width="6" stroke-linecap="round" stroke-miterlimit="10" points="100.2,40.2 51.5,88.8 29.8,67.5 "/>
                                        </svg>
                                        <p class="success">'.__('Succeed',$this->plugin_name).'!</p>
                                    </td>';
                            } else {
                                $row .= '<td class="ays-report-status-icon">
                                    <svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 130.2 130.2">
                                        <circle class="path circle" fill="none" stroke="#D06079" stroke-width="6" stroke-miterlimit="10" cx="65.1" cy="65.1" r="62.1"/>
                                        <line class="path line" fill="none" stroke="#D06079" stroke-width="6" stroke-linecap="round" stroke-miterlimit="10" x1="34.4" y1="37.9" x2="95.8" y2="92.3"/>
                                        <line class="path line" fill="none" stroke="#D06079" stroke-width="6" stroke-linecap="round" stroke-miterlimit="10" x1="95.8" y1="38" x2="34.4" y2="92.2"/>
                                    </svg>
                                    <p class="error">'.__('Failed',$this->plugin_name).'!</p>
                                </td>';
                            }
                        }

                        $row .= '</tr>';

                    }elseif($calc_method == 'by_points'){
                        if($hide_correct_answer){
                            $ans_point_class = "ays_quiz_user_history_quiz_hide_answer";
                            $answer_point_box = "";
                        }else{
                            $answer_point_box = '<td class="ays-report-answer-point">'.__('Answer point',$this->plugin_name).':<br/><p class="'.$ans_point_class.'">' . htmlentities($ans_point) . '</p></td>';
                        }

                        $row .= '<tr class="'.$tr_class.'">
                                <td colspan="2">'.__('Question',$this->plugin_name).' ' . $index . ' :<br/>' . (do_shortcode(stripslashes($question["question"]))) . '</td>
                                <td class="ays-report-user-answer ays-report-user-answer-by-points">'.__('User answered',$this->plugin_name).':<br/><p class="'.$ans_point_class.'">' . htmlentities(do_shortcode(stripslashes($user_answered))) . '<br>'.$user_answered_images.'</p></td>';
                        $row .= $answer_point_box;
                        $row .= '</tr>';
                    }
                    $index++;
                    if(isset($user_exp[$question_id])){
                        $row .= '<tr class="ays_result_element">
                        <td>'.__('User explanation for this question',$this->plugin_name).'</td>
                        <td colspan="3">'.$user_exp[$question_id].'</td>
                    </tr>';
                    }
                }
            }

        $row .= "</table>";

        return $row;
    }

    // Export result to pdf
    public function user_dashboard_export_result_pdf( $result_id ) {
        global $wpdb;

        $results_table   = esc_sql( $wpdb->prefix . "aysquiz_reports" );
        $questions_table = esc_sql( $wpdb->prefix . "aysquiz_questions" );
        $quizzes_table   = esc_sql( $wpdb->prefix . "aysquiz_quizes" );

        $pdf_response = null;
        $pdf_content  = null;

        $results         = $wpdb->get_row("SELECT * FROM {$results_table} WHERE id={$result_id} AND `status` = 'finished';", "ARRAY_A");
        $user_id         = intval($results['user_id']);
        $quiz_id         = intval($results['quiz_id']);
        
        $user            = get_user_by('id', $user_id);
        // $user_ip         = $results['user_ip'];
        $options         = json_decode($results['options']);
        // $user_attributes = $options->attributes_information;
        $start_date      = $results['start_date'];
        $duration        = $options->passed_time;
        $rate_id         = isset($options->rate_id) ? $options->rate_id : null;
        $rate            = Quiz_Maker_Data::ays_quiz_rate($rate_id);
        $calc_method     = isset($options->calc_method) ? $options->calc_method : 'by_correctness';
        $correctness     = (array)$options->correctness;
        
        if(!isset($options->user_points)){
            $options->user_points = array_sum($correctness);
        }
        
        $setting_options = Quiz_Maker_User_Dashboard_Admin::ays_get_setting("options");
        $setting_options = json_decode($setting_options , true);
        $hide_correct_answer = isset($setting_options['quiz_hide_correct_answer_user_history_quiz']) && $setting_options['quiz_hide_correct_answer_user_history_quiz'] == "on" ? true : false;
        
        $user_max_weight = isset($options->user_points) ? $options->user_points : '-';
        $quiz_max_weight = isset($options->max_points) ? $options->max_points : '-';
        
        $score       = ($calc_method == 'by_points') ? $user_max_weight . ' / ' . $quiz_max_weight : $results['score'] . '%';
        // $user        = ($user_id === 0) ? __( "Guest", $this->plugin_name ) : $user->data->display_name;
        $review      = (isset($rate['review']) && $rate['review'] != null) ? stripslashes(html_entity_decode(str_replace("\n", "", (strip_tags($rate['review']) )))) : '';
        // $email       = (isset($results['user_email']) && $results['user_email'] !== '') ? stripslashes($results['user_email']) : '';
        // $user_name   = (isset($results['user_name']) && $results['user_name'] !== '') ? stripslashes($results['user_name']) : '';
        // $user_phone  = (isset($results['user_phone']) && $results['user_phone'] !== '') ? stripslashes($results['user_phone']) : '';
        // $unique_code = (isset($results['unique_code']) && $results['unique_code'] !== '') ? strtoupper($results['unique_code']) : '';
        // $json    = json_decode(file_get_contents("http://ipinfo.io/{$user_ip}/json"));
        // $country = $json->country;
        // $region  = $json->region;
        // $city    = $json->city;
        // $from    = $city . ', ' . $region . ', ' . $country . ', ' . $user_ip;
        // if ($user_ip == '') {
            //     $from = '';
            // }
            $quests      = array();
            $export_data = array();
            
            $data_headers   = array();
            $data_questions = array();
            
        $data_headers['user_data'] = array(
            // 'api_user_information_header' => __( "User Information", $this->plugin_name ),
            // 'api_user_ip_header'     => __( "User IP", $this->plugin_name ),
            // 'api_user_id_header'     => __( "User ID", $this->plugin_name ),
            // 'api_user_header'        => __( "User", $this->plugin_name ),
            // 'api_user_mail_header'   => __( "Email", $this->plugin_name ),
            // 'api_user_name_header'   => __( "Name", $this->plugin_name ),
            // 'api_user_phone_header'  => __( "Phone", $this->plugin_name ),
            // 'api_checked_header'     => __( "Checked", $this->plugin_name ),
            
            'api_quiz_information_header' => __( "Quiz Information", $this->plugin_name ),
            
            // 'api_user_ip'     =>  $from,
            // 'api_user_id'     =>  $user_id."",
            // 'api_user'        =>  $user,
            // 'api_user_mail'   =>  $email,
            // 'api_user_name'   =>  $user_name,
            // 'api_user_phone'  =>  $user_phone,
            
            'api_start_date_header' =>  __( "Start date", $this->plugin_name ),
            'api_duration_header'   =>  __( "Duration", $this->plugin_name ),
            'api_score_header'      =>  __( "Score", $this->plugin_name ),
            'api_rate_header'       =>  __( "Rate", $this->plugin_name ),
            
            'api_start_date' =>  $start_date,
            'api_duration'   =>  $duration,
            'api_score'      =>  $score,
            'api_rate'       =>  $review,
        );

        // if ($user_attributes !== null) {
        //     $user_attributes = (array)$user_attributes;
        //     foreach ($user_attributes as $name => $value) {
        //         if(stripslashes($value) == ''){
        //             $attr_value = '-';
        //         }else{
        //             $attr_value = stripslashes($value);
        //         }
        //         if($attr_value == 'on'){
        //             $attr_value = __('Checked',$this->plugin_name);
        //         }
        //         $custom_fild = array(
        //             'api_custom_fild_name'  => stripslashes($name),
        //             'api_custom_fild_value' => $attr_value,
        //         );
        //         $quests[] = $custom_fild;
        //     }
        // }
        // $data_headers['custom_fild'] = $quests;
        
        $data_questions['headers'] = array(
            'api_glob_question_header'  => __( "Questions", $this->plugin_name ),
            'api_question_header'       => __( "Question", $this->plugin_name ),
            'api_correct_answer_header' => __( "Correct answer", $this->plugin_name ),
            'api_user_answer_header'    => __( "User answered", $this->plugin_name ),
            'api_hide_correct_answer'   => $hide_correct_answer,
        );
        
        $quests = array();
        foreach ($options->correctness as $key => $option) {
            if (strpos($key, 'question_id_') !== false) {
                $question_id     = absint(intval(explode('_', $key)[2]));
                $question_content = $wpdb->get_row("SELECT * FROM {$questions_table} WHERE id={$question_id}", "ARRAY_A");
                $correct_answers = Quiz_Maker_Data::get_correct_answers($question_id);
                
                if(Quiz_Maker_Data::question_is_text_type($question_id)){
                    $user_answered = Quiz_Maker_Data::get_user_text_answered($options->user_answered, $key);
                }else{
                    $user_answered = Quiz_Maker_Data::get_user_answered($options->user_answered, $key);
                }
                
                
                if ($user_answered == '' || ( isset($user_answered['status']) && $user_answered['status'] == false ) ) {
                    $user_answered = ' - ';
                }
                
                $successed_or_failed = ($option == true) ? __( "Succeed", $this->plugin_name ) : __( "Failed", $this->plugin_name );
                
                $question       = esc_attr(stripslashes($question_content["question"]));
                $correct_answer = html_entity_decode(strip_tags(stripslashes($correct_answers)));
                $user_answer    = html_entity_decode(strip_tags(stripslashes($user_answered)));
                $questions = array(
                    'api_question'       => $question,
                    'api_correct_answer' => $correct_answer,
                    'api_user_answer'    => $user_answer,
                    'api_status'         => $successed_or_failed,
                    'api_check_status'   => $option,
                );
                
                $quests[] = $questions;
            }
        }
        $data_questions['data_question'] = $quests;
        
        if ( !class_exists( 'Quiz_PDF_API' ) ) {
            $pdf_content = false;
        } else {
            $pdf = new Quiz_PDF_API();

            $export_data = array(
                'status'          => true,
                'type'            => 'pdfapi',
                'api_quiz_id'     => $quiz_id,
                'data_headers'    => $data_headers,
                'data_questions'  => $data_questions
            );
            
            $pdf_response = $pdf->generate_report_PDF_public($export_data);

            $pdf_content  = $pdf_response['status'];
        }

        if($pdf_content === true){
            return $pdf_response;
        }else{
            return $export_data;
        }
    }

    public function get_styles( $quiz_id ){

        $content = array();

        $default_user_page_column_names = array(
            "user_name" => __( 'User name', $this->plugin_name ),
            "start_date" => __( 'Start date', $this->plugin_name ),
            "end_date" => __( 'End date', $this->plugin_name ),
            "duration" => __( 'Duration', $this->plugin_name ),
            "score" => __( 'Score', $this->plugin_name ),
            "download_certificate" => __( 'Certificate', $this->plugin_name ),
            "details" => __( 'Details', $this->plugin_name ),
            "points" => __( 'Points', $this->plugin_name ),
        );

        $content[] = '<style type="text/css">';

        $content[] = "
            #". $this->html_name_prefix ."user-results-container-". $this->unique_id_in_class ." {
                margin: 20px auto;
            }

            /*
             * User reports info table
             */
            table#ays_quiz_user_dashboard_results_by_quiz_". $quiz_id ." {
                margin: 0 !important;
            }
            table#ays_quiz_user_dashboard_results_by_quiz_". $quiz_id ." thead th {
                font-size: 16px;
            }
            table#ays_quiz_user_dashboard_results_by_quiz_". $quiz_id ." td {
                font-size: 14px;
            }
            table#ays_quiz_user_dashboard_results_by_quiz_". $quiz_id ." thead th,
            table#ays_quiz_user_dashboard_results_by_quiz_". $quiz_id ." td {
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

            table#ays_quiz_user_dashboard_results_by_quiz_". $quiz_id ." {
                width: 100%;
                border-collapse: collapse;
            }

            /* Zebra striping */
            table#ays_quiz_user_dashboard_results_by_quiz_". $quiz_id ." tr:nth-of-type(odd) {
                background: #eee;
            }

            table#ays_quiz_user_dashboard_results_by_quiz_". $quiz_id ." th {
                color:  #333;
                font-weight: bold;
            }

            table#ays_quiz_user_dashboard_results_by_quiz_". $quiz_id ." td,
            table#ays_quiz_user_dashboard_results_by_quiz_". $quiz_id ." th {
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
                table#ays_quiz_user_dashboard_results_by_quiz_". $quiz_id.",
                table#ays_quiz_user_dashboard_results_by_quiz_". $quiz_id ." thead,
                table#ays_quiz_user_dashboard_results_by_quiz_". $quiz_id ." tbody,
                table#ays_quiz_user_dashboard_results_by_quiz_". $quiz_id ." th,
                table#ays_quiz_user_dashboard_results_by_quiz_". $quiz_id ." td,
                table#ays_quiz_user_dashboard_results_by_quiz_". $quiz_id ." tr {
                    display: block;
                }

                /* Hide table headers (but not display: none;, for accessibility) */
                table#ays_quiz_user_dashboard_results_by_quiz_". $quiz_id ." thead tr {
                    position: absolute;
                    top: -9999px;
                    left: -9999px;
                }

                table#ays_quiz_user_dashboard_results_by_quiz_". $quiz_id ." tr { border: 1px solid #ccc; }

                table#ays_quiz_user_dashboard_results_by_quiz_". $quiz_id ." td {
                    /* Behave  like a row */
                    border: none;
                    border-bottom: 1px solid #eee;
                    position: relative;
                    padding-left: 50%;
                    text-align: left;
                }

                table#ays_quiz_user_dashboard_results_by_quiz_". $quiz_id ." td:before {
                    /* Now like a table header */
                    position: absolute;
                    /* Top/left values mimic padding */
                    top: 6px;
                    left: 6px;
                    width: 45%;
                    padding-right: 10px;
                    white-space: nowrap;
                }

                table#ays_quiz_user_dashboard_results_by_quiz_". $quiz_id ." td:empty { display: none !important; }
                table#ays_quiz_user_dashboard_results_by_quiz_". $quiz_id ." td.". $this->html_name_prefix ."name-column:before { content: '" . $default_user_page_column_names['user_name'] . "'; }
                table#ays_quiz_user_dashboard_results_by_quiz_". $quiz_id ." td.". $this->html_name_prefix ."start-date-column:before { content: '" . $default_user_page_column_names['start_date'] . "'; }
                table#ays_quiz_user_dashboard_results_by_quiz_". $quiz_id ." td.". $this->html_name_prefix ."end-date-column:before { content: '" . $default_user_page_column_names['end_date'] . "'; }
                table#ays_quiz_user_dashboard_results_by_quiz_". $quiz_id ." td.". $this->html_name_prefix ."duration-column:before { content: '" . $default_user_page_column_names['duration'] . "'; }
                table#ays_quiz_user_dashboard_results_by_quiz_". $quiz_id ." td.". $this->html_name_prefix ."score-column:before { content: '" . $default_user_page_column_names['score'] . "'; }
                table#ays_quiz_user_dashboard_results_by_quiz_". $quiz_id ." td.". $this->html_name_prefix ."cert-column:before { content: '" . $default_user_page_column_names['download_certificate'] . "'; }
                table#ays_quiz_user_dashboard_results_by_quiz_". $quiz_id ." td.". $this->html_name_prefix ."details-column:before { content: '" . $default_user_page_column_names['details'] . "'; }
                table#ays_quiz_user_dashboard_results_by_quiz_". $quiz_id ." td.". $this->html_name_prefix ."points-column:before { content: '" . $default_user_page_column_names['points'] . "'; }
                table#ays_quiz_user_dashboard_results_by_quiz_". $quiz_id ." button.". $this->html_name_prefix ."-details { margin: initial; }
            }

            ";
        
        $content[] = "</style>";

        $content = implode( '', $content );

        return $content;
    }

}
