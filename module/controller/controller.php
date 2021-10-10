<?php
class controller
{
}

function useModel($modelName)
{
    require_once('app/model/' . $modelName . '.model.php');
}
function goRouter($route)
{
    header('Location: ' . __LOCAL__ . $route);
}
