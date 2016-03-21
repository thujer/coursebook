<?php

    namespace Runtime\Config;

    define('LN',                                "\n");
    define('BR',                                "<br />");

    if(!defined('DS'))
        define('DS',                            DIRECTORY_SEPARATOR);
    
    define('CONFIG_APP_NAME',                   'MeetingBook');                         // Textový název aplikace, použito jako default title
    define('CONFIG_APP_ID',                     'default');                             // subdir aplikace /app/<subdir>

    error_reporting(E_ALL ^ E_NOTICE);
    //error_reporting(E_ALL);
    ini_set('display_errors',   true);
    ini_set('html_errors',      true);

    define('CONFIG_DOMAIN',                     $_SERVER["HTTP_HOST"]);
    define('CONFIG_WEB_ROOT',                   "http://".CONFIG_DOMAIN);

    define('CONFIG_DEBUG',                      1);                                     // Povolení chybových a ladících výpisů
    define('CONFIG_SEO_MAP_FILE',               1);                                     // Povolení mapování překladů SEO (non constructor/action)

    define('CONFIG_INLINE_JS',                  false);                                  // Skripty budou nacteny inline v HTML kodu
    define('CONFIG_INLINE_CSS',                 false);                                  // Styly budou nacteny inline v HTML kodu

    define('CONFIG_WEB_OFFLINE',                0);                                      // 1=web je offline, probiha aktualizace

    define('CONFIG_DEFAULT_TITLE',              CONFIG_APP_NAME);
    define('CONFIG_DEFAULT_KEYWORDS',           'Meeting book, plánovač setkání');
    define('CONFIG_DEFAULT_DESCRIPTION',        'Meeting book, plánovač setkání');
    define('CONFIG_DEFAULT_ROBOTS',             'noindex, follow');
    define('CONFIG_DEFAULT_CONTENT_TYPE',       'text/html; charset=UTF-8');
    define('CONFIG_DEFAULT_FAVICON',            'favicon.ico');

    define('CONFIG_CORE_DIR',                   'class');

    define('CONFIG_LAYOUT',                     'front');
    define('CONFIG_LAYOUT_DIR',                 'app' . DS . CONFIG_APP_ID . DS . "layouts" . DS);

    define('CONFIG_APP_DIR',                    'app'.DS.CONFIG_APP_ID);                // Adresář aplikace
    define('CONFIG_APP_CONTROLLERS',            CONFIG_APP_DIR.DS . 'controllers');     // Adresář s definicí objektů aplikace
    define('CONFIG_APP_MODELS',                 CONFIG_APP_DIR.DS . 'models');          // Adresář s definicǐ datových struktur
    define('CONFIG_APP_MEDIA',                  CONFIG_APP_DIR.DS . 'media');           // Adresář s multimediálními soubory aplikace
    define('CONFIG_APP_CONFIG',                 CONFIG_APP_DIR.DS . 'config');          // Adresář s nastavenim aplikace
    define('CONFIG_APP_LAYOUT',                 CONFIG_APP_DIR.DS . 'layouts');         // Adresář s layoutem

    define('CONFIG_LOGIN_USER_TIMEOUT',         7200);                                  // cas automatickeho odhlaseni pro uzivatele v sekundach (60min)
    
    define('CONFIG_WORD_DELIMITER',             '-');                                   // Controller & actions names word separator
    

