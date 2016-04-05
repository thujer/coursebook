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

        $nl_id_meeting = request::get_var('nl_id_meeting', 'REQUEST', 0);

        if(empty($nl_id_meeting)) {
            return "Doplňte prosím hodnotu nl_id_meeting !";
        }

        // Call stored procedure - Get person details
        $o_result = $db->call_stored_proc('get_meeting', array(
            'nl_id_meeting' => $nl_id_meeting
        ));

        //dbg($o_result);

        /*
        $o_result = $db->call_stored_proc('get_meeting', array(
            'inl_id_meeting' => $nl_id_meeting
        ));
        */

        /*
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
        */

        // Get persons
        $a_person = $db->call_stored_proc('get_meeting_person', array(
            nl_id_meeting => $nl_id_meeting,
            b_after_limit => 0
        ));

        // Get stand-in persons
        $a_person_standin = $db->call_stored_proc('get_meeting_person', array(
            nl_id_meeting => $nl_id_meeting,
            b_after_limit => 1
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
        if($b_ajax) {
            layout::get_instance()->disable();
        }


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

        $a_result = $db->call_stored_proc('store_person', $_REQUEST);

        switch($a_result[1]['result'][0]->os_id_operation) {
            case 'insert': $s_message = 'Uložen nový zájemce'; break;
            case 'update': $s_message = 'Údaje osoby byly aktualizovány'; break;
            default: $s_message = 'Neznámá operace !'; break;
        }

        $a_result = $db->call_stored_proc('store_meeting_person', $_REQUEST + array(
                'nl_id_person' => $a_result[0]['result'][0]->onl_id_person
        ));

        /*
        dbg($a_result[0]['result'][0]->onl_id_person);
        dbg($a_result[1]['result'][0]->os_id_operation);
        dbg($s_message);
        dbg($a_result);
        */

        //request::set_var('nl_id_meeting', , 'REQUEST');
        $html_result = $this->detailAction();

        if($b_ajax) {
            layout::get_instance()->disable();
            echo $html_result;
            exit;
        }

        return $html_result;
    }


}



