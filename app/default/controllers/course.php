<?php

namespace Runtime\App\Controller;

use Runtime\controller;
use Runtime\database;
use Runtime\request;
use Runtime\layout;

/**
 * Class Course
 * @author  Tomas Hujer
 */
class course extends controller {

    var $template = array();

    /**
     * Person detail
     * @return string html output
     */
    public function detailAction() {

        $db = database::get_instance();

        $nl_id_course = request::get_var('nl_id_course', 'GET', 0);

        if(empty($nl_id_course)) {
            return "Doplňte prosím hodnotu nl_id_course !";
        }

        // Call stored procedure - Get person details
        $o_result = $db->call_stored_proc('get_course', array(
            'inl_id_course' => $nl_id_course
        ));

        // Get persons
        $a_person = $db->call_stored_proc('get_person_course_group_list', array(
            nl_id_course => $nl_id_course,
            nl_id_course_group => 1
        ));

        // Get stand-in persons
        $a_person_standin = $db->call_stored_proc('get_person_course_group_list', array(
            nl_id_course => $nl_id_course,
            nl_id_course_group => 2
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
        $a_course = $db->call_stored_proc('get_course_list', array(
        ));

        return $this->render_view(array(
            'a_course' => $a_course[0]['result'],
        ));
    }

}
