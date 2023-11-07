<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://ays-pro.com
 * @since      1.0.0
 *
 * @package    Quiz_Maker_User_Dashboard
 * @subpackage Quiz_Maker_User_Dashboard/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Quiz_Maker_User_Dashboard
 * @subpackage Quiz_Maker_User_Dashboard/public
 * @author     Quiz Maker team <info@ays-pro.com>
 */
class Quiz_Maker_User_Dashboard_Public {

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
	private $html_class_prefix = 'ays-quiz-user-dashboard-';
    private $html_name_prefix = 'ays-quiz-';
    private $name_prefix = 'ays_quiz_';
    private $unique_id;
    private $unique_id_in_class;

	protected $user_results_by_quiz;

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
		
		add_shortcode('ays_quiz_advanced_user_dashboard', array($this, 'ays_generate_advanced_user_dashboard'));
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
		 * defined in Quiz_Maker_User_Dashboard_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Quiz_Maker_User_Dashboard_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/quiz-maker-user-dashboard-public.css', array(), $this->version, 'all' );

        wp_enqueue_style($this->plugin_name . '-dataTable-min', QUIZ_MAKER_USER_DASHBOARD_PUBLIC_URL . '/css/user-dashboard-dataTables.min.css', array(), $this->version, 'all');

		wp_enqueue_style( $this->plugin_name . '-sweetalert-css', QUIZ_MAKER_USER_DASHBOARD_PUBLIC_URL . '/css/quiz-maker-user-dashboard-sweetalert2.min.css', array(), $this->version, 'all');
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
		 * defined in Quiz_Maker_User_Dashboard_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Quiz_Maker_User_Dashboard_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/quiz-maker-user-dashboard-public.js', array( 'jquery' ), $this->version, false );

		wp_enqueue_script( $this->plugin_name . '-public-ajax', QUIZ_MAKER_USER_DASHBOARD_PUBLIC_URL . '/js/quiz-maker-user-dashboard-public-ajax.js', array('jquery'), time(), true );

