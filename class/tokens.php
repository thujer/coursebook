<?
/*  +----------------------------------------------------------+
 *  |   Class Tokens                                           |
 *  |   Version 1.03.110520                                    |
 *  |   Author: Tomas Hujer (c) 2010-2011                      |
 *  +----------------------------------------------------------+
 *
 * 1.02.110413 - Upraveno generovani tokenu z automatickeho na vyzadane pomoci metody tokens::generate();
 *               Pri konstruktoru jsou rusenu pouze neplatne tokeny
 *
 * 1.03.110514 - Zmena nazvu session registeru "token" na "xtoken" z duvodu kompaktibility s PHP5.3,
 *               kde je token pravdepodobne systemova session promenna a cteni ci zapis zpusobi zastaveni skriptu
 *
 * 1.03.110520 - Nazev "xtoken" vracen zpet na "token", namisto toho byl pouzit prefix v rutinach request
 *               Nejednalo se o systemovou promennou, ale konflikt mezi registrem v session a autoload,
 *               ktery vytvori v session objekt namisto pole pokud je nazev registru shodny s nazvem tridy
 */

$tokens_error = null;

class tokens
{

    function __construct()
    {
        $token_stored = self::get_var('value');

        $url = request::get_current_url();
        $action = request::get_var('id', 'REQUEST');
        $agent = request::get_var('HTTP_USER_AGENT', 'SERVER');
        $expiration = time() + 3600;                        // Set expiration to 1 hour

        if (empty($token_stored) ||
            ($expiration < time()) ||
            ($agent != self::get_var('agent'))
        ) {
            self::destroy();
        }

        //html::show($_SERVER);
    }

    static function get_error_msg()
    {
        global $tokens_error;

        return $tokens_error;
    }


    static function generate($params = 'show_message')
    {
        $token_stored = self::get_var('value');

        $url = request::get_current_url();
        $action = request::get_var('id', 'REQUEST');
        $agent = request::get_var('HTTP_USER_AGENT', 'SERVER');
        $expiration = time() + 3600;                        // Set expiration to 1 hour

        $rand_str = self::get_random_string();
        $value = sha1($rand_str . time());

        self::set_var('value', $value);
        self::set_var('expiration', $expiration);
        self::set_var('url', $url);
        self::set_var('action', $action);
        self::set_var('agent', $agent);
        self::set_var('params', $params);

        // html::show($_SESSION['xtoken'], 'NEW TOKEN SAVED time:'.time().', timeout: '.$timeout.BR.__FILE__.__LINE__);
    }


    /*
    static function generate_ajax_token()
    {
        Pri vygenerovani stranky zapsat otisk SHA ajax tokenu a hesla do session

        V ajax.php zkontrolovat, zda je token platny, zda souhlasi otisk SHA tokenu a hesla, pripadne take zda je uzivatel prihlasen
    }
    */


    // --------------------------------------------------------------------------------------
    //! Test if token value presents
    /*!
        @return True = presents
    */
    // --------------------------------------------------------------------------------------
    static function generated()
    {
        $token_stored = self::get_var('value');
        return (!empty($token_stored));

    }


    // --------------------------------------------------------------------------------------
    //! Test current token value
    /*!
        @return current token value
    */
    // --------------------------------------------------------------------------------------
    static function get_value()
    {
        return (request::get_register_var('token', 'value'));
    }


    // --------------------------------------------------------------------------------------
    //! Set external variable
    // --------------------------------------------------------------------------------------
    static function set_var($name, $value)
    {
        request::set_register_var('token', $name, $value);
    }


    // --------------------------------------------------------------------------------------
    //! Return stored external variable
    /*!
        @return variable value
    */
    // --------------------------------------------------------------------------------------
    static function get_var($name)
    {
        return (request::get_register_var('token', $name, 'SESSION'));
    }


    // --------------------------------------------------------------------------------------
    //! Test token value and expiration
    /*!
        @return Boolean         true=token is ok
    */
    // --------------------------------------------------------------------------------------
    static function check_token()
    {
        global $screen;
        global $tokens_error;

        $token_saved = self::get_var('value');
        $token_request = request::get_var('token', 'REQUEST');

        //html::show($token_saved.' vs '.$token_request, __FILE__.__LINE__);

        $token_validity = false;

        if (!empty($token_saved)) {
            if ($token_saved == $token_request) {
                $token_expiration = self::get_var('expiration');

                if (time() < $token_expiration)
                    $token_validity = true;
                else                                        // 1300931259 / 1300927613
                    $tokens_error = 'TOKEN ERROR - TIME: ' . time() . ' / ' . $token_expiration;
            } else
                $tokens_error = 'TOKEN ERROR - REQUEST: ' . $token_saved . ' / ' . $token_request;
        } else
            $tokens_error = 'TOKEN ERROR - EMPTY';

        if (!$token_validity)
            self::destroy();

        return $token_validity;
    }


    // --------------------------------------------------------------------------------------
    //! Destroy token data
    // --------------------------------------------------------------------------------------
    static function destroy()
    {
        //request::destroy('xtoken', 'SESSION');
        request::destroy_register('token');
    }


    // --------------------------------------------------------------------------------------
    //! Generate random string from defined chars
    /*!
        @param $length Generated string length
        @return Current time in Unix format
    */
    // --------------------------------------------------------------------------------------
    static function get_random_string($length = 40)
    {
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
