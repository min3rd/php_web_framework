<?php

class CSSManager
{
    public static function include($filename)
    {
        if (!file_exists(__DIR__ . "/../public/css/" . $filename)) {
            return false;
        }
        $httpMethod = HTTP_METHOD;
        $serverName = SERVER_NAME;
        $suffix = SERVER_SUFFIX;
        echo "<link rel='stylesheet' href='{$httpMethod}{$serverName}{$suffix}public/css/{$filename}?v=" . CSS_JS_VERSION . "' type='text/css'>";
    }

    public static function includeRaw($filename)
    {
        if (!file_exists(__DIR__ . "/../public/" . $filename)) {
            return false;
        }
        $httpMethod = HTTP_METHOD;
        $serverName = SERVER_NAME;
        $suffix = SERVER_SUFFIX;
        echo "<link rel='stylesheet' href='{$httpMethod}{$serverName}{$suffix}public/{$filename}?v=" . CSS_JS_VERSION . "' type='text/css'>";
    }
}
