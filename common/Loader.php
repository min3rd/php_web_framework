<?php

/**
 * @author vanhminh.vu
 * @todo load all source file
 */
$directory_separator = DIRECTORY_SEPARATOR;
$core_folder = __DIR__ . "{$directory_separator}..{$directory_separator}core";
$core_files = scandir($core_folder);
if ($core_files !== false) {
    if (is_array($core_files) && count($core_files) > 0) {
        foreach ($core_files as $file_name) {
            if (strpos($file_name, ".php") === false) {
                continue;
            }
            $file_path = $core_folder . DIRECTORY_SEPARATOR . $file_name;
            if (is_file($file_path)) {
                require_once $file_path;
            }
        }
    }
}
$controller_folder = __DIR__ . "{$directory_separator}..{$directory_separator}controllers";
$controller_files = scandir($controller_folder);
if ($controller_files !== false) {
    if (is_array($controller_files) && count($controller_files) > 0) {
        foreach ($controller_files as $file_name) {
            if (strpos($file_name, ".php") === false) {
                continue;
            }
            $file_path = $controller_folder . DIRECTORY_SEPARATOR . $file_name;
            if (is_file($file_path)) {
                require_once $file_path;
            }
        }
    }
}

$model_folder = __DIR__ . "{$directory_separator}..{$directory_separator}models";
$model_files = scandir($model_folder);
if ($model_files !== false) {
    if (is_array($model_files) && count($model_files) > 0) {
        foreach ($model_files as $file_name) {
            if (strpos($file_name, ".php") === false) {
                continue;
            }
            $file_path = $model_folder . DIRECTORY_SEPARATOR . $file_name;
            if (is_file($file_path)) {
                require_once $file_path;
            }
        }
    }
}

$logic_folder = __DIR__ . "{$directory_separator}..{$directory_separator}logics";
$logic_files = scandir($logic_folder);
if ($logic_files !== false) {
    if (is_array($logic_files) && count($logic_files) > 0) {
        foreach ($logic_files as $file_name) {
            if (strpos($file_name, ".php") === false) {
                continue;
            }
            $file_path = $logic_folder . DIRECTORY_SEPARATOR . $file_name;
            if (is_file($file_path)) {
                require_once $file_path;
            }
        }
    }
}
