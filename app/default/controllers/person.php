<?php

namespace Runtime\App\Controller;

use Runtime\controller;
use Runtime\database;
use Runtime\request;
use Runtime\layout;

/**
 * Class person
 * @author  Tomas Hujer
 */
class person extends controller {

    var $template = array();

    /**
     * Person detail
     * @return string html output
     */
    public function detailAction() {

        $db = database::get_instance();

        $nl_id_child = request::get_var('id_child', 'GET', 0);

        if(empty($nl_id_child)) {
            return "Doplňte prosím hodnotu id_child !";
        }

        // Call stored procedure - Get person details
        $o_child = $db->call_stored_proc('get_person_details', array(
            'inl_id_child' => $nl_id_child
        ));

        $s_age_caption = (($o_child[0]['result'][0]->nl_age > 4)?'let':'roky');

        return $this->render_view(array(
            'o_child' => $o_child[0]['result'][0],
            'a_hobby_group' => $o_child[1]['result'],
            's_age_caption' => $s_age_caption
        ));
    }


    /**
     * Get classmates (by ajax)
     * @return mixed
     */
    public function getClassmateAction() {

        $db = database::get_instance();

        $nl_id_child = request::get_var('child_id', 'REQUEST', 0);
        $b_ajax = request::get_var('b_ajax', 'REQUEST', 0);

        // Get classmates
        $a_classmate = $db->call_stored_proc('get_person_classmate', array(
            'inl_id_child' => $nl_id_child
        ));

        if($b_ajax)
            layout::get_instance()->disable();

        return $this->render_view(array(
            'a_classmate' => $a_classmate[0]['result'],
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
        $a_person = $db->call_stored_proc('get_person_course_group_list', array(
            nl_id_course_group => 1
        ));

        // Get stand-in persons
        $a_person_standin = $db->call_stored_proc('get_person_course_group_list', array(
            nl_id_course_group => 2
        ));

        return $this->render_view(array(
            'a_person' => $a_person[0]['result'],
            'a_person_standin' => $a_person_standin[0]['result'],
        ));
    }

}
