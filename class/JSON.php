<?php

class JSON{
    static private $data = [];


    public static function load($src = null){
        $src = Transform::string($src);
        if (!is_string($src)){
            return 0;
        }

        $name = Dir::getJson($src);

        if (!is_null($name)){
            $json = json_decode(file_get_contents(Dir::getUrl($src.$name)),true);
            if (array_key_exists('created',$json) and array_key_exists('data',$json)){
                self::$data[$src] = $json;
            }
        }
    }

    public static function data($src = null){
        $src = Transform::string($src);
        if (!is_string($src)){
            return null;
        }

        if (!array_key_exists($src,self::$data) or !array_key_exists('data',self::$data[$src])){
            self::load($src);
        }



        if (array_key_exists($src,self::$data) and array_key_exists('data',self::$data[$src])) {
            return self::$data[$src]['data'];
        }else{
            return null;
        }
    }



    public static function timestamp($src = null){
        $src = Transform::string($src);

        if (!is_string($src)){
            return 0;

        }



        if (!array_key_exists($src,self::$data) or !array_key_exists('created',self::$data[$src])){
            self::load($src);
        }



        if (array_key_exists($src,self::$data) and array_key_exists('created',self::$data[$src])) {
            return self::$data[$src]['created'];
        }else{
            return 0;
        }
    }



    public static function create($folder = '', $content = []){
        if (!is_string($folder) or (!is_array($content) and !is_string($content) and !is_numeric($content))) {
            return null;
        }

        $name = Dir::token(5, 180).'.json';

        Dir::empty($folder);

        $json = [
            'created' => time(),
            'data' => $content,
        ];

        self::$data[$folder] = $json;

        file_put_contents(Dir::getUrl($folder.$name), json_encode($json));

        return null;
    }



    public static function set($folder = '', $content = []){
        if (!is_string($folder) or (!is_array($content) and !is_string($content) and !is_numeric($content) and !is_null($content))) {
            return null;
        }

        $time = self::timestamp($folder);

        $name = Dir::token(5, 180).'.json';

        Dir::empty($folder);

        $json = [
            'created' => $time,
            'data' => $content,
        ];

        self::$data[$folder] = $json;

        file_put_contents(Dir::getUrl($folder.$name), json_encode($json));

        return null;
    }
}