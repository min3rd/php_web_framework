<?php

function ParamExist(array $source, string $paramName, $default = false)
{
    if (!is_array($source)) {
        return $default;
    }
    if (!isset($source[$paramName])) {
        return $default;
    }
    return $source[$paramName];
}

function ParamExistInt(array $source, string $paramName, $default = false)
{
    $value = ParamExist($source, $paramName, $default);
    return $value ? intval($value) : $default;
}
function getRootUrl()
{
    return HTTP_METHOD . SERVER_NAME . SERVER_SUFFIX;
}

function int(string $input)
{
    preg_match_all('!\d+!', $input, $matches);
    if (!$matches || count($matches) <= 0) {
        return 0;
    }
    return intval(implode("", $matches[0]));
}
