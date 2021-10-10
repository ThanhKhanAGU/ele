<?php
class mvc {
    public static function controller($name)
    {
        $data =
"<?php
class ".$name."Controller extends controller{   
    
}";
    $path = "app/controller";
    write($path,$name.".controller.php", $data);
    }
    public static function model($name)
    {
        $data =
        "<?php
class " . $name . " extends model{   
    
}";
        $path = "app/model/";
        write($path, $name . ".model.php", $data);
    }
    
}