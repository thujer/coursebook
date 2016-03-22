<?php

namespace Runtime\App\Controller;

use Runtime\controller;
use Runtime\database;
use Runtime\request;
use Runtime\layout;

/**
 * Class Meeting
 * @author  Tomas Hujer
 */
class meeting extends controller {

    var $template = array();

    /**
     * Person detail
     * @return string html output
     */
    public function detailAction() {

        $db = database::get_instance();

        $nl_id_meeting = request::get_var('nl_id_meeting', 'GET', 0);

        if(empty($nl_id_meeting)) {
            return "Doplňte prosím hodnotu nl_id_meeting !";
        }

        // Call stored procedure - Get person details
        $o_result = $db->call_stored_proc('get_meeting', array(
            'inl_id_meeting' => $nl_id_meeting
        ));

        // Get persons
        $a_person = $db->call_stored_proc('get_person_meeting_group_list', array(
            nl_id_meeting => $nl_id_meeting,
            nl_id_meeting_group => 1
        ));

        // Get stand-in persons
        $a_person_standin = $db->call_stored_proc('get_person_meeting_group_list', array(
            nl_id_meeting => $nl_id_meeting,
            nl_id_meeting_group => 2
        ));

        return $this->render_view(array(
            'o_result' => $o_result[0]['result'][0],
            'a_person' => $a_person[0]['result'],
            'a_person_standin' => $a_person_standin[0]['result'],
        ));
    }


    /**
     * @return mixed
     */
    public function listAction() {

        $db = database::get_instance();

        $b_ajax = request::get_var('b_ajax', 'REQUEST', 0);
        if($b_ajax)
            layout::get_instance()->disable();

        // Get persons
        $a_meeting = $db->call_stored_proc('get_meeting_list', array(
        ));

        return $this->render_view(array(
            'a_meeting' => $a_meeting[0]['result'],
        ));
    }

    /**
     * Store person detail
     */
    public function storePersonAction() {

        $db = database::get_instance();

        $b_ajax = request::get_var('b_ajax', 'REQUEST', 0);
        if($b_ajax)
            layout::get_instance()->disable();

        //$a_result = $db->call_stored_proc('get_meeting_list', $_REQUEST);
        $a_result = $db->proc('store_meeting_person', $_REQUEST);

        print_r($a_result);
    }


}



