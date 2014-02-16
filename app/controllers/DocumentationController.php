<?php

namespace controllers;
use core\components\Controller;
use models;

class DocumentationController extends Controller
{
    public function show()
    {
        $docGen = new \utils\docgen\DocGen();
        $docGen->create();

        include('./asset/templates/documentation.php');
    }
}