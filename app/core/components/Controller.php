<?php

namespace core\components;

use core\App;

abstract class Controller
{
    protected $controller;
    protected $class;
    protected $response;
    protected $inputs;

    public function __construct()
    {
        $this->inputs = App::$container['inputs'];
        $this->response = App::$container['Response'];
        $this->controller = strtolower(str_replace('Controller', '', str_replace('controllers\\', '', get_called_class())));
        $this->class = 'models\\' . str_replace('Controller', '', str_replace('controllers\\', '', get_called_class()));
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