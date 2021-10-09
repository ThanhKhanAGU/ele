<?php class Route
{
    private static $get = [];
    private static $post = [];
    public static function get($url, $methedController, $url_se = NULL)
    {
        self::$get[$url] = ["method" => $methedController, "url_sub" => $url_se];
    }
    public static function post($url, $methedController, $url_se = NULL)
    {
        self::$post[$url] = ["method" => $methedController, "url_sub" => $url_se];
    }
    private static function check_one_url($url, $key, $value)
    {
        $pattern = preg_replace('/{(\w|\d)+}/', 'value', $key);
        $pattern = '/^' . str_replace(['value', '/'], ["(\\w|\\d)+", '\/'], $pattern) . '$/';
        if (preg_match($pattern, $url) !== 0) {
            $sl = count(explode(' ', trim(str_replace(preg_split('/{(\w|\d)+}/', $key), ' ', $key))));
            return [$value, array_slice(explode(' ', trim(str_replace(preg_split('/{(\w|\d)+}/', $key), ' ', $url))), 0, $sl)];
        }
        return false;
    }
    public static function checkurl($url, $method)
    {
       
        if ($method == "GET") {
            foreach (self::$get as $key => $value) {
                if ($res = self::check_one_url($url, $key, $value))
                    return $res;
            }
        } else {
            foreach (self::$post as $key => $value) {
                if ($res = self::check_one_url($url, $key, $value))
                    return $res;
            }
        }
        return false;
    }
}
