<?php

/**
 * Content
 */
class Content
{
    public static $title = '';
    public static $content = '';

    public static function view(){
    	require_once($_SERVER['DOCUMENT_ROOT'] . "/templates/content-templates/".self::$content.".php");
    }

    public function set(string $view){
        self::$content = $view;
        return $this;
    }

    public function title(string $title){
        self::$title = $title;
        return $this;
    }

    public static function get(){
        return self::$content;
    }

    public static function getTitle(){
        return self::$title;
    }
}


 ?>
