<?php
session_start();
define('__LOCAL__', $_SERVER['REQUEST_SCHEME'] . "://" . $_SERVER['HTTP_HOST'] . '/');
require_once(__DIR__ . "/database/database.php");
require_once(__DIR__ . "/model/model.php");
require_once(__DIR__ . "/view/view.php");
require_once(__DIR__ . "/controller/controller.php");
require_once(__DIR__ . "/Router/Route.php");
// Require file when add login
if (is_file(__DIR__ . "/auth/auth.php")) require_once(__DIR__ . "/auth/auth.php");
foreach (glob("router/*.php") as $f) {
    require_once($f);
}
//Xử lý url
if (isset($_SERVER['PATH_INFO'])) {
    $client_url = $_SERVER['PATH_INFO'];
} else {
    $client_url = "/";
}
//két thúcxử lý url
$callback = Route::checkurl($client_url, $_SERVER['REQUEST_METHOD']);
if ($callback !== false) {
    if ($callback[0]['url_sub'] == null || !class_exists('user')) {
        if (is_string($callback[0]['method'])) {
            $class = explode("@", $callback[0]["method"]);

            require_once($class[0] . '.controller.php');

            $controller_name = $class[0] . "Controller";
            $method = $class[1];

            $class_call = new  $controller_name();

            call_user_func_array([$class_call, $method], $callback[1]);
        } else {
            call_user_func_array($callback[0]['method'], $callback[1]);
        }
    } else {
        header('Location: ' . __LOCAL__ . $callback[0]['url_sub']);
    }
} else {
?>
    <!DOCTYPE html>
    <html lang="vi">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Not Found</title>
        <link rel="shortcut icon" href="module/view/icon.png" type="image/x-icon">
        <style>
            body {
                padding: 0;
                margin: 0;
                width: 100vw;
                height: 100vh;
                display: flex;
                justify-content: center;
                align-items: center;
            }

            h1 {
                font-size: 6vw;
                text-transform: uppercase;
                color: rgb(5, 20, 49);
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            }
        </style>
    </head>

    <body>
        <h1>404 | Not found</h1>
    </body>

    </html>
<?php
}
