<?php

/*

Copyright 2014 Dario Curvino (email : d.curvino@tiscali.it)

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit( 'You\'re not allowed to see this page' );
} // Exit if accessed directly

/**
 * Create a new table class that will extend the WP_List_Table
 */
class YasrStats extends Yasr_WP_List_Table {

    private $active_tab;

    public function __construct($active_tab) {
        parent::__construct();
        $this->active_tab = $active_tab;
    }
    /**
     * Prepare the items for the table to process
     *
     * @return Void
     */
    public function prepare_items() {
        $columns  = $this->get_columns();
        $hidden   = $this->get_hidden_columns();
        $sortable = $this->get_sortable_columns();

        //print bulk_Actions
        $this->get_bulk_actions();
        $this->process_bulk_action();

        if ($this->active_tab === 'logs' || $this->active_tab === '') {
            $data = YasrDatabaseRatings::getAllVisitorVotes();
        }
        else if($this->active_tab === 'logs_multi') {
            $data = YasrMultiSetData::returnAllLogMulti();
        }
        else if($this->active_tab === 'overall') {
            $data = YasrDatabaseRatings::getAllOverallRatings();
        }

        usort($data, array( $this, 'sort_data' ));

        $perPage     = 25;
        $currentPage = $this->get_pagenum();
        $totalItems  = count($data);

        $this->set_pagination_args(
            array(
                'total_items' => $totalItems,
                'per_page'    => $perPage
            )
        );

        $data = array_slice($data, (( $currentPage - 1 ) * $perPage ), $perPage);

        $this->_column_headers = array( $columns, $hidden, $sortable );
        $this->items           = $data;

    }

    /**
     * Override the parent columns method. Defines the columns to use in your listing table
     *
     * @return Array
     */
    public function get_columns() {

        $columns = array(
            'cb'           => '<input type="checkbox" />',
            'id'           => 'ID',
            'post_id'      => 'Title',
            'vote'         => 'Vote',
            'user_id'      => 'User ID',
            'date'         => 'Date'
        );

        if($this->active_tab === 'logs_multi') {
            //insert multiset and field name
            $columns = array_slice($columns, 0, 3, true) +
                array('set_type' => 'MultiSet', 'field_id' => 'Field Name') +
                array_slice($columns, 3, count($columns) - 1, true) ;
        }

        if (YASR_ENABLE_IP === 'yes') {
            $columns['ip'] = 'IP';
        }
        return $columns;
    }

    /**
     * Define which columns are hidden
     *
     * @return Array
     */
    public function get_hidden_columns() {
        return array('id');
    }

    /**
     * Define the sortable columns
     *
     * @return Array
     */
    public function get_sortable_columns() {

        $sortable_columns = array(
            'post_id' => array('post_id', false),
            'user_id' => array('user_id', false),
            'vote'    => array('vote', false),
            'date'    => array('date', false),
            'ip'      => array('ip', false)
        );

        if($this->active_tab === 'logs_multi') {
            $sortable_columns['set_type'] =  array('set_type', false);
            $sortable_columns['field_id'] =  array('field_id', false);
        }

        return $sortable_columns;
    }

    /**
     * Define what data to show on each column of the table
     *
     * @param Array $item Data
     * @param String $column_name - Current column name
     *
     * @return Mixed|void
     */
    protected function column_default( $item, $column_name ) {

        global $wpdb;
        $set_id = '';

        if (isset($item['set_type'])) {
            $set_id = (int)$item['set_type'];
        }

        switch ($column_name) {
            case 'post_id':
                $post_id = $item[$column_name];

                $title_post = wp_strip_all_tags(get_the_title($post_id));
                $link       = get_permalink($post_id);

                return '<a href="' . $link . '">' . $title_post . '</a>';

            case 'user_id':
                $user_id = $item[$column_name];

                $user = get_user_by('id', $user_id);

                //If !user means that the vote are anonymous
                if ($user === false) {
                    $user             = (object) array( 'user_login' );
                    $user->user_login = __('anonymous', 'yet-another-stars-rating');
                }

                return $user->user_login;

            case 'set_type':
                $data = $wpdb->get_results(
                    $wpdb->prepare(
                        "SELECT set_name 
                                FROM " . YASR_MULTI_SET_NAME_TABLE . " 
                                WHERE set_id = %d", $set_id),
                    ARRAY_A);

                if(!empty($data)) {
                    return $data[0]['set_name'];
                }

                return __('Multi Set doesn\'t exists', 'yet-another-stars-rating');

            case 'field_id':
                $field_id = $item[$column_name];
                $data = $wpdb->get_results(
                    $wpdb->prepare(
                        "SELECT field_name 
                                FROM " . YASR_MULTI_SET_FIELDS_TABLE . " 
                                WHERE parent_set_id = %d
                                AND field_id = %d",
                        $set_id, $field_id),
                    ARRAY_A);

                if(!empty($data)) {
                    return $data[0]['field_name'];
                }

                return __('Field doesn\'t exists', 'yet-another-stars-rating');

            case 'date':
                $date = $item[$column_name];
                if($item[$column_name] === '0000-00-00 00:00:00') {
                    $date = __('Imported Data', 'yet-another-stars-rating');
                }
                return $date;

            //All other columns must return their content
            case 'vote':
            case 'ip':
                return $item[$column_name];
        }

    }

    /**
     * Allows you to sort the data by the variables set in the $_GET
     *
     * @return Mixed
     */
    protected function sort_data( $a, $b ) {

        // Set defaults (just need to avoid undefined variable at first load,
        // it is already ordered with the query
        $orderby = 'date';
        $order   = 'desc';

        // If orderby is set, use this as the sort column
        if (!empty($_GET['orderby'])) {
            $orderby = $_GET['orderby'];
        }

        // If order is set use this as the order
        if (!empty($_GET['order'])) {
            $order = $_GET['order'];
        }

        $result = strcmp($a[ $orderby ], $b[ $orderby ]);

        if ($order === 'asc') {
            return $result;
        }

        return - $result;
    }


    protected function get_bulk_actions() {
        $actions = array(
            'delete' => 'Delete'
        );
        return $actions;
    }

    protected function column_cb($item) {

        return sprintf(
            "<input type='checkbox' name='yasr_logs_votes_to_delete[]' id='{$item['id']}' value='{$item['id']}' />"
        );
    }

    //process bulk action
    protected function process_bulk_action() {
        if ($this->current_action() === 'delete') {
            check_admin_referer( 'yasr-delete-stats-logs', 'yasr-nonce-delete-stats-logs' );

            global $wpdb;

            $table = YASR_LOG_TABLE;

            if($this->active_tab === 'logs_multi') {
                $table = YASR_LOG_MULTI_SET;
            }

            foreach ($_POST['yasr_logs_votes_to_delete'] as $log_id) {
                //force to be an int
                $log_id = (int)$log_id;

                //If user is deleting an overall rating value, use delete_meta and return
                if($this->active_tab === 'overall') {
                    delete_meta($log_id);
                    return;
                }

                //delete the log id
                $wpdb->delete(
                    $table,
                    array(
                        'id' => $log_id
                    ),
                    array( '%d' )
                );

            }
        }

    }

}