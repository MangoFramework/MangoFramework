<?php

namespace core\components;

use Illuminate\Database\QueryException;

use core\App;
use models;

abstract class Rest extends Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->response->setType('json');
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
        $class = $this->class;
        $DB = App::$container['Database']->getConnection();
        $table = strtolower(str_replace('models\\','',$class)).'s';
        $index = $DB->table($table)->select('*')->get();

        return $index;
    }

    public function get($id)
    {
        $class = $this->class;
        $result = $class::find($id);

        if (!is_object($result)) {
            $data = array(
                'state' => 'Not Found',
                'controller' => $this->controller,
                'method' => self::getMethod(__METHOD__),
                'id' => $id
            );
        } else {
            $data = $result->getAttributes();
        }

        // set the response data default
        return $data;
    }

    public function post()
    {
        $post = $this->inputs;
        $class = $this->class;
        $object = new $class();
        $table = str_replace('models\\', '', strtolower($class) . 's');
        $schemaManager = App::$container['Database']->getSchemaManager();
        $listTableColumns = $schemaManager->listTableColumns($table);

        foreach ($post as $column => $value) {
            if (!array_key_exists($column, $listTableColumns)) {
                return array(
                    'state' => 'attribute not found',
                    'controller' => $this->controller,
                    'method' => self::getMethod(__METHOD__),
                    'attribute' => $column
                );
            } else {
                $object->$column = $value;
            }
        }

        try {
            $object->save();
            return array(
                'state' => 'succeful',
                'controller' => $this->controller,
                'method' => self::getMethod(__METHOD__),
                'id' => $object->getAttributes()['id']
            );
        } catch (QueryException $e) {
            return array(
                'state' => 'unsucceful',
                'controller' => $this->controller,
                'method' => self::getMethod(__METHOD__),
                'Exception message' => $e->getMessage()
            );
        }
    }

    public function put($id)
    {
        $post = $this->inputs;
        $class = $this->class;
        $object = $class::find($id);
        $data = array();

        if (!is_object($object)) {
            $data = array(
                'state' => 'Not Found',
                'controller' => $this->controller,
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
                        'state' => 'attribute not found',
                        'controller' => $this->controller,
                        'method' => self::getMethod(__METHOD__),
                        'attribute' => $column
                    );
                } else {
                    $object->$column = $value;
                }
            }

            try {
                $object->save();
                return array(
                    'state' => 'succeful',
                    'controller' => $this->controller,
                    'method' => self::getMethod(__METHOD__),
                    'id' => $object->getAttributes()['id']
                );
            } catch (QueryException $e) {
                return array(
                    'state' => 'unsucceful',
                    'controller' => $this->controller,
                    'method' => self::getMethod(__METHOD__),
                    'Exception message' => $e->getMessage()
                );
            }

        }

        return $data;
    }

    public function delete($id)
    {
        $class = $this->class;
        $result = $class::find($id);

        if (!is_object($result)) {
            $data = array(
                'state' => 'Not Found',
                'controller' => $this->controller,
                'method' => self::getMethod(__METHOD__),
                'id' => $id
            );
        } else {
            $result->delete();
            $data = array(
                'state' => 'succeful',
                'controller' => $this->controller,
                'method' => self::getMethod(__METHOD__),
                'id' => $id
            );
        }

        return $data;
    }

    public function complexe()
    {
        self::$response->setType('json');
        $class = $this->class;
        $options = App::$container['ComplexeOptions'];
        $first = true;
        $models = array();
        $DB = App::$container['Database']->getConnection();
        $table = strtolower(str_replace('models\\','',$class)).'s';
        $query = 'select * from '.$table;
        $operators = array(
            '<=','>=','<','>','='
        );

        foreach($options as $option)
        {
            if($option['action'] == 'occur' && $option['cond'][0] == '='){
                if($first){
                    $query .= ' where '.$option['column'].' '.$option['cond'][0].' "'.substr($option['cond'],1).'"';
                }
                else{
                    $query .= ' and '.$option['column'].' '.$option['cond'][0].' "'.substr($option['cond'],1).'"';
                }
                $first = false;
            }
            else if($option['action'] == 'occur' && $option['cond'][0] == '~'){
                if($first){
                    $query .= ' where '.$option['column'].' LIKE "%'.substr($option['cond'],1).'%"';
                }
                else{
                    $query .= ' and '.$option['column'].' LIKE "%'.substr($option['cond'],1).'%"';
                }
                $first = false;
            }
            else if($option['action'] == 'length'){
                if($first){
                    $query .= ' where CHAR_LENGTH('.$option['column'].') '.$option['cond'][0].' '.substr($option['cond'],1);

                }
                else{
                    $query .= ' and CHAR_LENGTH('.$option['column'].') '.$option['cond'][0].' '.substr($option['cond'],1);
                }
                $first = false;
            }
            else if($option['action'] == 'compare'){

                $i =0;
                $myOp = $operators[$i];
                while(strpos($option['cond'],$operators[$i]) === false)
                {
                    $i++;
                    $myOp = $operators[$i];
                }

                if($first){
                    $query .= ' where '.$option['column'].' '.$myOp.' '.str_replace($myOp,'',$option['cond']);
                }
                else{
                    $query .= ' and '.$option['column'].' '.$myOp.' '.str_replace($myOp,'',$option['cond']);
                }
                $first = false;
            }
        }

        $models = $DB->select($query);

        self::$response->setData($models, 'default');

    }

    public function complexei()
    {
        self::$response->setType('json');
        $class = $this->class;
        $options = App::$container['ComplexeOptions'];
        $first = true;
        $models = array();
        $DB = App::$container['Database']->getConnection();
        $operators = array(
            '<=','>=','<','>','='
        );

        foreach($options as $option)
        {
            if($option['action'] == 'occur' && $option['cond'][0] == '='){
                if($first){
                    $model = $class::where($option['column'],$option['cond'][0],substr($option['cond'],1));

                }
                else{
                    $model = $model->where($option['column'],$option['cond'][0],substr($option['cond'],1));
                }
                $first = false;
            }
            else if($option['action'] == 'occur' && $option['cond'][0] == '~'){
                if($first){
                    $model = $class::where($option['column'],'LIKE','%'.substr($option['cond'],1).'%');

                }
                else{
                    $model = $model->where($option['column'],'LIKE','%'.substr($option['cond'],1).'%');
                }
                $first = false;
            }
            else if($option['action'] == 'length'){
                if($first){
                    $model = $class::whereRaw('CHAR_LENGTH('.$option['column'].') '.$option['cond'][0].' '.substr($option['cond'],1));

                }
                else{
                    $model = $model->whereRaw('CHAR_LENGTH('.$option['column'].') '.$option['cond'][0].' '.substr($option['cond'],1));
                }
                $first = false;
            }
            else if($option['action'] == 'compare'){

                $i =0;
                $myOp = $operators[$i];
                while(strpos($option['cond'],$operators[$i]) === false)
                {
                    $i++;
                    $myOp = $operators[$i];
                }

                if($first){
                    $model = $class::where($option['column'],$myOp,str_replace($myOp,'',$option['cond']));

                }
                else{
                    $model = $model->where($option['column'],$myOp,str_replace($myOp,'',$option['cond']));
                }
                $first = false;
            }
        }
        $model = $model->get();

        foreach ($model as $object)
        {
            $models[] = $object->getAttributes();
        }

        self::$response->setData($models, 'default');

    }
}