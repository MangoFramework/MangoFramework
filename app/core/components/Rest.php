<?php

namespace core\components;

use Illuminate\Database\QueryException;
use core\App;
use models;

abstract class Rest extends Controller
{

    public function beforeProc()
    {
        parent::beforeProc();
        $responseConfig = App::$container['Config']->getResponse();
        $restConfig = $responseConfig['REST']['type'];
        if (!empty($restConfig))
            self::$response->setType($restConfig);
    }

    private function getMethod($const)
    {
        $method = $const;
        $pos = strrpos($method, '::');
        $method = substr($method, $pos + 2);
        return $method;
    }

    public function index()
    {
        $class = self::$class;
        $DB = App::$container['Database']->getConnection();
        $table = strtolower(str_replace('models\\', '', $class)) . 's';
        $data = $DB->table($table)->select('*')->get();

        return $data;
    }

    public function get($id)
    {
        $class = self::$class;
        try {
            $result = $class::find($id);

            if (!is_object($result)) {
                $data = array(
                    'success' => false,
                    'message' => 'id not found',
                    'controller' => self::$controller,
                    'method' => self::getMethod(__METHOD__),
                    'id' => $id
                );
            } else {
                $data = $result->getAttributes();
            }
        } catch (QueryException $e) {
            $data = array(
                'success' => false,
                'message' => $e->getMessage(),
                'controller' => self::$controller,
                'method' => self::getMethod(__METHOD__),
                'id' => $id
            );
        }

        // set the response data default
        return $data;
    }

    public function post()
    {
        $post = self::$inputs;
        $class = self::$class;
        $object = new $class();
        $table = str_replace('models\\', '', strtolower($class) . 's');
        $schemaManager = App::$container['Database']->getSchemaManager();
        $listTableColumns = $schemaManager->listTableColumns($table);

        foreach ($post as $column => $value) {
            if (!array_key_exists($column, $listTableColumns)) {
                return array(
                    'success' => false,
                    'message' => 'attribute not found',
                    'controller' => self::$controller,
                    'method' => self::getMethod(__METHOD__),
                    'attribute' => $column
                );
            } else {
                $object->$column = $value;
            }
        }

        try {
            $object->save();
            $data = array(
                'success' => true,
                'message' => 'save successful',
                'controller' => self::$controller,
                'method' => self::getMethod(__METHOD__),
                'id' => $object->getAttributes()['id']
            );
        } catch (QueryException $e) {
            $data = array(
                'success' => false,
                'message' => $e->getMessage(),
                'controller' => self::$controller,
                'method' => self::getMethod(__METHOD__)
            );
        }
        return $data;
    }

    public function put($id)
    {
        $post = self::$inputs;
        $class = self::$class;

        try {
            $object = $class::find($id);

            if (!is_object($object)) {
                return array(
                    'success' => false,
                    'message' => 'id not found',
                    'controller' => self::$controller,
                    'method' => self::getMethod(__METHOD__),
                    'id' => $id
                );
            } else {
                $table = str_replace('models\\', '', strtolower($class) . 's');
                $schemaManager = App::$container['Database']->getSchemaManager();
                $listTableColumns = $schemaManager->listTableColumns($table);

                foreach ($post as $column => $value) {
                    if (!array_key_exists($column, $listTableColumns)) {
                        return array(
                            'success' => false,
                            'message' => 'attribute not found',
                            'controller' => self::$controller,
                            'method' => self::getMethod(__METHOD__),
                            'attribute' => $column
                        );
                    } else {
                        $object->$column = $value;
                    }
                }


                $object->save();
                return array(
                    'success' => true,
                    'message' => 'save successful',
                    'controller' => self::$controller,
                    'method' => self::getMethod(__METHOD__),
                    'id' => $object->getAttributes()['id']
                );
            }
        } catch (QueryException $e) {
            return array(
                'success' => false,
                'message' => $e->getMessage(),
                'controller' => self::$controller,
                'method' => self::getMethod(__METHOD__)
            );
        }

    }

    public function delete($id)
    {
        $class = self::$class;
        try {
            $result = $class::find($id);

            if (!is_object($result)) {
                $data = array(
                    'success' => false,
                    'message' => 'id not found',
                    'controller' => self::$controller,
                    'method' => self::getMethod(__METHOD__),
                    'id' => $id
                );
            } else {

                $result->delete();
                $data = array(
                    'success' => true,
                    'message' => 'delete successful',
                    'controller' => self::$controller,
                    'method' => self::getMethod(__METHOD__),
                    'id' => $id
                );
            }
        } catch (QueryException $e) {
            $data = array(
                'success' => false,
                'message' => $e->getMessage(),
                'controller' => self::$controller,
                'method' => self::getMethod(__METHOD__)
            );
        }

        return $data;
    }

    public function complexe()
    {
        self::$response->setType('json');
        $class = self::$class;
        $options = App::$container['ComplexeOptions'];
        $first = true;
        $models = array();
        $DB = App::$container['Database']->getConnection();
        $table = strtolower(str_replace('models\\', '', $class)) . 's';
        $query = 'select * from ' . $table;
        $operators = array(
            '<=', '>=', '<', '>', '='
        );

        foreach ($options as $option) {
            if ($option['action'] == 'occur' && $option['cond'][0] == '=') {
                if ($first) {
                    $query .= ' where ' . $option['column'] . ' ' . $option['cond'][0] . ' "' . substr($option['cond'], 1) . '"';
                } else {
                    $query .= ' and ' . $option['column'] . ' ' . $option['cond'][0] . ' "' . substr($option['cond'], 1) . '"';
                }
                $first = false;
            } else if ($option['action'] == 'occur' && $option['cond'][0] == '~') {
                if ($first) {
                    $query .= ' where ' . $option['column'] . ' LIKE "%' . substr($option['cond'], 1) . '%"';
                } else {
                    $query .= ' and ' . $option['column'] . ' LIKE "%' . substr($option['cond'], 1) . '%"';
                }
                $first = false;
            } else if ($option['action'] == 'length') {
                if ($first) {
                    $query .= ' where CHAR_LENGTH(' . $option['column'] . ') ' . $option['cond'][0] . ' ' . substr($option['cond'], 1);

                } else {
                    $query .= ' and CHAR_LENGTH(' . $option['column'] . ') ' . $option['cond'][0] . ' ' . substr($option['cond'], 1);
                }
                $first = false;
            } else if ($option['action'] == 'compare') {

                $i = 0;
                $myOp = $operators[$i];
                while (strpos($option['cond'], $operators[$i]) === false) {
                    $i++;
                    $myOp = $operators[$i];
                }

                if ($first) {
                    $query .= ' where ' . $option['column'] . ' ' . $myOp . ' ' . str_replace($myOp, '', $option['cond']);
                } else {
                    $query .= ' and ' . $option['column'] . ' ' . $myOp . ' ' . str_replace($myOp, '', $option['cond']);
                }
                $first = false;
            }
        }

        $models = $DB->select($query);

        return $models;
    }

}