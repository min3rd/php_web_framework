<?php

class JSManager
{
    public static function include($filename)
    {
        if (!file_exists(__DIR__ . "/../public/js/" . $filename)) {
            return false;
        }
        $httpMethod = HTTP_METHOD;
        $serverName = SERVER_NAME;
        $suffix = SERVER_SUFFIX;
        echo "<script type='text/javascript' src='{$httpMethod}{$serverName}{$suffix}public/js/{$filename}?v=" . CSS_JS_VERSION . "'></script>";
    }

    public static function includeRaw($filename)
    {
        if (!file_exists(__DIR__ . "/../public/" . $filename)) {
            return false;
        }
        $httpMethod = HTTP_METHOD;
        $serverName = SERVER_NAME;
        $suffix = SERVER_SUFFIX;
        echo "<script type='text/javascript' src='{$httpMethod}{$serverName}{$suffix}public/{$filename}?v=" . CSS_JS_VERSION . "'></script>";
    }
}
