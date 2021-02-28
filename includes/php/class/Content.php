<?php

/**
 * Content
 */
class Content
{
    public $title;

    public static function view(){
    	require_once($_SERVER['DOCUMENT_ROOT'] . "/templates/content-templates/".self::get().".php");
    }

    public function set(string $view){
        $GLOBALS['content'] = $view;
        return $this;
    }

    public function title(string $title){
        $this->title = $title;
        self::setTitle($title);
        return $this;
    }

    public static function get(){
        if(Request::check('global',['content'])){
            return Response::return($GLOBALS['content'],200);
        }
        return Response::return('404',404);
    }

    public static function setTitle(string $title){
        $GLOBALS['title'] = $title;
    }

    public static function getTitle(){
        if(Request::check('global','title')){
            return Request::get('global','title');
        }
        return '';
    }
}


 ?>
