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

    public function __construct(){
        self::$url = Request::url();
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
        if(preg_match_all("/\{(\w+)\}/",$route,$matches,PREG_OFFSET_CAPTURE) && preg_match("/".str_replace("/","\/",preg_replace("/\{\w+\}/","\w+",$route))."$/",Request::url())){
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

    public static function set(string $route, Closure $func){
        // set current route from check list
        self::$routes[] = ["route" => $route,"callback" => $func,"urls" => []];
        self::$route = $route;
        self::$url = Request::url();

        if(self::$check === true){
            return new self;
        }
        return new self;
    }

    public static function dispatch(){
        // check with route to call
        $routes = array_column(self::$routes,'route');
        // check if there is there exist an route with the exast as the url
        if(in_array(Request::url(),$routes)){
            $key = array_search(Request::url(),$routes);
            call_user_func(self::$routes[$key]['callback'], new Content);
            self::$check = true;
            return true;
        }else{
            // loop trough all routes and check if there is a dynamic route that is the same as the url
            foreach ($routes as $route_key => $route) {
                if(self::checkParam($route)){
                    if((!empty(self::$routes[$route_key]['urls']) && in_array(Request::url(),self::$routes[$route_key]['urls'])) || empty(self::$routes[$route_key]['urls'])){
                        call_user_func(self::$routes[$route_key]['callback'], new Content);
                        self::$check = true;
                        return;
                    }
                }
            }
            return false;
        }
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
        if(!empty($urls) && !is_null($urls)){
            $key = array_search(self::$route,array_column(self::$routes,'route'));
            self::$routes[$key]["urls"] =  $urls;
            return new self;
        }
    }

    public static function getUrls(){
        return self::$valid_urls;
    }

    public static function addRouteInfo(string $name){
        $key = array_search(self::$route,array_column(self::$routes,'route'));
        self::$routes[$key]["name"] = $name;
        return new self;
    }

    public static function checkRouteNames($name){
        foreach (self::$routes as $key => $route) {
            if(isset($route['name']) && $route['name'] === $name){
                return $key;
            }
        }
        return false;
    }

    public static function view(string $name, $options = []){
        $routes = self::$routes;
        if(in_array($name,array_column($routes,"name")) && $key = self::checkRouteNames($name)){
            if(!empty($options)){
                return self::redirect(self::addParamsToRoute($routes,$key,$options));
            }
        }
        return;
    }

    public static function addParamsToRoute($routes,$key,$options){
        $url = $routes[$key]['route'];
        foreach ($options as $key => $value) {
            $url = preg_replace("/\{".$key."\}/",$value,$url);
        }
        return $url;
    }

    public static function redirect(string $url, $options = []){
        if(Request::url() === $url || empty($url)){
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
