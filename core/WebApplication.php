<?php

/**
 * @author vanminh.vu
 * @todo create new WebApplication
 */
class WebApplication
{
    const NAME_CONTROLLER = "controller";
    const NAME_FUNCTION = "function";
    const NAME_QUERY_STRING = "QUERY_STRING";
    const NAME_REQUEST_METHOD = "REQUEST_METHOD";
    const NAME_METHOD_GET = "GET";
    const NAME_METHOD_POST = "POST";

    private $_handlers;
    private $_controller;
    private $_function;
    private $_param;

    public function __construct()
    {
        session_start();
        $this->_handlers = array();
        $this->_controller = null;
        $this->_function = null;
        $this->_param = array();
    }

    public function mount($request_method, $query_string, $controller, $function)
    {
        $query_string = preg_replace("/[\/]+/", "\\\/", $query_string);
        $this->_handlers[$request_method][$query_string] = array(
            self::NAME_CONTROLLER => $controller,
            self::NAME_FUNCTION => $function,
        );
    }

    public function get($query_string, $controller, $function)
    {
        $this->mount(self::NAME_METHOD_GET, $query_string, $controller, $function);
    }

    public function post($query_string, $controller, $function)
    {
        $this->mount(self::NAME_METHOD_POST, $query_string, $controller, $function);
    }

    public function prepare()
    {
        $query_string = $_SERVER[self::NAME_QUERY_STRING];
        $request_method = $_SERVER[self::NAME_REQUEST_METHOD];
        if (!isset($this->_handlers[$request_method])) {
            return false;
        }
        $handlers = $this->_handlers[$request_method];
        if (!$handlers) {
            return false;
        }
        if (count($handlers) <= 0) {
            return false;
        }
        foreach ($handlers as $key => $handler) {
            if (preg_match("/^$key$/", $query_string)) {
                $class_name = $handler[self::NAME_CONTROLLER];
                if (!class_exists($class_name)) {
                    return false;
                }
                $this->_controller = new $class_name();
                if (!$this->_controller) {
                    return false;
                }
                if (!method_exists($this->_controller, $handler[self::NAME_FUNCTION])) {
                    return false;
                }
                $this->_function = $handler[self::NAME_FUNCTION];
                return true;
            }
        }
        return false;
    }

    public function handle()
    {
        if (!$this->_controller) {
            return false;
        }
        if (!$this->_function) {
            return false;
        }
        if (!method_exists($this->_controller, $this->_function)) {
            return false;
        }
        return call_user_func_array(array(
            $this->_controller,
            $this->_function
        ), $this->_param);
    }

    public function error($code = 404, $message = "File not found")
    {
        echo json_encode(array(
            'code' => $code,
            'message' => $message,
        ));
    }
}
