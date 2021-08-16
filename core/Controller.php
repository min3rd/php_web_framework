<?php

class Controller
{
    public function __construct()
    {
    }
    public function redirect($pattern)
    {
        header("Location: " . getRootUrl() . "$pattern");
    }
    public function error($code = 404, $message = "")
    {
        Logger::error(get_called_class(), "error=" . json_encode(array(
            'code' => $code,
            'message' => $message,
        )));
        header('Access-Control-Allow-Origin: *');
        header("Content-type: application/json; charset=utf-8");
        echo json_encode(array(
            'code' => $code,
            'message' => $message,
        ));
    }
    public function json($data)
    {
        Logger::info("return_json", json_encode($data));
        header('Access-Control-Allow-Origin: *');
        header("Content-type: application/json; charset=utf-8");
        echo json_encode($data);
    }
}
