<?php

namespace Longbang\Llaravel\Classes;

class CommonClasses
{
    public static $SELF = null;

    public function __construct()
    {
        static::newSelf();
    }

    public static function newSelf()
    {
        if(static::$SELF instanceof self) {
            //static::$SELF;
        } else {
            static::$SELF = new static();
        }
    }

    public static function __callstatic($method,$arg)
    {
        static::newSelf();
    }

    public function __call($method,$arg)
    {
        static::newSelf();
    }

    public function __get($variable)
    {
        static::newSelf();
        return static::$SELF->$variable;
    }

    public static function all()
    {
        static::newSelf();
        return static::$SELF;
    }
}