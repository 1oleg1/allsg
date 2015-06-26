<?php
ini_set('max_execution_time', '0');
error_reporting(E_ALL);
$t=mktime();
require_once(dirname(__FILE__) . "/conf.php");
require_once(dirname(__FILE__) . '/phpQuery/phpQuery.php');
//phpQuery::$debug = true;

$url_abs = "http://rabotaplus.ua";
//$url_site_vac = "http://rabotaplus.ua/vacancy/cat/"; // вакансии
$url_site_res = "http://rabotaplus.ua/resume/cat/";   // резюме
//-----------------------------------------------------------

// Вход на сайт, чтобы можно было видеть контакты
function get_web_page( $url )
{
$ch = curl_init();
//$url = 'http://rabotaplus.ua/login';
curl_setopt($ch, CURLOPT_URL, $url ); // отправляем на 
curl_setopt($ch, CURLOPT_HEADER, 0); // пустые заголовки
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // возвратить то что вернул сервер
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); // следовать за редиректами
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);// таймаут4
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/18.0 (Windows NT 6.1; rv:6.0.2) Gecko/20100101 Firefox/6.0.2'); // типа человек
//curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);// просто отключаем проверку сертификата
curl_setopt($ch, CURLOPT_COOKIEJAR, dirname(__FILE__).'/cookie.txt'); // сохранять куки в файл 
curl_setopt($ch, CURLOPT_COOKIEFILE,  dirname(__FILE__).'/cookie.txt'); 
curl_setopt($ch, CURLOPT_POST, 1); // использовать данные в post
curl_setopt($ch, CURLOPT_POSTFIELDS, array(
	'email' =>	'dswhitezaqr@gmail.com',
    'passwd' =>	'123456',
));
$data = curl_exec($ch);
	curl_close($ch);
	return $data;
}

get_web_page ('http://rabotaplus.ua/login'); // захоим на сайт и сохраняем кукисы


//-------------------------------------------------------------
$url_id = array( // массив соответствий
    53 => 1, //'IT-специалисты',IT, компьютеры, Интернет
    54 => 7, //'Бухгалтерия, финансы и учет',Бухгалтеры, аудиторы
    55 => 3, //'СМИ, издательство, полиграфия', Журналисты, редакторы, медиа, СМИ
    56 => 12, //'Медицина, фармацевтика', Медицина, фармацевтика
    57 => 117, //'Недвижимость и страхование',Недвижимость
    58 => 178, //'Охрана, служба безопасности',Охранники, служба безопасности
    59 => 200, //'Строительство,Рабочие спец-ти',Строительство
    61 => 191, //'Туризм и спорт',Туризм, спорт, красота, фитнес
    62 => 6, //'Архитектура, дизайн', Дизайнеры, художники, оформители
    63 => 175, //'Торговля и продажи',Мерчендайзеры, торговые представители
    64 => 2, //'Автосервис и транспорт',	Транспорт, автобизнес , автосервис
    65 => 8, //'Маркетинг, реклама и PR',Маркетинг, реклама, PR 
    67 => 182, //'HR, управление персоналом',Психологи, HR специалисты, тренеры
    68 => 187, //'Офисный персонал',Секретари, офис-менеджеры
    69 => 198, //'РАЗНОЕ',Разное, прочее
    71 => 11, //'Логистика, склад, ВЕД',Логистика, таможня, склад, ВЭД
    72 => 1, //'WEB-специалисты',IT, компьютеры, Интернет
    73 => 172, //'КаБаРе (кафе, бары, рестораны)',Гостиницы, рестораны, кафе
    74 => 180, //'Образование, переводчики',Образование, Наука
    75 => 195, //'Сфера услуг',Сфера обслуживания
    77 => 193, //'Юриспруденция, право',Юристы, адвокаты, нотариусы
    79 => 3, //'Сфера развлечения,TV, Радио',Журналисты, редакторы, медиа, СМИ
    337 => 186, // 'Руководство',Руководители, директора
    368 => 185, //'Производство',	Разнорабочие, сборщики
    375 => 190, //'Телекоммуникации и связь',Телекоммуникации , связь
    420 => 183, // 'Работа для студентов',Работа для студентов, без опыта
    435 => 4, //'Банковское дело',Банки, финансы, экономика
    526 => 194, //'Морские специальности',Морские специальности
    471 => 189, // 'Консалтинг',Страхование, право , консалтинг
    505 => 188, //'Сельское хозяйство, агробизнес',Сельское хозяйство, агробизнес
);

$url_all = array ();
    foreach($url_id as $ist=>$pol){
        $url = $url_site_res . $ist . "/page/3/";
        $page_content = file_get_contents($url);
        //$page_content = get_web_page($url);                
        $html = phpQuery::newDocument($page_content);
        $posts = $html->find('.rplusResumeListInfoText > div' );
                
        foreach($posts as $post){ // Вытаскиев ссылки со страниц 
            $post_link = pq($post)->find('a');
	        $urlmore = $post_link->attr('href'); // url поста        
            if (substr_count($urlmore, "resume")) {
                echo $url_abs . $urlmore . "<br />" ;
                $url_all[] = array ( $pol , $url_abs . $urlmore ); 
            }
        }
        echo "Взято из источника " . $url . "<br />";                
    }
