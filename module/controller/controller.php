<?php
class controller
{
}

function useModel($modelName)
{
    require_once('app/model/' . $modelName . '.model.php');
}
