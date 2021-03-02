<?php


/**
 * Routes
 */
class Route
{
    public $route;
    public static $check = false;

    public static function notFound(){
        if(self::$check === false){
            (new Content)->set('404')->title('404');
            return;
        }
        return false;
    }

    // if you pass a parametes temp string like {name} then you can access the value from the url as global variable
    public static function checkParam(string $route){
        $url = Request::url();
        // match the route tho the url and check if the placeholders bijv. {name} is passed in
        if(preg_match_all("/\{(\w+)\}/",$route,$matches,PREG_OFFSET_CAPTURE) && preg_match("/".str_replace("/","\/",preg_replace("/\{\w+\}/","\w+",$route))."/",$url)){
            $matches = $matches[0];
            // set global variable for each parameters
            foreach ($matches as $key => $match) {
                $pos = $match[1];
                $values = explode("/",rtrim(substr($url,$pos),"/"));
                foreach ($values as $key => $value) {
                    $GLOBALS[preg_replace("/\{|\}/","",$matches[$key][0])] = $value;
                }
                break;
            }
            return true;
        }else{
            return false;
        }
    }

    public static function set(string $route, $func){
        //check if the route has passed in paraneters {id}

        $url = Request::url();
        // check if the route has parameters
        if(self::checkParam($route) || Request::url() === $route){
            self::$check = true;
            return $func(new Content);
        }
        return false;
    }

    public static function auth($func){
        if(User::is_loggedin() === true){
            return $func(new Content);
        }else{
            return false;
        }
    }

    public static function noAuth($func){
        if(User::is_loggedin() === false){
            return $func(new Content);
        }else{
            return false;
        }
    }

    public static function admin(){
        if(User::is_loggedin() && $_SESSION['_user']->Is_Admin ===  true){
            return $func(new Content);
        }else{
            return false;
        }
    }

    // state functions (redirect,replace url)
    public static function redirect(string $to){
        header('Location: '.$to);
        exit;
    }

    public static function change(string $url){
        echo "<script>history.pushState(null, '', '".$url."');</script>";
    }
}


 ?>