echo "</br></br>Выбор всех ссылок закончен, проверка массива : </br></br>";
//echo "<pre>";
//print_r ($url_all);
//echo "</pre>";
    foreach($url_all as $ist2){
        echo "В категорию " . $ist2[0] . " будет внесена информамия из " . $ist2[1] . "</br>" ;        
    }
    foreach($url_all as $ist2){
        //$page_content_more = file_get_contents($ist2[1]);
        $page_content_more = get_web_page($ist2[1]);            
        $htmlmore = phpQuery::newDocument($page_content_more);        
        echo "------------------------------------</br>";
        echo $ist2[1]. "</br>";
        $title = $htmlmore->find('.print_info_important'); // вытаскиваем название
        $title = strip_tags($title); // удаляем html - теги
        echo $title. "</br>";
//        $city = $htmlmore->find('.no_print_vacancy_position')->filter("div:eq(3)");
        $city = $htmlmore->find('.no_print_vacancy_position div:eq(5)'); // вытаскиваем название города        
        $city = strip_tags($city);
        $city = trim(str_replace("Город — ","",$city));
        echo "----" . $city . "---</br>";         
        // вытаскиваем то, что находится в "Профессиональные навыки"        
        $text = $htmlmore->find(".rplusResumeDetailsTitle:contains('Профессиональные навыки') + div + div");         
        $text = str_replace("<br />","<br>",$text); // заменям br на те, которые распознает strip_tags 
        $text = strip_tags($text,'<br>'). "</br>"; // удалаем все теги, кроме br
        $text = preg_replace ('/\s+/',' ',  $text); // удалаем лишние пробелы и знаки переноса в тексте
        $text = trim($text) ;
        //echo $text . "<br>";
        // вытаскиваем то, что находится в "Дополнительные сведения"        
        $text_add = $htmlmore->find(".rplusResumeDetailsTitle:contains('Дополнительные сведения') + div + div)");
        $text_add = str_replace("<br />","<br>",$text_add); // заменям br на те, которые распознает strip_tags 
        $text_add = strip_tags($text_add,'<br>'). "</br>"; // удалаем все теги, кроме br
        $text_add = preg_replace ('/\s+/',' ',  $text_add); // удалаем лишние пробелы и знаки переноса в тексте
        $text_add = trim($text_add) ;                
        //echo $text_add . "<br>";        
        $text = $text . "<br>" . $text_add ;
        echo $text . "<br>";          
        // вытаскиваем то, что находится в "Контактная информация "        
        $contact = $htmlmore->find('#contacts_b');
        $contact = strip_tags($contact);
        $contact = preg_replace ('/\s+/',' ',  $contact); // удалаем лишние пробелы и знаки переноса в тексте
        $contact = trim($contact) ;
        $sql_add = "";
        $pos1 = stripos($contact, 'Контактная информация'); // смотрим, есть такая строка в текстве, или нет
        if ($pos1 !== false) { // если есть
            $contact = str_replace("Контактная информация","",$contact); // удаляем "Контактная информация"         
            $sql_add = " contacts = '". $contact . "' , "; // дполнительное поле в SQL-запрос
        }                    
        echo $contact . "<br>";
                                                                
        $query = mysql_query("SELECT id FROM jb_city WHERE city_name = '".$city."' LIMIT 1");
        echo "Запрос cравнения города SELECT id FROM jb_city WHERE city_name = '".$city."' LIMIT 1 <br>";
        echo "Вхождение города в базу данных обнаружено " . mysql_num_rows($query) ."<br/>";
        if(mysql_num_rows($query)) { // если город совпадает        
            $board = mysql_fetch_array($query); // получаем id города
            echo "Город совпал ".$city." с ". $board['id'] . "<br />";                  
            echo "<br> ---------------------------------------------------------------------------------- <br>";
                $query_dubl=mysql_query("SELECT id FROM jb_board 
                                         WHERE city='".$city."' AND title='".$title."' 
                                         AND text='".$text."' LIMIT 1");
	            if(mysql_num_rows($query_dubl)){ // проверяем на дубли
	               echo "Ошибка! Аналогичное объявление уже присутствует в Базе Данных";
                } else { // иначе вставляем в базу данных
            	$sql = "INSERT jb_board SET id_category='".$ist2[0]."', 
                         type='r', title='".$title."',  
                         city='".$city."', city_id='".$board['id']."',  
                         text='".$text."', old_mess='old', ".$sql_add."
                         time_delete='30', date_add=NOW(), parser = '".$ist2[1]."'";
                $insert=mysql_query($sql); 
                //echo $sql . "<br />";
                //echo $insert;
	            $last_id=mysql_insert_id();
                echo "<br> ID объявления - " . $last_id . "<br>";                
                }                    	                   	               
        }  else echo "Город не совпал";
phpQuery::unloadDocuments(); // удалаемдокумент из памяти
gc_collect_cycles(); // запуск сборщика мусора. от утечек памяти. только в php 5.3 и выше                              
}

?>