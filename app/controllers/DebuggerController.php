<?php

namespace controllers;
use core\components\Controller;
use models;

class DebuggerController extends Controller
{
    public $routes = array(
        '/debugger/myHelloWorldRoute/:text' => 'helloWorld'
    );

    public function helloWorld($text)
    {
        echo $text;
    }

    public function show()
    {
        $html = file_get_contents('./asset/templates/debugger.php');
        return $html;
    }
}