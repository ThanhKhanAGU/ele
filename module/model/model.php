<?php
class model
{
    public function save()
    {
        if (($classname = get_called_class()) !== "model") {
            if (isset($this->id)) {
                //Sửa
                date_default_timezone_set('asia/ho_chi_minh');
                $_sql = 'UPDATE `' . $classname . '` SET ';
                foreach ($this as $key => $value) {
                    if ($key != 'id') {

                        $_sql .= "`$key`='$value', ";
                    }
                }

                $_sql .= "`time_edit` = '" . date("Y/m/d H:i:s") . "' WHERE `id` = $this->id";
                return db::exeNonQuery($_sql);
            } else {
                //Thêm
                date_default_timezone_set('asia/ho_chi_minh');
                $time = date("Y/m/d H:i:s");
                $_sql = 'INSERT INTO  `' . $classname . '` ';
                $name =  "(`time_edit`,`time_create`";
                $data =  "('" . $time . "','" . $time . "'";
                foreach ($this as $key => $value) {
                    if ($key != 'id') {
                        $name .= ",`$key`";
                        $data .= ",'$value'";
                    }
                }
                $_sql .= $name . ") VALUES " . $data . ")";
                return db::exeNonQuery($_sql);
            }
        } else return [];
    }
    public function delete()
    {
        if (isset($this->id)) {
            $_sql = "DELETE FROM `" . get_called_class() . "` WHERE `id` = $this->id";
            return db::exeNonQuery($_sql);
        }
        return false;
    }
    public static function all()
    {
        if (($classname = get_called_class()) !== "model")
            return (new mysql($classname))->get();
        else
            return [];
    }
    public static function first()
    {
        if (($classname = get_called_class()) !== "model")
            return (new mysql($classname))->first();
        else
            return [];
    }
    public static function find($id)
    {
        if (($classname = get_called_class()) !== "model")
            return (new mysql($classname))->where($id)->first();
        else
            return false;
    }
    public static function where(...$obj)
    {
        if (($classname = get_called_class()) !== "model")
            return (new mysql($classname))->where(...$obj);
        else
            return [];
    }
}
class mysql
{
    private $sql;
    private $table;
    public function __construct($table)
    {
        $this->table = $table;
        $this->sql = "SELECT * FROM `$table`";
    }
    public function first()
    {
        $arr = $this->get(1);
        if(count($arr)>0)
            return array_shift($arr);
        else 
            return [];
    }
    public function get(int $limit = NULL) 
    {
        if ($limit !== NULL) {
            foreach (db::exeQuery($this->sql .= " LIMIT $limit") as $item) {
                $class = $this->table;
                $obj = new $class();
                foreach ($item as $key => $value) {
                    $obj->$key = $value;
                }
                $result[$obj->id] = $obj;
            }
            return $result ?? [];
        } else {
            foreach (db::exeQuery($this->sql) as $item) {
                $class = $this->table;
                $obj = new $class();
                foreach ($item as $key => $value) {
                    $obj->$key = $value;
                }
                $result[$obj->id] = $obj;
            }
            return $result;
        }
    }
    public function where(...$value)
    {
        foreach($value as $i)
        {
            $obj[] = str_replace(['"','\''],[''],$i);
        }

        if (count($obj) === 0) return $this;
        elseif (count($obj) === 1) {
            if (gettype($obj[0]) == "array") {
                foreach ($obj[0] as $arr) {
                    if (strpos($this->sql, "WHERE") !== false) {
                        $this->sql .= " and `" . $arr[0] . "` " . $arr[1] . " '" . $arr[2] . "'";
                    } else {
                        $this->sql .= " WHERE `" . $arr[0] . "` " . $arr[1] . " '" . $arr[2] . "'";
                    }
                }
            }elseif (strpos($this->sql, "WHERE") !== false) {
                $this->sql .= " and `id` = " . $obj[0];
            } else {
                $this->sql .= " WHERE `id` = " . $obj[0];
            }
        } elseif (count($obj) === 2) {
            if (strpos($this->sql, "WHERE") !== false) {
                $this->sql .= " and `" . $obj[0] . "` = '" . $obj[1] . "'";
            } else {
                $this->sql .= " WHERE `" . $obj[0] . "` = '" . $obj[1] . "'";
            }
        } elseif (count($obj) === 3) {
            if (strpos($this->sql, "WHERE") !== false) {
                $this->sql .= " and `" . $obj[0] . "` " . $obj[1] . " '" . $obj[2] . "'";
            } else {
                $this->sql .= " WHERE `" . $obj[0] . "` " . $obj[1] . " '" . $obj[2] . "'";
            }
        }

        return $this;
    }
    public function orWhere(...$value)
    {
        foreach ($value as $i) {
            $obj[] = str_replace(['"', '\''], [''], $i);
        }
        if (count($obj) === 0) return $this;
        elseif (count($obj) === 1) {
            if (gettype($obj[0]) == "array") {
                foreach ($obj[0] as $arr) {
                    if (strpos($this->sql, "WHERE") !== false) {
                        $this->sql .= " or `" . $arr[0] . "` " . $arr[1] . " '" . $arr[2] . "'";
                    } else {
                        $this->sql .= " WHERE `" . $arr[0] . "` " . $arr[1] . " '" . $arr[2] . "'";
                    }
                }
            }
            if (strpos($this->sql, "WHERE") !== false) {
                $this->sql .= " or `id` = " . $obj[0];
            } else {
                $this->sql .= " WHERE `id` = " . $obj[0];
            }
        } elseif (count($obj) === 2) {
            if (strpos($this->sql, "WHERE") !== false) {
                $this->sql .= " or `" . $obj[0] . "` = '" . $obj[1] . "'";
            } else {
                $this->sql .= " WHERE `" . $obj[0] . "` = '" . $obj[1] . "'";
            }
        } elseif (count($obj) === 3) {
            if (strpos($this->sql, "WHERE") !== false) {
                $this->sql .= " or `" . $obj[0] . "` " . $obj[1] . " '" . $obj[2] . "'";
            } else {
                $this->sql .= " WHERE `" . $obj[0] . "` " . $obj[1] . " '" . $obj[2] . "'";
            }
        }

        return $this;
    }
    public function orderBy(...$value)
    {
        foreach ($value as $i) {
            $obj[] = str_replace(['"', '\''], [''], $i);
        }
        foreach ($obj as $item) {
            if (strpos($this->sql, "ORDER BY") === false)
                $this->sql .= " ORDER BY `$item`";
            else
                $this->sql .= ", `$item`";
        }
        return $this;
    }
    public function orderByDesc(...$value)
    {
        foreach ($value as $i) {
            $obj[] = str_replace(['"', '\''], [''], $i);
        }
        foreach ($obj as $item) {
            if (strpos($this->sql, "ORDER BY") === false)
                $this->sql .= " ORDER BY `$item` DESC";
            else
                $this->sql .= ", `$item` DESC";
        }
        return $this;
    }
}