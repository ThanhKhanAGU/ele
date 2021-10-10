<?php 
require_once(__DIR__."/table.php");

function runcmd($cmd)
{
    $myfile = fopen(__DIR__ . "\cmd.php", "w");
    fwrite($myfile,"<?php ". $cmd);
    fclose($myfile);
    require_once(__DIR__."\cmd.php");
    unlink(__DIR__ . "\cmd.php");
}

function write($path, $name, $data)
{
    if (!is_dir($path)) {
        mkdir($path, 0777, true);
    }
    if(is_file($path . "/" . $name))
    {
        $file = $path . '/' . $name;
        echo "\e[33mFile `$file` đã tồn tại không thể ghi đè.\e[39m\n";
    }else
    {
        $file = $path . '/' . $name;
        $myfile = fopen($file, "w");
        fwrite($myfile, $data);
        fclose($myfile);
        echo "\e[32mĐã Tạo file `".$file."` Thành Công.\e[39m\n";
    }
}
function logo()
{
print_r(
        "
\e[39m┌──[ \e[1m\e[34mAN GIANG UNIVERSITY\e[0m \e[39m]───────────────────────────────────────────────────────────────────────┐
│     ______  __          ______                                                            __   │
│    / ____/ / /  ___    / ____/ _____  ____ _   ____ ___   ___  _      __  ____    _____  / /__ │
│   / __/   / /  / _ \  / /_    / ___/ / __ `/  / __ `__ \ / _ \| | /| / / / __ \  / ___/ / //_/ │
│  / /___  / /  /  __/ / __/   / /    / /_/ /  / / / / / //  __/| |/ |/ / / /_/ / / /    / ,<    │
│ /_____/ /_/   \___/ /_/     /_/     \__,_/  /_/ /_/ /_/ \___/ |__/|__/  \____/ /_/    /_/|_|   │
└────────────────────────────────────────────────────────────────────────────────────────────────┘
       \e[39m   ┌────────────────────────────────────────────────────────────────────────────┐
       \e[39m┌──┴──[\e[33m MVC \e[39m]────────────────────────────────────────────────────────────────┐  │
       \e[39m│ \e[36mFramework: \e[39mQuản lý MVC trong framework - khởi tạo các file trong framework.│  │
       \e[39m│   ├─ \e[33m[\e[32m-controller `name` \e[33m]\e[39m: Tạo ra một controller trong framework.         │  │
       \e[39m│   ├─ \e[33m[\e[32m-model      `name` \e[33m]\e[39m: Tạo ra một model trong framework.              │  │
       \e[39m│   ├─ \e[33m[\e[32m-view       `name` \e[33m]\e[39m: Tạo ra một view trong framework.               │  │
       \e[39m│   └─ \e[33m[\e[32m-mvc        `name` \e[33m]\e[39m: Tạo ra một tổng hợp mvc trong framework.       │──┘
       \e[39m└────────────────────────────────────────────────────────────────────────────┘
       \e[39m   ┌────────────────────────────────────────────────────────────────────────────┐
       \e[39m╒══╧══[\e[33m TOOL \e[39m]═══════════════════════════════════════════════════════════════╗  │
       \e[39m│ \e[36mTool Framework: \e[39mThêm các chức năng cho framework.                          ║  │
       \e[39m│   ├─ \e[33m[\e[32m-tool  -user     \e[33m]\e[39m: Tạo chức năng đăng nhập cho dự án.               ║  │
       \e[39m│   ├─ \e[33m[\e[32m-tool  -function \e[33m]\e[39m: Tạo function sử dụng cho toàn bộ dự án.          ║  │
       \e[39m│   └─ \e[33m[\e[32m-tool  -router   \e[33m]\e[39m: Tạo chức năng quản lý router.                    ║──┘
       \e[39m└────────────────────────────────────────────────────────────────────────────╜
       \e[39m   ┌────────────────────────────────────────────────────────────────────────────┐
       \e[39m╔══╧══[\e[33m DATABASE \e[39m]═══════════════════════════════════════════════════════════╗  │
       \e[39m║ \e[36mDatebase: \e[39mQuản lý cơ sở dữ liệu - trực tiếp trên termial.                  ║  │
       \e[39m║    ├─ \e[33m[\e[32m-db  -check  \e[33m]\e[39m:  Kiểm tra kết nối đến CSDL chưa.                    ║  │
       \e[39m║    ├─ \e[33m[\e[32m-db  -reset  \e[33m]\e[39m:  Xóa toàn bộ cơ sở dữ liệu.                         ║  │
       \e[39m║    ├─ \e[33m[\e[32m-db  -execute\e[33m]\e[39m:  Thực hiện kịch bản tạo bởi lệnh `-tb`.             ║  │
       \e[39m║    ├─ \e[33m[\e[32m-db  `query` \e[33m]\e[39m:  Thực hiện câu `query` để trả về kết quả mong muốn. ║  │
       \e[39m║    ├─ \e[33m[\e[32m-tb  `name`  \e[33m]\e[39m:  Tạo ra một kịch bản tạo bảng `name`.               ║  │
       \e[39m║    └─ \e[33m[\e[32m-add `name`  \e[33m]\e[39m:  Thêm dữ liệu vào bảng `name`, [*] để thêm tất cả.  ║──┘
       \e[39m╚════════════════════════════════════════════════════════════════════════════╝");
}

require_once(__DIR__."/mvc.php");
if($argc>2){
    if(strtolower($argv[1]) == '-tool')
    {
       
        if(strtolower($argv[2]) === "-function")
        {
            write('function','function.view.php','<?php');
        }
        if (strtolower($argv[2]) === "-router") {
            write('router','web.php','<?php
Route::get("/",function(){
    echo "Hello World";
});');
        }
        if(strtolower($argv[2]) === "-user")
        {
            write('module/auth','auth.php','<?php
class user extends model{
   private static $user_login = null;
   public static function login($username, $password,$time = 1800)
   {
        $chck = user::where([["username","=", $username], [\'password\',"=", md5($password)]])->first();
        if($chck === NULL)
        {
            return false;
        }
        else
        {
            setcookie(\'__login__remember__\', $chck->remember_token, time() + $time, "/");
            $_SESSION["__login__remember__"] = $chck->remember_token;
            return true;
        }
   }  
   public static function check()
   {
       if(isset($_COOKIE[\'__login__remember__\']) && isset($_SESSION[\'__login__remember__\']))
       {
            return $_COOKIE[\'__login__remember__\'] === $_SESSION[\'__login__remember__\'];         
       }else return false;
   }
   public static function info()
   {
       if(self::$user_login === null  && self::check())
       {
          self::$user_login = user::where(\'remember_token\',$_SESSION[\'__login__remember__\'])->first();
       }
       return self::$user_login;
   }
   public static function logout()
   {
        unset($_SESSION[\'__login__remember__\']);
        unset($_COOKIE[\'__login__remember__\']);
   }   
}');
            write('database','user.php','<?php
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
$table = new Table("user");
$table->string(\'username\');
$table->string(\'password\',255);');
            write('database/data', 'user.php', '<?php
require_once(\'module\database\database.php\');
require_once(\'module\model\model.php\');
class user extends model{};
# thêm dữ liệu vào database
$data = [
   # [
   # ]
];
#thực thi thêm data vào bảng
foreach($data as $item)
{
    $dt = new user();
    foreach($item as $key=>$value)
    {
        if($key == \'password\') 
            $dt->key = md5($value);
        else 
            $dt->$key = $value;
    }
    $dt->remember_token = "_".rand(100000, 999999) . time() . "_token";
    var_dump($dt->save());
}');
        }
    die();
    }
    if(strtolower($argv[1]) == "-db")
    {
        if(strtolower($argv[2]) == "-execute")
        {
            table::execute();
        }
        elseif (strtolower($argv[2]) == "-reset") {
            table::reset();
        }
        elseif (strtolower($argv[2]) == "-check") {
            table::check();
        }
        else
        {
            table::show(str_replace("`","", $argv[2]));
        }
    die();
    }
    if(strtolower($argv[1]) === "-tb")
    {
        
        for ($i = 2; $i <$argc; $i ++) {
            $file = table::create($argv[$i]);
            write("database", "_" . count(glob("database/*.php")) . "_" . $argv[$i] . ".php", $file[0]);
            write("database/data", $argv[$i] . ".php", $file[1]);
        }
    die();
    }
    if(strtolower($argv[1]) === "-add")
    {

        $name = $argv[2];
        if($name !== "*" )
        {
            
            for ($i = 2; $i <$argc; $i ++) {
                $name = $argv[$i];
                if (is_file("database/data/$name.php")) {
                    require_once("database/data/$name.php");
                    echo "\e[32mĐã Tạo dữ liệu vào bảng `$name` Thành Công.\e[39m\n";
                } else
                echo "\e[31mKhông tìm thấy dữ liệu nào.\e[39m\n";
            }
        }else
        {
            foreach(glob("database/data/*.php") as $data_table)
            {
                require_once($data_table);
                echo "\e[32mĐã Tạo dữ liệu từ `$data_table` Thành Công.\e[39m\n";
            }
        }
    die();
    }

    if (strtolower($argv[1]) === "-model") {

        
        for ($i = 2; $i <$argc; $i ++) {
            mvc::model($argv[$i]);
        }
    die();
    }

    if (strtolower($argv[1]) === "-controller") {
        
       for ($i=2; $i <$argc; $i++) {
            mvc::controller($argv[$i]);
            write('router', $argv[$i].".router.php","<?php\n # ROUTER FOR ".strtoupper($argv[$i]));
       }
    die();
    }

    if (strtolower($argv[1]) === "-view") {
        for ($i = 2; $i < $argc; $i ++) {
            write('app/view', $argv[2] . ".html", "");
        }
        echo (' K:\workstation\Web\build\ele\module\command\command.php on line 21 ');
        die();
    }

    if (strtolower($argv[1]) === "-mvc") {

        for ($i = 2; $i < $argc; $i ++) {
            mvc::model($argv[$i]);
            mvc::controller($argv[$i]);
            write('router', $argv[$i] . ".router.php", "<?php\n # ROUTER FOR " . strtoupper($argv[$i]));
            write('app/view', $argv[$i] . ".html", "");
        }
        die();
    }
}else{
    logo();
}