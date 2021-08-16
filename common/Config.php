<?php
define("LOCAL_ENV", 1);
define("PD_ENV", 2);
if (
    preg_match("/localhost/i", $_SERVER["HTTP_HOST"])
    || preg_match("/[0-9]+/i", $_SERVER["HTTP_HOST"])
) {
    define("GAME_ENV", LOCAL_ENV);
} else {
    define("GAME_ENV", PD_ENV);
}

if (GAME_ENV == LOCAL_ENV) {
    define("DB_HOSTNAME", "localhost");
    define("DB_USERNAME", "");
    define("DB_PASSWORD", "");
    define("DB_DATABASE", "");
    define("DB_PORT", null);
    define("DB_SOCKET", null);
    define("SERVER_SUFFIX", "viking-it.com/");
    define('SSL_ON', false);
    define("FEATURE_APCU", false);
} else {
    define("DB_HOSTNAME", "localhost");
    define("DB_USERNAME", "");
    define("DB_PASSWORD", "");
    define("DB_DATABASE", "");
    define("DB_PORT", null);
    define("DB_SOCKET", null);
    define('SERVER_SUFFIX', '');
    define('SSL_ON', true);
    define("FEATURE_APCU", false);
}
if (SSL_ON) {
    define('HTTP_METHOD', 'https://');
} else {
    define('HTTP_METHOD', 'http://');
}
if (isset($_SERVER['SERVER_NAME'])) {
    define('SERVER_NAME', $_SERVER['SERVER_NAME'] . '/');
} else {
    define('SERVER_NAME', 'localhost' . '/');
}

define('CSS_JS_VERSION', 202108160001);
