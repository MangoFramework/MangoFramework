<?php

namespace models;
use core\components\BaseModel;

class User extends BaseModel{
    public $softDelete = true;
    public $timestamps = true;
}