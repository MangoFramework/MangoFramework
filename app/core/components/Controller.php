<?php

namespace core\components;
use core\App;

abstract class Controller
{
    protected static $controller;
    protected static $class;
    protected static $inputs;
    protected static $session;
    public static $response;
    protected static $auth;

    public function beforeProc()
    {
        self::$controller = strtolower(str_replace('Controller', '', str_replace('controllers\\', '', get_called_class())));
        self::$class = 'models\\' . str_replace('Controller', '', str_replace('controllers\\', '', get_called_class()));
        self::$inputs = App::$container['inputs'];
        self::$response = App::$container['Response'];
        self::$session = App::$container['Session'];
        self::$auth = App::$container['Auth'];
    }

    public function routingTo($route)
    {
        App::$container['Blueprint']->pathInfo = $route;
        $parts = explode('/',$route);
        if(!empty($parts[1]))
            App::$container['Blueprint']->controller = ucfirst($parts[1]) . 'Controller';
        App::$container['Router']->route = '';
        App::routing();
    }

}