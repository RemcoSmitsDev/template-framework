<?php

/**
 * Content
 */
class Content
{

    public static function view(){
    	require_once($_SERVER['DOCUMENT_ROOT'] . "/templates/content-templates/".self::get().".php");
    }

    public static function set(string $view){
        $GLOBALS['content'] = $view;
    }

    public static function get(){
        if(Request::check('global',['content'])){
            return Response::return($GLOBALS['content'],200);
        }
        return Response::return('404',404);
    }
}


 ?>
