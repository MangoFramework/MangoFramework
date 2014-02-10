<?php

namespace core;

use core\components\controllerMapException;
use core\components\RouterException;
use core\components\BlueprintException;
use Symfony\Component\ClassLoader\UniversalClassLoader;

class App
{
    public static $container;

    public static function run()
    {
        try {
            self::autoloader();

            self::init();
            self::routing();
            self::response();

        } catch (ContainerException $e) {
            var_dump($e);
        } catch (controllerMapException $e) {
            self::$container['Response']->setData(array('Error' => $e->getMessage()));
        }
    }

    public static function init()
    {
        self::$container = Container::getInstance();
        self::$container->loaders();
        self::$container['Database'];
    }

    public static function routing()
    {
        // IS HOME ? -- config home route ?!
        if (self::$container['Blueprint']->pathInfo != '/') {

            // LOGIC
            if (self::$container['Blueprint']->exist['logic']) {
                self::$container['Blueprint']->type = 'logic';

                if (self::$container['Blueprint']->isLogic()) {

                    self::$container['Router']->logicRouting();
                    self::$container['Blueprint']->lockRouter = true;
                } elseif (self::$container['Blueprint']->isSubLogic()) {

                    self::$container['Router']->subLogicRouting();
                    self::$container['Blueprint']->lockRouter = true;
                }

            }
            // END LOGIC

            // PHYSICAL
            if (self::$container['Blueprint']->exist['physical'] && !self::$container['Blueprint']->lockRouter) {
                if (empty(self::$container['Blueprint']->type))
                    self::$container['Blueprint']->type = 'physical';

                if (self::$container['Blueprint']->isRest()) {
                    self::$container['Router']->beforeRestRouting();
                    self::$container['Router']->restRouting();
                    self::$container['Blueprint']->lockRouter = true;
                }
                elseif (self::$container['Blueprint']->isComplexe()) {
                    self::$container['Router']->complexeRouting();
                }
            }
            // END PHYSICAL

            if (self::$container['Blueprint']->exist['logic'] || self::$container['Blueprint']->exist['physical']) {
                try {
                    self::$container['Response']->setData(self::$container['Router']->execute());
                } catch (RouterException $e) {
                    // bad route for this controller !
                    self::$container['Response']->setData(array('Error' => $e->getMessage()));
                }
            } else {
                // no controller
                self::$container['Response']->setData(array('Error' => 'Controller not found : '.self::$container['Blueprint']->controller));
            }

        } else {
            // home
            self::$container['Response']->setData(array('home' => ''));
        }
    }

    public static function response()
    {

        // with die at TRUE and erasePrevBuffer at TRUE the buffer will contain only this response
        // if not all old or/and next content in buffer will be append
        $params = array(
            'die' => FALSE
        );

        // SEND RESPONSE
        self::$container['Response']->sendResponse($params);

        self::stop();
    }

    public static function autoloader()
    {
           if (file_exists('vendors/autoload.php'))
            require_once 'vendors/autoload.php';
        elseif (file_exists('../vendors/autoload.php'))
            require_once '../vendors/autoload.php';

        $loader = new UniversalClassLoader();
        $loader->useIncludePath(true);
        $loader->register();
        $loader->registerNamespaces(array(
            "core" => "./app/",
            "models" => "../"
        ));

        // Flush output
        /*    if (ob_get_length() > 0) {
              self::$container['Response']->write(ob_get_clean());
            }*/

        // Enable ouput buffering
        ob_start();
    }

    public static function stop($code = 200)
    {
        self::$container['Response']->setStatus($code)
            ->write(ob_get_clean(),true)
            ->send();
    }

}