        wp_localize_script( $this->plugin_name . '-public-ajax', 'user_dashboard_public_ajax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
        ));
		
        wp_localize_script( $this->plugin_name, 'AysQuizUserDashboardObj', array(	
            'loadResource'          => __( "Can't load resource.", $this->plugin_name ),
            'dataDeleted'           => __( 'Maybe the data has been deleted.', $this->plugin_name ),

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
			'attempt'               => __( 'Attempt', $this->plugin_name ),
            'userProgress'          => __( 'User progress', $this->plugin_name ),
            'progressionChart'      => __( 'Progression chart', $this->plugin_name ),
            "all"                   => __( "All", $this->plugin_name ),
        ) );
		
		wp_enqueue_script( $this->plugin_name . '-datatable-min', QUIZ_MAKER_USER_DASHBOARD_PUBLIC_URL . '/js/partials/user-dashboard-datatable.min.js', array('jquery'), $this->version, true);

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
		
		wp_enqueue_script( $this->plugin_name . '-user-google-chart', QUIZ_MAKER_USER_DASHBOARD_PUBLIC_URL . '/js/partials/google-chart.js', array('jquery'), $this->version, true);

		wp_enqueue_script( $this->plugin_name . '-sweetalert-js', QUIZ_MAKER_USER_DASHBOARD_PUBLIC_URL . '/js/quiz-maker-user-dashboard-sweetalert2.all.min.js', array('jquery'), $this->version, true );
	}
	
	//All Quizzes
    public function get_all_quizzes(){
        global $wpdb;

        $quiz_table = $wpdb->prefix. 'aysquiz_quizes';

        $sql = "SELECT * FROM {$quiz_table}";
        $results = $wpdb->get_results( $sql, ARRAY_A );

        return $results;
    }

	//User Activity Per Day
	public function get_user_activity_per_day( $user_id ){
		$user_activity_per_day = new User_Advanced_Dashboard_User_Activity_Per_Day( $this->plugin_name, $this->version );

		return $user_activity_per_day->ays_user_activity_per_day_html( $user_id );
	}

	//User All Results
	public function get_user_all_quiz_results( $user_id ){

		$user_all_results = new Quiz_Maker_User_Dashboard_All_Results( $this->plugin_name, $this->version );

		return $user_all_results->ays_user_dashboard_all_results_html( $user_id );
		
	}
	
	//User Results by Quiz
	public function get_user_results_by_quiz( $user_id, $quiz_id, $unique_id ){

		$user_results_by_quiz = new Quiz_Maker_User_Dashboard_Results_By_Quiz( $this->plugin_name, $this->version );

		return $user_results_by_quiz->user_results_by_quiz_html( $user_id, $quiz_id, $unique_id );
	}

	//User Results by Quiz Details
	public function get_user_reports( $unique_id, $result_id ){

		$user_results_by_quiz = new Quiz_Maker_User_Dashboard_Results_By_Quiz( $this->plugin_name, $this->version );

		return $user_results_by_quiz->get_user_reports_info_popup( $unique_id, $result_id );
	}

	//User Results by Quiz Details AJAX
	public function get_user_reports_info_popup_ajax(){
		error_reporting(0);
		
		if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'get_user_reports_info_popup_ajax'){
			//Unique ID
			$unique_id = (isset($_REQUEST['unique_id']) && $_REQUEST['unique_id'] != '') ? $_REQUEST['unique_id'] : null;
			
			//Result ID
			$result_id = (isset($_REQUEST['result_id']) && $_REQUEST['result_id'] != '') ? absint($_REQUEST['result_id']) : null;
			
			if($unique_id === null){
				return;
			}
			
			if($result_id === null){
				return;
			}
			
			$details = $this->get_user_reports( $unique_id, $result_id );
			
			ob_end_clean();
			$ob_get_clean = ob_get_clean();
			echo json_encode(array(
				"status" => true,
				"details" => $details,
			));
			wp_die();
		}else{
			echo json_encode(array(
                "status" => false,
                "details" => ''
            ));
            wp_die();
		}
	}

	//User Results by Quiz Details PDF
	public function get_user_reports_pdf( $result_id ){

		$user_results_by_quiz = new Quiz_Maker_User_Dashboard_Results_By_Quiz( $this->plugin_name, $this->version );

		return $user_results_by_quiz->user_dashboard_export_result_pdf( $result_id );
	}

	//User Results by Quiz Details PDF AJAX
	public function get_user_reports_pdf_ajax(){
		error_reporting(0);

		if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'get_user_reports_pdf_ajax'){
			//Result ID
			$result_id = (isset($_REQUEST['result']) && $_REQUEST['result'] != '') ? absint($_REQUEST['result']) : null;

			if($result_id === null){
				return;
			}
			
			$result = $this->get_user_reports_pdf( $result_id );
			
			ob_end_clean();
			$ob_get_clean = ob_get_clean();
			echo json_encode(array(
				"status" => true,
				"result" => $result,
			));
			wp_die();
		}else{
			echo json_encode(array(
                "status" => false,
                "result" => $result,
            ));
            wp_die();
		}
	}

	//User Results by Quiz AJAX
	public function get_results_by_quiz(){
		error_reporting(0);
		if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'get_results_by_quiz'){
			//Unique ID
			$unique_id = (isset($_REQUEST['unique_id']) && $_REQUEST['unique_id'] != '') ? $_REQUEST['unique_id'] : '';
			
			//Quiz ID
			$quiz_id = (isset($_REQUEST['quiz_id']) && $_REQUEST['quiz_id'] != '') ? absint($_REQUEST['quiz_id']) : null;

			//User ID
			$user_id = (isset($_REQUEST['user_id']) && $_REQUEST['user_id'] != '') ? absint($_REQUEST['user_id']) : null;

			if($quiz_id === null){
				return;
			}

			if($user_id === null){
				return;
			}

			$content = $this->get_user_results_by_quiz( $user_id, $quiz_id, $unique_id );

			ob_end_clean();
			$ob_get_clean = ob_get_clean();
			echo json_encode(array(
				"status" => true,
				"content" => $content,
			));
			wp_die();
		}
	}

	// User Best Score
	public function user_best_score( $user_id, $quiz_id, $mode ){
		$user_best_score_by_quiz = new Quiz_Maker_User_Dashboard_Best_Score_By_Quiz( $this->plugin_name, $this->version );

		return $user_best_score_by_quiz->ays_user_bestscore_html( $user_id, $quiz_id, $mode );
	}
	
	//User Best Score AJAX
	public function user_best_score_by_quiz_ajax()
	{
		error_reporting(0);

		if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'user_best_score_by_quiz_ajax'){
			//User ID
			$user_id = (isset($_REQUEST['user_id']) && $_REQUEST['user_id'] != '') ? absint($_REQUEST['user_id']) : null;

			//Quiz ID
			$quiz_id = (isset($_REQUEST['quiz_id']) && $_REQUEST['quiz_id'] != '') ? absint($_REQUEST['quiz_id']) : null;

			//Mode
			$mode = (isset($_REQUEST['mode']) && $_REQUEST['mode'] != '') ? sanitize_text_field($_REQUEST['mode']) : 'percentage';

			if($user_id === null){
				return;
			}

			if($quiz_id === null){
				return;
			}
			
			$result = $this->user_best_score( $user_id, $quiz_id, $mode );
			
			ob_end_clean();
			$ob_get_clean = ob_get_clean();
			echo json_encode(array(
				"status" => true,
				"result" => $result,
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

	// User Progress 
	public function user_progress( $user_id, $quiz_id, $mode, $unique_id ){
		$user_progress_by_quiz = new Quiz_Maker_User_Dashboard_User_Progress_By_Quiz( $this->plugin_name, $this->version );

		return $user_progress_by_quiz->ays_user_progress_html( $user_id, $quiz_id, $mode, $unique_id );
	}
	
	//User Progress AJAX
	public function user_progress_by_quiz_ajax()
	{
		error_reporting(0);

		if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'user_progress_by_quiz_ajax'){
			//User ID
			$user_id = (isset($_REQUEST['user_id']) && $_REQUEST['user_id'] != '') ? absint($_REQUEST['user_id']) : null;

			//Quiz ID
			$quiz_id = (isset($_REQUEST['quiz_id']) && $_REQUEST['quiz_id'] != '') ? absint($_REQUEST['quiz_id']) : null;

			//Mode
			$mode = (isset($_REQUEST['mode']) && $_REQUEST['mode'] != '') ? sanitize_text_field($_REQUEST['mode']) : 'score';

			//Unique ID
			$unique_id = (isset($_REQUEST['unique_id']) && $_REQUEST['unique_id'] != '') ? $_REQUEST['unique_id'] : '';

			if($user_id === null){
				return;
			}

			if($quiz_id === null){
				return;
			}
			
			$result = $this->user_progress( $user_id, $quiz_id, $mode, $unique_id );

			ob_end_clean();
			$ob_get_clean = ob_get_clean();
			echo json_encode(array(
				"status" => true,
				"result" => $result,
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

	//Add On Content
	public function create_content( $user_id ){
		$tab = isset($_GET['ays-ud-tab']) ? $_GET['ays-ud-tab'] : 'user-activity-per-day';
		$tab_url = "?ays-ud-tab=";
		$active_tab_name = '';
		$active_tab_class = "ays-quiz-user-dashboard-tab-content-active";
		$active_tab_empty = "";

		switch ($tab) {
			case 'user-activity-per-day':
				$active_tab_name = 'user-activity-per-day';
				break;
			case 'results':
				$active_tab_name = 'results';
				break;
			case 'results-by-quiz':
				$active_tab_name = 'results-by-quiz';
				break;
			case 'best-score':
				$active_tab_name = 'best-score';
				break;
			case 'progress':
				$active_tab_name = 'progress';
				break;
			default:
				$active_tab_name = 'user-activity-per-day';
				break;
		}

        $results = $this->get_all_quizzes();
		$content = array();

		$content[] = '<div class="ays-quiz-advanced-user-dashboard-main">';
			$content[] = '<div class="ays-quiz-advanced-user-dashboard-container">';	

				//User Dashboard Tabs
				$content[] = '<div class="ays-top-menu ays-quiz-advanced-user-dashboard-tabs">';
					$content[] = '<div class="nav-tab-wrapper">';
						$content[] = '<div>';
							$content[] = '<a href="'.$tab_url.'user-activity-per-day" data-tab="tab1" class="nav-tab '.(($tab == 'user-activity-per-day') ? 'nav-tab-active' : $active_tab_empty).'">';
								$content[] = __("Activity Per Day", $this->plugin_name);
							$content[] = '</a>';
						$content[] = '</div>';
						$content[] = '<div>';
							$content[] = '<a href="'.$tab_url.'results" data-tab="tab2" class="nav-tab '.(($tab == 'results') ? 'nav-tab-active' : $active_tab_empty).'">';
								$content[] = __("Results", $this->plugin_name);
							$content[] = '</a>';
						$content[] = '</div>';
						$content[] = '<div>';
							$content[] = '<a href="'.$tab_url.'results-by-quiz" data-tab="tab3" class="nav-tab '.(($tab == 'results-by-quiz') ? 'nav-tab-active' : $active_tab_empty).'">';
								$content[] = __("Results by Quiz", $this->plugin_name);
							$content[] = '</a>';
						$content[] = '</div>';
						$content[] = '<div>';
							$content[] = '<a href="'.$tab_url.'best-score" data-tab="tab4" class="nav-tab '.(($tab == 'best-score') ? 'nav-tab-active' : $active_tab_empty).'">';
								$content[] = __("Best Score", $this->plugin_name);
							$content[] = '</a>';
						$content[] = '</div>';
						$content[] = '<div>';
							$content[] = '<a href="'.$tab_url.'progress" data-tab="tab5" class="nav-tab '.(($tab == 'progress') ? 'nav-tab-active' : $active_tab_empty).'">';
								$content[] = __("Progress", $this->plugin_name);
							$content[] = '</a>';
						$content[] = '</div>';
					$content[] = '</div>';
				$content[] = '</div>';
								
				//User Dashboard Content
				$content[] = '<div class="ays-quiz-advanced-user-dashboard-content">';

					//Activity Per Day
					$content[] = '<div class="'.$this->html_class_prefix.'content-activity-per-day ays-quiz-user-dashboard-tab-content '.(($tab == 'user-activity-per-day') ? $active_tab_class : $active_tab_empty).'">';
						$content[] = $this->get_user_activity_per_day($user_id);
					$content[] = '</div>';

					//Results
					$content[] = '<div class="'.$this->html_class_prefix.'content-results ays-quiz-user-dashboard-all-results ays-quiz-user-dashboard-tab-content '.(($tab == 'results') ? $active_tab_class : $active_tab_empty).'">';
						$content[] = $this->get_user_all_quiz_results($user_id);
					$content[] = '</div>';

					//Results by Quiz
					$content[] = '<div class="'.$this->html_class_prefix.'content-result-by-quiz ays-quiz-user-dashboard-tab-content '.(($tab == 'results-by-quiz') ? $active_tab_class : $active_tab_empty).'">';
						$content[] = '<div class="'.$this->html_class_prefix.'quizzes-div">';
							//Select quiz
							$content[] = '<select data-user="'.$user_id.'" class="'.$this->html_class_prefix.'quizzes">';
								$content[] = '<option selected="true" disabled>';
									$content[] = __( 'Select quiz', $this->plugin_name );
								$content[] = '</option>';
								foreach ($results as $key => $result) {
									//Quiz ID
									$quiz_id = (isset($result['id']) && $result['id'] != '') ? absint($result['id']) : null;

									//Quiz Title
									$quiz_title = (isset($result['title']) && $result['title'] != '') ? sanitize_text_field(stripslashes($result['title'])) : '';

									if($quiz_id === null){
										return;
									}

									$content[] = '<option data-id="'.$quiz_id.'">';
										$content[] = $quiz_title;
									$content[] = '</option>';
								}
							$content[] = '</select>';

							//unique id
							$content[] = '<input type="hidden" data-unique="'.$this->unique_id.'">';
						$content[] = '</div>';
						
						$content[] = '<div class="'.$this->html_class_prefix.'result-by-quiz '.$this->html_name_prefix .'user-results-container" id="'. $this->html_name_prefix ."user-results-container-". $this->unique_id_in_class .'" data-id="'. $this->unique_id .'">';

							$content[] = '<div class="'.$this->html_class_prefix.'preloader">';
								$content[] = '<img src="'. QUIZ_MAKER_USER_DASHBOARD_ADMIN_URL .'/images/loaders/tail-spin.svg">';
							$content[] = '</div>';

						$content[] = '</div>';
					$content[] = '</div>';

					//Best Score
					$content[] = '<div class="'.$this->html_class_prefix.'content-best-score ays-quiz-user-dashboard-tab-content '.(($tab == 'best-score') ? $active_tab_class : $active_tab_empty).'">';
						$content[] = '<div class="'.$this->html_class_prefix.'quizzes-best-score">';

							//Best Score By Mode
							$content[] = '<select class="'.$this->html_class_prefix.'mode">';
								$content[] = '<option selected="true" disabled>';
									$content[] = __( 'Select Mode', $this->plugin_name );
								$content[] = '</option>';
								$content[] = '<option>';
									$content[] = __( 'Percentage', $this->plugin_name );
								$content[] = '</option>';
								$content[] = '<option>';
									$content[] = __( 'Points', $this->plugin_name );
								$content[] = '</option>';
							$content[] = '</select>';
							
							//Select quiz
							$content[] = '<select class="'.$this->html_class_prefix.'best-score-quizzes">';
								$content[] = '<option selected="true" disabled>';
									$content[] = __( 'Select quiz', $this->plugin_name );
								$content[] = '</option>';
								foreach ($results as $key => $result) {
									//Quiz ID
									$quiz_id = (isset($result['id']) && $result['id'] != '') ? absint($result['id']) : null;

									//Quiz Title
									$quiz_title = (isset($result['title']) && $result['title'] != '') ? sanitize_text_field(stripslashes($result['title'])) : '';

									if($quiz_id === null){
										return;
									}

									$content[] = '<option data-id="'.$quiz_id.'">';
										$content[] = $quiz_title;
									$content[] = '</option>';
								}
							$content[] = '</select>';

							//Check
							$content[] = '<button data-user="'.$user_id.'" class="'.$this->html_class_prefix.'check">';
								$content[] = __( 'Check', $this->plugin_name );
							$content[] = '</button>';

						$content[] = '</div>';
						$content[] = '<div class="'.$this->html_class_prefix.'best-score">';
							$content[] = '<div class="'.$this->html_class_prefix.'preloader">';
								$content[] = '<img src="'. QUIZ_MAKER_USER_DASHBOARD_ADMIN_URL .'/images/loaders/tail-spin.svg">';
							$content[] = '</div>';
						$content[] = '</div>';
					$content[] = '</div>';

					//User Progress
					$content[] = '<div class="'.$this->html_class_prefix.'content-user-progress ays-quiz-user-dashboard-tab-content '.(($tab == 'progress') ? $active_tab_class : $active_tab_empty).'" data-id="'. $this->unique_id .'" >';
						$content[] = '<div class="'.$this->html_class_prefix.'quizzes-user-progress">';

							//Best Score By Mode
							$content[] = '<select class="'.$this->html_class_prefix.'user-progress-mode">';
								$content[] = '<option selected="true" disabled>';
									$content[] = __( 'Select Mode', $this->plugin_name );
								$content[] = '</option>';
								$content[] = '<option>';
									$content[] = __( 'Score', $this->plugin_name );
								$content[] = '</option>';
								$content[] = '<option>';
									$content[] = __( 'Points', $this->plugin_name );
								$content[] = '</option>';
							$content[] = '</select>';
							
							//Select quiz
							$content[] = '<select class="'.$this->html_class_prefix.'user-progress-quizzes">';
								$content[] = '<option selected="true" disabled>';
									$content[] = __( 'Select quiz', $this->plugin_name );
								$content[] = '</option>';
								foreach ($results as $key => $result) {
									//Quiz ID
									$quiz_id = (isset($result['id']) && $result['id'] != '') ? absint($result['id']) : null;

									//Quiz Title
									$quiz_title = (isset($result['title']) && $result['title'] != '') ? sanitize_text_field(stripslashes($result['title'])) : '';

									if($quiz_id === null){
										return;
									}

									$content[] = '<option data-id="'.$quiz_id.'">';
										$content[] = $quiz_title;
									$content[] = '</option>';
								}
							$content[] = '</select>';

							//Check
							$content[] = '<button data-user="'.$user_id.'" class="'.$this->html_class_prefix.'user-progress-check">';
								$content[] = __( 'Check', $this->plugin_name );
							$content[] = '</button>';

						$content[] = '</div>';
						$content[] = '<div class="'.$this->html_class_prefix.'user-progress">';
							$content[] = '<div class="'.$this->html_class_prefix.'preloader">';
								$content[] = '<img src="'. QUIZ_MAKER_USER_DASHBOARD_ADMIN_URL .'/images/loaders/tail-spin.svg">';
							$content[] = '</div>';
						$content[] = '</div>';
					$content[] = '</div>';

				$content[] = '</div>';

			$content[] = '</div>';
		$content[] = '</div>';
		
		$content = implode( '', $content );

		return $content;
	}

	// Shorcode Method
	public function ays_generate_advanced_user_dashboard(){
		$this->enqueue_styles();
		$this->enqueue_scripts();

		$user_id = get_current_user_id();
		$unique_id = uniqid();
		
        $this->unique_id = $unique_id;
        $this->unique_id_in_class = $user_id . "-" . $unique_id;

		if( is_null($user_id) || $user_id == 0 ){
           $content = "<p style='text-align: center;font-style:italic;'>" . __( "You must log in to see your results.", $this->plugin_name ) . "</p>";

		   return str_replace(array("\r\n", "\n", "\r"), '', $content);
        }

		return str_replace(array("\r\n", "\n", "\r"), '', $this->create_content( $user_id ));
	}
}
