<?php
/*  +----------------------------------------------------------+
 *  |   Class SMS                                              |
 *  |   Version 1.01.160512                                    |
 *  |   Author: Tomas Hujer (c) 2016                           |
 *  +----------------------------------------------------------+
 *
 */

class sms {

    function __construct() {
    }


    /**
     * Send SMS
     * @param $s_recipient Recipient number
     * @param $s_message Message content
     */
    static function send($s_recipient, $s_message) {

        require "./vendors/sendinblue/sms_api.php";

        $mailin = new MailinSms('AmOL1C2vMGQ7b3Xc');

        $mailin->addTo($s_recipient)

            ->setFrom('JogaWeb') // If numeric, then maximum length is 17 characters and if alphanumeric maximum length is 11 characters.
            ->setText($s_message) // 160 characters per SMS.
            ->setTag('JogaWeb')
            ->setType('') // Two possible values: marketing or transactional.
            ->setCallback('http://joga.spsy.eu/');

        return $mailin->send();
    }


    /**
     * Generate random string from defined chars
     * @param int $length Generated string length
     * @return string random string
     */
    static function get_random_string($length = 40) {
        if ((!is_int($length)) ||
            ($length < 1)
        ) {
            $this->trigger_error('Invalid length for random string');
            //exit();
        }

        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ123456789";
        $randstring = '';
        $maxvalue = strlen($chars) - 1;

        for ($i = 0; $i < $length; $i++) {
            $randstring .= substr($chars, rand(0, $maxvalue), 1);
        }

        return $randstring;
    }
}

?>
