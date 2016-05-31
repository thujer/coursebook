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

        // Get unconfirmed persons
        $a_person_unconfirmed = $db->call_stored_proc('get_meeting_person', array(
            nl_id_meeting => $nl_id_meeting,
            b_after_limit => 0,
            b_unconfirmed => 1
        ));

        return $this->render_view(array(
            'o_result' => $o_result[0]['result'][0],
            'a_person' => $a_person[0]['result'],
            'a_person_standin' => $a_person_standin[0]['result'],
            'a_person_unconfirmed' => $a_person_unconfirmed[0]['result'],
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

        $html_result = '';

        $a_error = array();
        $a_message = array();

        if($a_result[0]['error']) {
            $a_error[] = $a_result[0]['error'];
        } else {

            $s_id_contact_state = $a_result[1]['result'][0]->os_id_operation;
            $nl_id_person = $a_result[0]['result'][0]->onl_id_person;

            switch($s_id_contact_state) {
                case 'update':
                    //$a_message[] = 'Kontakt byl aktualizován';

                case 'insert':

                    $s_pin = \tokens::get_random_string(5);

                    //$a_message[] = 'Založen nový kontakt '.$nl_id_person;

                    $a_result = $db->call_stored_proc('store_meeting_person', array(
                        'nl_id_meeting' => request::get_var('nl_id_meeting'),
                        'nl_id_person' => $nl_id_person,
                        's_pin' => $s_pin
                    ));

                    if($a_result[0]['error']) {
                        $a_error[] = $a_result[0]['error'];
                    }

                    $a_message[] = "Kontakt $nl_id_person přidán do seznamu zájemců";

                    break;

                default:
                    $a_message[] = 'Neznámá operace !';
                    break;
            }

        }

        if($b_ajax) {
            layout::get_instance()->disable();
            echo json_encode(array(
                's_content' => $html_result,
                'a_error' => $a_error,
                'a_message' => $a_message
            ));
            exit;
        }

        return $html_result;
    }




    /**
     * Send confirm SMS
     */
    public function confirmBySMSAction() {

        $db = database::get_instance();

        $b_ajax = request::get_var('b_ajax');
        $nl_id_meeting = request::get_var('nl_id_meeting');
        $nl_id_person = request::get_var('nl_id_person');
        $s_pin = request::get_var('s_pin');

        $a_result = $db->call_stored_proc('get_meeting_person', array(
            'nl_id_meeting' => $nl_id_meeting,
            'nl_id_person' => $nl_id_person,
        ));

        if($a_result[0]['num_rows']) {
            if(!empty($s_pin)) {

                if($s_pin == $a_result[0]['result'][0]->s_pin) {

                    $a_result = $db->call_stored_proc('store_meeting_person', array(
                        'nl_id_meeting' => $nl_id_meeting,
                        'nl_id_person' => $nl_id_person,
                        'b_confirmed' => 1
                    ));

                    $a_message[] = "Účastník byl potvrzen a přidán do seznamu";
                    $b_reload = 1;
                } else {
                    $a_error[] = "Chybný PIN !";
                }

            } else {
                $s_pin = $a_result[0]['result'][0]->s_pin;

                $a_person = $db->call_stored_proc('get_person', array(
                    'nl_id_person' => $nl_id_person
                ));
                $a_person_phone = $a_person[0]['result'][0]->s_phone;

                $o_result = \sms::send($a_person_phone, "Vas potvrzovaci kod: $s_pin zadejte prosim do potvrzovaciho pole na webu.");
                //$o_result =  '{"status":"OK","number_sent":1,"to":"00420777748740","sms_count":1,"credits_used":3.8,"remaining_credit":164.8,"reference":{"1":"ic39x1tx2em4pqvk3rf"}}';

                $o_result = json_decode($o_result);

                if($o_result->status == 'OK') {
                    $a_message[] = "SMS zpráva odeslána na <span title=\"{$o_result->to}\">číslo účastníka</span>, zadejte pin a odešlete znovu tlačítkem Potvrdit či Odebrat";
                } else {
                    $a_message[] = "Při odesílání SMS zprávy nastaly potíže";
                }

            }
        } else {
            $a_message[] = "Osoba nebo setkání nebyly nalezeny";
        }


        //dbg($o_result);
        // {"status":"OK","number_sent":1,"to":"00420777748740","sms_count":1,"credits_used":3.8,"remaining_credit":164.8,"reference":{"1":"ic39x1tx2em4pqvk3rf"}}

        if($b_ajax) {
            layout::get_instance()->disable();
            echo json_encode(array(
                's_content' => $html_result,
                'a_error' => $a_error,
                'a_message' => $a_message,
                'b_reload' => $b_reload
            ));
            exit;
        }

        return $html_result;
    }

    
    /**
     * Send confirm email
     */
    public function confirmByEmailAction() {

        $db = database::get_instance();

        $b_ajax = request::get_var('b_ajax');
        $nl_id_meeting = request::get_var('nl_id_meeting');
        $nl_id_person = request::get_var('nl_id_person');
        $s_pin = request::get_var('s_pin');

        $a_result = $db->call_stored_proc('get_meeting_person', array(
            'nl_id_meeting' => $nl_id_meeting,
            'nl_id_person' => $nl_id_person,
        ));

        if($a_result[0]['num_rows']) {
            if(!empty($s_pin)) {

                if($s_pin == $a_result[0]['result'][0]->s_pin) {

                    $a_result = $db->call_stored_proc('store_meeting_person', array(
                        'nl_id_meeting' => $nl_id_meeting,
                        'nl_id_person' => $nl_id_person,
                        'b_confirmed' => 1
                    ));

                    $a_message[] = "Účastník byl potvrzen a přidán do seznamu";
                    $b_reload = 1;
                } else {
                    $a_error[] = "Chybný PIN !";
                }

            } else {
                $s_pin = $a_result[0]['result'][0]->s_pin;

                $a_person = $db->call_stored_proc('get_person', array(
                    'nl_id_person' => $nl_id_person
                ));
                $a_person_email = $a_person[0]['result'][0]->s_email;

                @$o_result = \email::send($a_person_email, "Přihlašovací údaje", "Vaše heslo: $s_pin zadejte prosim do potvrzovaciho pole na webu.");

                //$o_result = json_decode($o_result);
                //dbg($o_result);

                if($o_result['code'] == 'success') {
                    $a_message[] = "Email byl odeslán na <span title=\"{$a_person_email}\">adresu účastníka</span>, zadejte heslo a odešlete znovu tlačítkem";

                    if(!empty($o_result['message'])) {
                        $a_message[] = $o_result['message'];
                    }
                } else {
                    $a_message[] = "Při odesílání e-mailu nastaly potíže";

                    if(!empty($o_result['message'])) {
                        $a_message[] = $o_result['message'];
                    }

                }

            }
        } else {
            $a_message[] = "Osoba nebo setkání nebyly nalezeny";
        }


        //dbg($o_result);
        // {"status":"OK","number_sent":1,"to":"00420777748740","sms_count":1,"credits_used":3.8,"remaining_credit":164.8,"reference":{"1":"ic39x1tx2em4pqvk3rf"}}

        if($b_ajax) {
            layout::get_instance()->disable();
            echo json_encode(array(
                's_content' => $html_result,
                'a_error' => $a_error,
                'a_message' => $a_message,
                'b_reload' => $b_reload
            ));
            exit;
        }

        return $html_result;
    }

    /**
     * Send confirm SMS
     */
    public function removeMeetingPersonAction() {

        $db = database::get_instance();

        $b_ajax = request::get_var('b_ajax', 'REQUEST', 0);
        $nl_id_meeting = request::get_var('nl_id_meeting', 'REQUEST', 0);
        $nl_id_person = request::get_var('nl_id_person', 'REQUEST', 0);
        $s_pin = request::get_var('s_pin');

        $a_result = $db->call_stored_proc('get_meeting_person', array(
            'nl_id_meeting' => $nl_id_meeting,
            'nl_id_person' => $nl_id_person,
        ));

        if($a_result[0]['num_rows']) {
            if (!empty($s_pin)) {

                if ($s_pin == $a_result[0]['result'][0]->s_pin) {

                    $a_result = $db->call_stored_proc('remove_meeting_person', array(
                        'nl_id_meeting' => $nl_id_meeting,
                        'nl_id_person' => $nl_id_person
                    ));

                    //dbg($a_result);

                    $a_message[] = "Osoba byla odstraněna z tohoto setkání";
                } else {
                    $a_error[] = "Chybný PIN !";
                }
            } else {

                $a_person = $db->call_stored_proc('get_person', array(
                    'nl_id_person' => $nl_id_person
                ));
                $a_person_phone = $a_person[0]['result'][0]->s_phone;

                $s_pin = $a_result[0]['result'][0]->s_pin;

                $o_result = \sms::send($a_person_phone, "Vas potvrzovaci kod: $s_pin zadejte prosim do potvrzovaciho pole na webu.");
                //$o_result =  '{"status":"OK","number_sent":1,"to":"00420777748740","sms_count":1,"credits_used":3.8,"remaining_credit":164.8,"reference":{"1":"ic39x1tx2em4pqvk3rf"}}';

                $o_result = json_decode($o_result);

                if($o_result->status == 'OK') {
                    $a_message[] = "SMS zpráva odeslána na <span title=\"{$o_result->to}\">číslo účastníka</span>, zadejte pin a odešlete znovu tlačítkem";
                } else {
                    $a_message[] = "Při odesílání SMS zprávy nastaly potíže";
                }
            }
        } else {
            $a_message[] = "Osoba nebo setkání nebyly nalezeny";
        }

        if($b_ajax) {
            layout::get_instance()->disable();
            echo json_encode(array(
                's_content' => $html_result,
                'a_error' => $a_error,
                'a_message' => $a_message
            ));
            exit;
        }

        return $html_result;
    }

}



