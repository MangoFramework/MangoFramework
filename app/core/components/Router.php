<?php

namespace core\components;

use Pux\Mux;
use Pux\Executor;

class RouterException extends \Exception
{
}

class Router extends Mux
{
    public $route;
    private $class;
    private $blueprint;

    public function __construct(Blueprint $blueprint)
    {
        $this->blueprint = $blueprint;
    }

    public function logicRouting()
    {
        $routePatern = '/' . strtolower(str_replace('Controller', '', $this->blueprint->controller));
        $this->class = '\controllers\\' . $this->blueprint->controller;

        $this->add($routePatern, [$this->class, $this->blueprint->method]);
        $this->add($routePatern . "/", [$this->class, $this->blueprint->method]);

        $this->prepare($this->blueprint->pathInfo);
    }

    public function subLogicRouting()
    {
        $routePatern = $this->blueprint->route;
        $this->class = '\controllers\\' . $this->blueprint->controller;

        $this->add($routePatern, [$this->class, $this->blueprint->method]);

        $this->prepare($this->blueprint->pathInfo);
    }

    public function restRouting()
    {
        $routePatern = '/' . strtolower(str_replace('Controller', '', $this->blueprint->controller));
        $this->class = '\controllers\\' . $this->blueprint->controller;

        $this->get($routePatern . "/", [$this->class, 'index']);
        $this->get($routePatern, [$this->class, 'index']);
        $this->get($routePatern . '/:id', [$this->class, 'get']);
        $this->post($routePatern, [$this->class, 'post']);
        $this->put($routePatern . '/:id', [$this->class, 'put']);
        $this->delete($routePatern . '/:id', [$this->class, 'delete']);

        $this->prepare($this->blueprint->pathInfo);
    }

    public function complexeRouting()
    {
        $this->class = '\controllers\\' . $this->blueprint->controller;
        $this->add('/complexe-wxx45wx4',[$this->class,'complexe']);

        $this->prepare('/complexe-wxx45wx4');
    }

    public function beforeRouting()
    {
        $this->add('/before-wxx45wx4', [$this->class, 'before']);
        try {
            Executor::execute($this->dispatch('/before-wxx45wx4'));
        } catch (\Exception $e) {
        }
        $this->add('/before:mproc-wxx45wx4', [$this->class, 'beforeProc']);
        try {
            Executor::execute($this->dispatch('/before:proc-wxx45wx4'));
        } catch (\Exception $e) {
        }
    }

    public function afterRouting()
    {
        $this->add('/after-wxx45wx4', [$this->class, 'after']);
        try {
            Executor::execute($this->dispatch('/after-wxx45wx4'));
        } catch (\Exception $e) {
        }
    }

    public function prepare($path)
    {
        $prepare = $this->dispatch($path);
        if (!empty($prepare)) {
            $this->route = $this->dispatch($path);
        }
    }

    public function execute()
    {
        if (empty($this->route))
            throw new RouterException('bad route : '.$this->blueprint->pathInfo);

        $class = $this->route[2][0];
        $controller = new $class();

        if (method_exists($controller, $this->route[2][1])) {
            $this->beforeRouting();
            $response = Executor::execute($this->route);
            $this->afterRouting();

            return $response;
        } else {
            throw new RouterException('missing methode');
        }
    }
}