<?

//Данные для подключения к БД
$host = "localhost";    // Имя хоста
$bdname = "allworks3";    // Имя БД
$bdlogin = "allworks3";    // Логин к БД
$bdpassword = "allworks3wqa";  // Пароль к БД
$sitename = "allwork.com.ua"; // название сайта для index.php дл я добавление в title
$path_abs = "/var/www/allwork.com.ua/public";
#############################################################################################################

setlocale(LC_ALL, 'ru_RU.UTF8', 'ru_RU', 'russian');
define("REF", true);
session_start();
$h = "http://" . $_SERVER['HTTP_HOST'] .  "parser/";
//$_SERVER['DOCUMENT_ROOT']

$GLOBALS['cq'] = 0;

function cq() {
    $GLOBALS['cq']++;
}




function gentime() {
    static $a;
    if ($a == 0)
        $a = microtime(true);else
        return(string) (microtime(true) - $a);
}

gentime();
$db = @mysql_connect($host, $bdlogin, $bdpassword);
if (!$db)
    die(mysql_error());
if (!@mysql_select_db($bdname, $db))
    die(mysql_error());
mysql_query("SET NAMES utf8");
cq();
$conf = mysql_query("SELECT * FROM jb_config");
cq();
$c = @mysql_fetch_assoc($conf);

//$limit_pages_in_cache = 3;
//$JBSCACHE = @$c['scache'];
//$JBSCACHE_expire = @$c['scache_expire'];
//$JBSCACHE_exp_expire = @$c['scache_exp_expire'];
//$JBKCACHE = @$c['kcache'];
//if (@$_COOKIE['jbnocache'] == '1')
//    $JBKCACHE = "";


function pr() {
    echo "<pre>_REQUEST: ";
    print_r($_REQUEST);
    echo "</pre>";
    echo "<pre>_FILES: ";
    print_r($_FILES);
    echo "</pre>";
    echo "<pre>_SESSION: ";
    print_r($_SESSION);
    echo "</pre>";
}

function cleansql($input) {
    $input = trim($input);
    if (get_magic_quotes_gpc())
        $input = stripslashes($input);
    if (!is_numeric($input))
        $input = mysql_real_escape_string($input);
    return $input;
}

function clean($input) {
    $input = strip_tags_smart($input);
    $input = htmlspecialchars($input);
    $input = cleansql($input);
    return $input;
}

function translit($content) {
    $transA = array('А' => 'a', 'Б' => 'b', 'В' => 'v', 'Г' => 'g', 'Ґ' => 'g', 'Д' => 'd', 'Е' => 'e', 'Є' => 'e', 'Ё' => 'yo', 'Ж' => 'zh', 'З' => 'z', 'И' => 'i', 'І' => 'i', 'Й' => 'y', 'Ї' => 'y', 'К' => 'k', 'Л' => 'l', 'М' => 'm', 'Н' => 'n', 'О' => 'o', 'П' => 'p', 'Р' => 'r', 'С' => 's', 'Т' => 't', 'У' => 'u', 'Ў' => 'u', 'Ф' => 'f', 'Х' => 'h', 'Ц' => 'c', 'Ч' => 'ch', 'Ш' => 'sh', 'Щ' => 'sch', 'Ъ' => '', 'Ы' => 'y', 'Ь' => '', 'Э' => 'e', 'Ю' => 'yu', 'Я' => 'ya');
    $transB = array('а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'ґ' => 'g', 'д' => 'd', 'е' => 'e', 'ё' => 'yo', 'є' => 'e', 'ж' => 'zh', 'з' => 'z', 'и' => 'i', 'і' => 'i', 'й' => 'y', 'ї' => 'y', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n', 'о' => 'o', 'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't', 'у' => 'u', 'ў' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'c', 'ч' => 'ch', 'ш' => 'sh', 'щ' => 'sch', 'ъ' => '', 'ы' => 'y', 'ь' => '', 'э' => 'e', 'ю' => 'yu', 'я' => 'ya', '&quot;' => '', '&amp;' => '', 'µ' => 'u', '№' => '');
    $content = trim(strip_tags_smart($content));
    $content = strtr($content, $transA);
    $content = strtr($content, $transB);
    $content = preg_replace("/\s+/ums", "_", $content);
    $content = preg_replace('/[\-]+/ui', '-', $content);
    $content = preg_replace('/[\.]+/u', '_', $content);
    $content = preg_replace("/[^a-z0-9\_\-\.]+/umi", "", $content);
    $content = str_replace("/[_]+/u", "_", $content);
    return $content;
}

function ru2en($content) {
    $transA = array('А' => 'A', 'Б' => 'B', 'В' => 'V', 'Г' => 'G', 'Ґ' => 'G', 'Д' => 'D', 'Е' => 'E', 'Є' => 'E', 'Ё' => 'Yo', 'Ж' => 'Zh', 'З' => 'Z', 'И' => 'I', 'І' => 'I', 'Й' => 'Y', 'Ї' => 'Y', 'К' => 'K', 'Л' => 'L', 'М' => 'M', 'Н' => 'N', 'О' => 'O', 'П' => 'P', 'Р' => 'R', 'С' => 'S', 'Т' => 'T', 'У' => 'U', 'Ў' => 'U', 'Ф' => 'F', 'Х' => 'H', 'Ц' => 'C', 'Ч' => 'Ch', 'Ш' => 'Sh', 'Щ' => 'Sch', 'Ъ' => '', 'Ы' => 'Y', 'Ь' => '', 'Э' => 'E', 'Ю' => 'Yu', 'Я' => 'Ya');
    $transB = array('а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'ґ' => 'g', 'д' => 'd', 'е' => 'e', 'ё' => 'yo', 'є' => 'e', 'ж' => 'zh', 'з' => 'z', 'и' => 'i', 'і' => 'i', 'й' => 'y', 'ї' => 'y', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n', 'о' => 'o', 'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't', 'у' => 'u', 'ў' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'c', 'ч' => 'ch', 'ш' => 'sh', 'щ' => 'sch', 'ъ' => '', 'ы' => 'y', 'ь' => '', 'э' => 'e', 'ю' => 'yu', 'я' => 'ya', '&quot;' => '', '&amp;' => '', 'µ' => 'u', '№' => '');
    $content = trim(strip_tags_smart($content));
    $content = strtr($content, $transA);
    $content = strtr($content, $transB);
    return $content;
}


?>