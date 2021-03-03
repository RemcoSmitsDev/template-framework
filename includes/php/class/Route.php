<?php


/**
 * Routes
 */
class Route
{
    public static $routes = [];
    public static $route;
    public static $url;
    public static $check = false;
    public static $valid_urls = array();

    public function __construct(){
        self::$url = Request::url();
        self::$valid_urls = [];
    }

    public static function notFound(){
        if(self::$check === false){
            (new Content)->set('404')->title('404');
            return;
        }
        return false;
    }

    // if you pass a parametes temp string like {name} then you can access the value from the url as global variable
    public static function checkParam(string $route){
        // match the route tho the url and check if the placeholders bijv. {name} is passed in
        if(preg_match_all("/\{(\w+)\}/",$route,$matches,PREG_OFFSET_CAPTURE) && preg_match("/".str_replace("/","\/",preg_replace("/\{\w+\}/","\w+",$route))."$/",self::$url)){
            $matches = $matches[0];
            // set global variable for each parameters
            foreach ($matches as $key => $match) {
                $pos = $match[1];
                $values = explode("/",rtrim(substr(self::$url,$pos),"/"));
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
        // set current route from check list
        self::$route = $route;
        self::$url = Request::url();

        // return if there is alreay a match with an url
        if(self::$check === true){
            return new self;
        }

        //check if the route has passed in paraneters {id}
        if(!empty(self::$valid_urls) && in_array(self::$url,self::$valid_urls) && self::checkParam($route)){
            self::$check = true;
            call_user_func($func, new Content, self::$url);
        }else if(empty(self::$valid_urls) && (self::$url === $route || self::checkParam($route))){
            self::$check = true;
            call_user_func($func, new Content, self::$url);
        }
        return new self;
    }

    public static function auth($func){
        if(User::is_loggedin()){
            return $func(new Content);
        }else{
            return false;
        }
    }

    public static function noAuth($func){
        if(!User::is_loggedin()){
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

    public static function name($string){
        self::addRouteInfo($string);
        return new self;
    }

    public static function urls($urls){
        if(empty($urls)){
            return false;
        }
        self::$valid_urls = $urls;
    }

    public static function addRouteInfo(string $name){
        self::$routes[] = array("name" => $name,"route" => self::$route);
    }

    public static function view(string $name, $options = []){
        $routes = self::$routes;
        if($key = array_search($name,array_column($routes,"name"),true)){
            self::redirect($routes[$key]['route'], $options);
        }
        return;
    }

    public static function redirect(string $url, $options = []){
        if(Request::url() == $url){
            return false;
        }

        if(!empty($options)){
            $url = explode("/",rtrim($url,"/"));
            $i = 0;
            if(count($options) !== count($url)){
                return false;
            }
            foreach($url as $key => $value){
                if(preg_match("/\{\w+\}/",$value)){
                    echo $url[$key] = $options[$i];
                    $i++;
                }
            }
            header('Location: '.implode("/",$url));
            exit;
        }else{
            header('Location: '.$url);
            exit;
        }
        return;
    }
}


 ?>
