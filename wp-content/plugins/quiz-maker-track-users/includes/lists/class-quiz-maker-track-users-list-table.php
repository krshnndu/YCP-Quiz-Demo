<?php
    class Track_Users_List_Table extends WP_List_Table{
        private $plugin_name;

        public function __construct($plugin_name) {
            $this->plugin_name = $plugin_name;

            parent::__construct( array(
                'singular' => __( 'Track User', $this->plugin_name ), //singular name of the listed records
                'plural'   => __( 'Track Users', $this->plugin_name ), //plural name of the listed records
                'ajax'     => false //does this table support ajax?
            ) );

            add_action( 'admin_notices', array( $this, 'track_users_notices' ) );
        }

        /**
         * Override of table nav to avoid breaking with bulk actions & according nonce field
         */
        public function display_tablenav( $which ) {
            ?>
            <div class="tablenav <?php echo esc_attr( $which ); ?>">
                
                <div class="alignleft actions">
                    <?php $this->bulk_actions( $which ); ?>
                </div>
                
                <?php
                $this->extra_tablenav( $which );
                $this->pagination( $which );
                ?>
                <br class="clear" />
            </div>
            <?php
        }
        
        public function extra_tablenav( $which ){
            global $wpdb;
            
            ?>

        <?php
        }

        
        /**
         * Retrieve customers data from the database
         *
         * @param int $per_page
         * @param int $page_number
         *
         * @return mixed
         */
        
        public static function track_users_per_page( $per_page = 20, $page_number = 1 ) {

            global $wpdb;
            $sql = "SELECT * FROM {$wpdb->prefix}aysquiz_track_users ";

            $where = array();

            if( ! empty($where) ){
                $sql .= " WHERE " . implode( " AND ", $where );
            }


            if ( ! empty( $_REQUEST['orderby'] ) ) {
                $order_by  = ( isset( $_REQUEST['orderby'] ) && sanitize_text_field( $_REQUEST['orderby'] ) != '' ) ? sanitize_text_field( $_REQUEST['orderby'] ) : 'id';

                if($order_by == 'user_name'){
                    $order_by = 'user_id';
                }

                $order_by .= ( ! empty( $_REQUEST['order'] ) && strtolower( $_REQUEST['order'] ) == 'asc' ) ? ' ASC' : ' DESC';

                $sql_orderby = sanitize_sql_orderby($order_by);

                if ( $sql_orderby ) {
                    $sql .= ' ORDER BY ' . $sql_orderby;
                } elseif ($sql_orderby == '') {
                    $sql .= ' ORDER BY user_id';
                }else {
                    $sql .= ' ORDER BY id DESC';
                }
            } else {
                $sql .= ' ORDER BY id DESC';
            }

            $sql .= " LIMIT $per_page";
            $sql .= ' OFFSET ' . ( $page_number - 1 ) * $per_page;
            $result = $wpdb->get_results( $sql, 'ARRAY_A' );

            return $result;

        }

        /**
         * Delete a customer record.
         *
         * @param int $id customer ID
         */
        public static function delete_reports( $id ) {
            global $wpdb;

            $wpdb->delete(
                "{$wpdb->prefix}aysquiz_track_users",
                array( 'id' => $id ),
                array( '%d' )
            );
        }


        /**
         * Returns the count of records in the database.
         *
         * @return null|string
         */
        public static function record_count() {
            global $wpdb;

            $sql = "SELECT COUNT(*) FROM {$wpdb->prefix}aysquiz_track_users";

            return $wpdb->get_var( $sql );
        }


        /** Text displayed when no customer data is available */
        public function no_items() {
            echo __( 'There are no results yet.', $this->plugin_name );
        }
        
        function column_user_name( $item ) {
            global $wpdb;

            $delete_nonce = wp_create_nonce( $this->plugin_name . '-delete-each-result' );
            
            $user_id = intval($item['user_id']);
            $class_red = '';

            if($user_id == 0){

                $name = "Guest";

            }else{
                $name = '';
                $user = get_userdata($user_id);

                if($user !== false){
                    $name = $user->data->display_name;
                }else{
                    $name = __( "Deleted user", $this->plugin_name );
                }

            }
            
            return $name;
        }

        function column_tab_change_time( $item ) {
            global $wpdb;
            
            $tab_change_time = (isset($item['tab_change_time']) && $item['tab_change_time'] != '') ? $item['tab_change_time'] : 0;
            
            if ( $tab_change_time == 0) {
                $time = 0;
            }else{
                $time = $tab_change_time.'s';
            }

            return $time;
        }

        /**
         * Render a column when no column specific method exist.
         *
         * @param array $item
         * @param string $column_name
         *
         * @return mixed
         */
        public function column_default( $item, $column_name ) {
            switch ( $column_name ) {
                case 'user_name':
                case 'user_ip':
                case 'start_date':
                case 'end_date':
                case 'tab_change_time':
                case 'id':
                    return $item[ $column_name ];
                    break;
                default:
                    return print_r( $item, true ); //Show the whole array for troubleshooting purposes
            }
        }

        /**
         * Render the bulk edit checkbox
         *
         * @param array $item
         *
         * @return string
         */
        function column_cb( $item ) {
            return sprintf(
                '<input type="checkbox" name="bulk-delete[]" value="%s" />', $item['id']
            );
        }


        /**
         * Method for name column
         *
         * @param array $item an array of DB data
         *
         * @return string
         */
        
        /**
         * Method for name column
         *
         * @param array $item an array of DB data
         *
         * @return string
         */
        
        /**
         *  Associative array of columns
         *
         * @return array
         */
        function get_columns() {
            $columns = array(
                'cb'                => '<input type="checkbox" />',
                'user_name'         => __( 'User Name', $this->plugin_name ),
                'user_ip'           => __( 'User IP', $this->plugin_name ),
                'start_date'        => __( 'Start Date', $this->plugin_name ),
                'end_date'          => __( 'End Date', $this->plugin_name ),
                'tab_change_time'   => __( 'Tab Change Duration', $this->plugin_name ),
                'id'                => __( 'ID', $this->plugin_name ),
            );

            return $columns;
        }


        /**
         * Columns to make sortable.
         *
         * @return array
         */
        public function get_sortable_columns() {
            $sortable_columns = array(
                'user_name'  => array( 'user_name', true ),
                'id'         => array( 'id', true ),
            );

            return $sortable_columns;
        }

        /**
         * Returns an associative array containing the bulk action
         *
         * @return array
         */
        public function get_bulk_actions() {
            $actions = array(
                'bulk-delete' => 'Delete',
            );

            return $actions;
        }

        /**
         * Handles data query and filter, sorting, and pagination.
         */
        public function prepare_items() {
            $this->_column_headers = $this->get_column_info();

            /** Process bulk action */
            $this->process_bulk_action();

            $per_page = $this->get_items_per_page('track_users_per_page', 20);

            $current_page = $this->get_pagenum();
            $total_items = self::record_count();

            if(! empty( $_REQUEST['orderby'] ) &&  $_REQUEST['orderby'] == 'quiz_complete' ){
                $total_items = self::record_complete_filter_count();
            }

            $this->set_pagination_args(array(
                'total_items' => $total_items, //WE have to calculate the total number of items
                'per_page' => $per_page //WE have to determine how many items to show on a page
            ));

            $this->items = self::track_users_per_page( $per_page, $current_page );
        }

        public function process_bulk_action() {
            //Detect when a bulk action is being triggered...
            $message = 'deleted';
            if ( 'delete' === $this->current_action() ) {

                // In our file that handles the request, verify the nonce.
                $nonce = esc_attr( $_REQUEST['_wpnonce'] );

                if ( ! wp_verify_nonce( $nonce, $this->plugin_name . '-delete-result' ) ) {
                    die( 'Go get a life script kiddies' );
                }
                else {
                    self::delete_reports( absint( $_GET['result'] ) );

                    // esc_url_raw() is used to prevent converting ampersand in url to "#038;"
                    // add_query_arg() return the current url

                    $url = esc_url_raw( remove_query_arg(array('action', 'quiz_track_users', '_wpnonce') ) ) . '&status=' . $message;
                    wp_redirect( $url );
                }

            }


            // If the delete bulk action is triggered
            if ( ( isset( $_POST['action'] ) && $_POST['action'] == 'bulk-delete' )
                || ( isset( $_POST['action2'] ) && $_POST['action2'] == 'bulk-delete' )
            ) {

                $delete_ids = ( isset( $_POST['bulk-delete'] ) && ! empty( $_POST['bulk-delete'] ) ) ? esc_sql( $_POST['bulk-delete'] ) : array();

                // loop over the array of record IDs and delete them
                foreach ( $delete_ids as $id ) {
                    self::delete_reports( $id );
                }

                // esc_url_raw() is used to prevent converting ampersand in url to "#038;"
                // add_query_arg() return the current url

                $url = esc_url_raw( remove_query_arg(array('action', 'quiz_track_users', '_wpnonce') ) ) . '&status=' . $message;
                wp_redirect( $url );
            }
        }

        public function track_users_notices(){
            $status = (isset($_REQUEST['status'])) ? sanitize_text_field( $_REQUEST['status'] ) : '';
    
            if ( empty( $status ) )
                return;
    
            if ( 'deleted' == $status )
                $updated_message = esc_html( __( 'Tarck users deleted.', $this->plugin_name ) );
    
            if ( empty( $updated_message ) )
                return;
    
            ?>
            <div class="notice notice-success is-dismissible">
                <p> <?php echo $updated_message; ?> </p>
            </div>
            <?php
        }
    }
?>