<?php

namespace core\components;

class Config
{
    public function getResponse()
    {
        $config = include('./config/response.php');
        return $config;
    }

    public function getDocGen()
    {
        $config = include('./config/docgen.php');
        return $config;
    }

    public function getAppConfig()
    {
        $config = include './config/app.php';
        return $config;
    }
}