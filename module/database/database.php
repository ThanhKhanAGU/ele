<?php 
class db
{
    ##################################
    ### BUILD APP CONNECT DATABASE ###
    ##################################
    private static $db = NULL;
    private static function get_dataconfig()
    {
        if (self::$db === NULL) {
            $myfile = fopen("database.config", "r");
            $dt_config = fread($myfile, filesize("database.config"));
            fclose($myfile);
            $dt_config = explode("\n", $dt_config);
            foreach ($dt_config as $value) {
                if (trim($value)) {
                    $dt = explode('=', trim($value));
                    self::$db[trim($dt[0])] = trim($dt[1]);
                }
            }
        }
        return self::$db;
    }
    public static function check()
    {
        $db = self::get_dataconfig();
        $con = new mysqli($db['SERVER'], $db['USERNAME'], $db['PASSWORD']);
        if ($con->connect_error)
        {
            echo "\e[31mError: Check \e[34m`User name` \e[31mor \e[34m`Password`\e[39m";
        }
        else
        {
            $con = new mysqli($db['SERVER'], $db['USERNAME'], $db['PASSWORD']);
            if($con->query("CREATE DATABASE IF NOT EXISTS " . $db['DATABASE'])===true)
            {
                echo "\e[32mData connection successful!\e[39m";
            }
        }
    }
    public static function exeNonQuery($query)
    {
        date_default_timezone_set('asia/ho_chi_minh');
        $db = self::get_dataconfig();
        $con = new mysqli($db['SERVER'], $db['USERNAME'], $db['PASSWORD'], $db['DATABASE']);
        $check = ($con->query($query) === true);
        $con->close();
        return $check;
    }
    public static function reset()
    {
        $db = self::get_dataconfig();
        $con = new mysqli($db['SERVER'], $db['USERNAME'], $db['PASSWORD']);
        if ($con->query("CREATE DATABASE IF NOT EXISTS " . $db['DATABASE']) === true) {
            if ($con->query("DROP DATABASE " . $db['DATABASE']) === true) {
                if ($con->query("CREATE DATABASE IF NOT EXISTS " . $db['DATABASE']) === true) {
                    echo "\e[32mDatabase reset successful!\e[39m";
                }
            }
        }
        else
        {
            echo "\e[31mError: Check \e[34m`User name` \e[31mor \e[34m`Password`\e[39m";
        }
    }
    public static function exeQuery($query)
    {
        $db = self::get_dataconfig();
        $con = new mysqli(
            $db['SERVER'],
            $db['USERNAME'],
            $db['PASSWORD'],
            $db['DATABASE']
        );

        mysqli_set_charset($con, 'UTF8');
        date_default_timezone_set('asia/ho_chi_minh');
        $result = $con->query($query);
        if ($result !== false) {
            while ($row = $result->fetch_assoc()) {
                foreach ($row as $key => $value) {
                    $dataRow[$key] = $value;
                }
                $dataTable[] = (object) $dataRow;
            }
            $con->close();
            return $dataTable ?? [];
        } else {
            return [];
        }
       
    }
    ##################################
    ### BUILD APP CONNECT DATABASE ###
    ##################################
}