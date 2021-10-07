<?php
if(is_file("function/function.view.php"))require_once("function/function.view.php");
function view($url,$arr = [])
{
    $url = str_replace('\\','/',$url);
    $url = "app/view/".$url;
    $myfile = fopen($url, "r") or die("View Not Found");
    $data = fread($myfile, filesize($url));
    fclose($myfile);
    
    if(strpos($data, '@extend') !== false)// Xử lý kế thừa
    { 
        $url_con = preg_replace('/@extend\(|\)(.|\s)+/', '', trim($data));
        $myfile = fopen($url_con, "r") or die("View Not Found");
        $data_extend = fread($myfile, filesize($url_con));
        fclose($myfile);
        preg_match_all('/@paste\((.)+\)/', $data_extend, $output_array);
        foreach ($output_array[0] as $key_item) {
            $key = str_replace(['@paste(',')'],'', $key_item);
            $value = explode("@copy($key)",$data);
            if(count($value)==1) {
                 $data_extend = str_replace("@paste($key)",'',$data_extend);
            }
            else {
                $value = explode("@endcopy", $value[1])[0];
                $data_extend = str_replace("@paste($key)", trim($value), $data_extend);
            }
        }
        $data = $data_extend;
    }
    else
    {
        $data = preg_replace('/@paste\((.|\b)+\)/', '', $data);
    }

    // bắt đầu xử lý vòng lặp
    preg_match_all('/@foreach\((.|\b)+\)/', $data, $output_array);
    foreach($output_array[0] as $item){
        $value = preg_replace('/(@(\w|\d)+\()|(\)\z)/', '', trim($item));
        $function = "foreach";

        $data = str_replace("@$function($value)", "<?php $function($value){?>", $data);
    }

    preg_match_all('/@for\((.|\b)+\)/', $data, $output_array);
    foreach ($output_array[0] as $item) {
        $value = preg_replace('/(@(\w|\d)+\()|(\)\z)/', '', trim($item));
        $function = "for";

        $data = str_replace("@$function($value)", "<?php $function($value){?>", $data);
    }

    preg_match_all('/@while\((.|\b)+\)/', $data, $output_array);
    foreach ($output_array[0] as $item) {
        $value = preg_replace('/(@(\w|\d)+\()|(\)\z)/', '', trim($item));
        $function = "while";

        $data = str_replace("@$function($value)", "<?php $function($value){?>", $data);
    }

    $data = str_replace(["@endforeach", "@endfor","@endwhile","@endif","@end"],"<?php } ?>",$data);
    //end xử lý vòng lặp

    //xử lý if elseif else
    preg_match_all('/@if\((.|\b)+\)/', $data, $output_array);
    foreach ($output_array[0] as $item) {
        $value = preg_replace('/(@(\w|\d)+\()|(\)\z)/', '', trim($item));
        $function = "if";

        $data = str_replace("@$function($value)", "<?php $function($value){?>", $data);
    }
    preg_match_all('/@elseif\((.|\b)+\)/', $data, $output_array);
    foreach ($output_array[0] as $item) {
        $value = preg_replace('/(@(\w|\d)+\()|(\)\z)/', '', trim($item));
        $function = "elseif";

        $data = str_replace("@$function($value)", "<?php }$function($value){?>", $data);
    }

    preg_match_all('/@(\w|\d)+\((.|\b)+\)/', $data, $output_array);
    foreach ($output_array[0] as $item) {

        $value = preg_replace('/(@(\w|\d)+\()|(\)\z)/', '', trim($item));
        $function = preg_replace('/\A@|\((.|\b)+\)/', '', trim($item));

        $data = str_replace("@$function($value)","<?php print_r($function($value));?>", $data);
    }

    $data = str_replace("@else",'<?php }else{ ?>',$data);
    //end xử lý if elseif else

    $data = str_replace(["{{","}}","{!","!}"], ["<?php print_r(",");?>","<?php ","?>"], $data);
    
    $value = "<?php ";
    foreach($arr as $key => $item)
    {
        $value.=" \$$key = \$arr['$key'] ?? null;";
    }
    $value .= "?>";
    $data = $value.$data;

    $myfile = fopen(__DIR__."/cache.php", "w");
    fwrite($myfile, $data);
    fclose($myfile);

    require_once(__DIR__ . "/cache.php");
    unlink(__DIR__ . "/cache.php");
    return $data;
}

function json(...$array)
{
    print_r(json_encode($array));
}
