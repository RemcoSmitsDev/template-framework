<?php

/**
 * Content
 */
class Content
{
    public static $title = '';
    public static $content = '';

    public static function view(){
        $file = $_SERVER['DOCUMENT_ROOT'] . "/templates/content-templates/".self::$content.".php";
        if(file_exists($file)){
            require_once($file);
        }else{
            Route::$check = false;
        }
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
