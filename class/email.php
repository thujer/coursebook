<?php
/*  +----------------------------------------------------------+
 *  |   Class Email                                            |
 *  |   Version 1.01.160517                                    |
 *  |   Author: Tomas Hujer (c) 2016                           |
 *  +----------------------------------------------------------+
 *
 */

class email {

    function __construct() {
    }

    /**
     * Send Email
     * @param $s_recipient Recipient mail address
     * @param $s_subject Email subject
     * @param $s_message Message content
     */
    static function send($s_recipient, $s_subject, $s_message) {

        require "./vendors/sendinblue/mailin.php";

        $mailin = new Mailin("https://api.sendinblue.com/v2.0", "AmOL1C2vMGQ7b3Xc");
        $data = array(
            "to" => array($s_recipient => $s_recipient),
            "from" => array("tomas@hujer.eu", "Tomas Hujer!"),
            "subject" => $s_subject,
            "html" => $s_message
        );

        return($mailin->send_email($data));
    }
}

?>
