<?php
require_once("module/database/database.php");
class Table
{
    public static $script;
    public static function execute()
    {
        foreach (glob("database/*.php") as $key=>$value) {
            require_once($value);
        }
        db::check();
        echo "\n\n";
        foreach (self::$script as $key => $item) {
            if (!db::exeNonQuery($item->sql()))
            die("Lỗi khi thêm bản: $key \n $item->sql()");
            else echo "\t - \e[32mTạo Table `$key` thành công!\e[39m\n";
        }
        echo "\n\e[32mTạo Database thành công!\e[39m";
    }
    public static function show($query)
    {
        echo str_replace(
            [',', "\n\"", "{", "\"}", ' "', '":'],
            [",\n", "\n  \"", "{\n  ", "\"\n}", " \e[33m", "\e[39m => "],
            json_encode(db::exeQuery($query))
        );
    }
    public static function reset()
    {
        db::reset();
    }
    public static function check()
    {
        db::check();
    }
    
    private $head = '';
    private $sql = '';
    private $foot = '';
    public function sql()
    {
        return $this->head . $this->sql . $this->foot;
    }
    public function __construct($name_database)
    {
        $this->head =
            "CREATE TABLE IF NOT EXISTS `$name_database` ( 
        `id` bigint(20) AUTO_INCREMENT";
        $this->foot = ",
        `time_create` datetime DEFAULT NULL,
        `time_edit` datetime DEFAULT NULL,
        PRIMARY KEY (`id`) 
        )ENGINE=MyISAM DEFAULT CHARSET=utf8;";
        if ($name_database === 'user') {
            $this->foot = ',
            `remember_token` varchar(50) DEFAULT NULL,
            UNIQUE KEY(`username`)' . $this->foot;
        }
        self::$script[$name_database] = $this;
    }
    public function string($name, $size = 50)
    {
        $this->sql .= ",
        `$name` varchar($size) DEFAULT NULL";
    }
    public function text($name, $size = 2)
    {
        $type[1] = "tinytext";
        $type[2] = "text";
        $type[3] = "mediumtext";
        $type[4] = "longtext";
        if ($size > 0 && $size < 6) {
            $this->sql .= ",
            `$name` $type[$size] DEFAULT NULL";
        } else die("sai kích thước");
    }
    public function int($name, $size = 4)
    {
        $type[1] = "tinyint";
        $type[2] = "smallint";
        $type[3] = "mediumint";
        $type[4] = "int";
        $type[5] = "bigint";
        if ($size > 0 && $size < 6) {
            $this->sql .= ",
            `$name` $type[$size] DEFAULT 0";
        } else die("sai kích thước");
    }
    public function id_r($name,$table_pa)
    {
        $this->sql .= ",
            `$name` bigint,
            FOREIGN KEY (`".$name."`) REFERENCES $table_pa(id)";
    }
    public function float($name)
    {
        $this->sql .= ",
            `$name` float DEFAULT 0";
    }
    public function double($name)
    {
        $this->sql .= ",
        `$name` dounble DEFAULT 0";
    }
    public function bit($name)
    {
        $this->sql .= ",
        `$name` bit(1) DEFAULT 0";
    }
    public function date($name)
    {
        $this->sql .= ",
        `$name` date DEFAULT NULL";
    }
    public function datetime($name)
    {
        $this->sql .= ",
        `$name` datetime DEFAULT NULL";
    }
    public function time($name)
    {
        $this->sql .= ",
        `$name` time DEFAULT NULL";
    }
    public static function create($name)
    {
        return ['<?php
#----------------------------------------------------------------------------
#----------------------         CÁC KIỂU DỮ LIỆU       ----------------------
# $table->string("tên",0->255) : kiểu chữ
# $table->text("tên",1->4)     : kiểu văn bản
# $table->int("tên",1->5)      : kiểu số nguyên
# $table->id_r("tên","bản cha"): kiểu id liên kết
# $table->float("tên")         : kiểu float
# $table->double("tên")        : kiểu double
# $table->bit("tên")           : kiểu true / false
# $table->date("tên")          : kiểu ngày (YYYY/MM/DD)
# $table->datetime("tên")      : kiểu thời gian và ngày (YYYY/MM/DD H:m:s)
# $table->time("tên")          : kiểu thời gian (H:m:s)
#------   Nhập Code   --------
$table = new Table("' . $name . '");', '<?php
require_once(\'module\\database\\database.php\');
require_once(\'module\\model\\model.php\');
class ' . $name . ' extends model{};
# thêm dữ liệu vào database
$data = [
    // [
    // ]
];
#thực thi thêm data vào bảng
foreach($data as $item)
{
    $dt = new ' . $name . '();
    foreach($item as $key=>$value)
    {
        $dt->$key = $value;
    }
    $dt->save();
}'];
    }
}