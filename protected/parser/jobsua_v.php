<?php
ini_set('max_execution_time', '0');
error_reporting(E_ALL);
require_once(dirname(__FILE__) . "/conf.php");
require_once(dirname(__FILE__) . '/phpQuery/phpQuery.php');
//phpQuery::$debug = true;
$url_abs = "http://http://www.silvergames.com/";
$url_site_vac = "http://www.jobs.ua/vacancy/search/?todo=search&keywords=&city=&exp_id=&shedule_id=&edu_id=&parent_rubric=";   // вакансии первая часть URL
//-----------------------------------------------------------

$url_id = array( // массив соответствий
    'action'   => 1,   //'Компьютерные технологии, IT',IT, компьютеры, Интернет    
    'racing'   => 4,   //'WEB-специалисты',IT, компьютеры, Интернет
    'shooting' => 5,   //'Бухгалтерия, банк, финансы, аудит',Бухгалтеры, аудиторы
    'sports'   => 6, //'Телекоммуникации, связь',Телекоммуникации , связь
    'strategy' => 32,  //'Инженерия, производство, рабочие специальности', Инженеры, технологи, проектировщики
    'puzzle'   => 3,   //'СМИ, редактирование, переводы', Журналисты, редакторы, медиа, СМИ
    'arcade'   => 10,  //'Медицина, фармация', Медицина, фармацевтика
    65  => 6,   //'Полиграфия, издательство', Дизайнеры, художники, оформители
    393  => 6,   //'Дизайн, оформление, креатив', Дизайнеры, художники, оформители
    406 => 186, //'Руководство, топ-менеджмент',Руководители, директора
    364 => 178, //'Служба безопасности, охрана',Охранники, служба безопасности
    315 => 2,   //'Транспорт, автосервис',Транспорт, автобизнес , автосервис
    284 => 200, //'Строительство, архитектура, недвижимость',Строительство
    374 => 191, //'Сфера услуг',Туризм, спорт, красота, фитнес
    333 => 191, //'Туризм, спорт',Туризм, спорт, красота, фитнес
    269 => 176, //'Торговля, продажи',Менеджеры по продажам/закупкам
    164 => 176, //'Менеджеры среднего звена',Менеджеры по продажам/закупкам
    213 => 8,   //'Реклама, маркетинг, PR',Маркетинг, реклама, PR
    190 => 187, //'Офисный персонал, HR',Секретари, офис-менеджеры
    344 => 11, //'Логистика, склад, ВЭД',Логистика, таможня, склад, ВЭД
    304 => 172, //'Ресторанный бизнес, кулинария',Гостиницы, рестораны, кафе
    1   => 180, //'Образование, наука, воспитание',Образование, Наука
    83  => 193,  //'Юриспруденция, консалтинг, страхование',Юристы, адвокаты, нотариусы
    426 => 192, //'Культура, кино, шоу-бизнес',Шоу-бизнес, культура, музыка
    481 => 197, //'Работа за рубежом',Работа за рубежом
    139 => 184, //'Временная работа, работа на дому',Работа на дому, удаленная работа
    502 => 188, //'Сельское хозяйство, агробизнес',Сельское хозяйство, агробизнес
);

$url_all = array ();
    foreach($url_id as $ist=>$pol){
        $url = $url_site_vac . $ist . "&page=1"; // страница 1
        //$page_content = iconv('windows-1251', 'utf-8', file_get_contents($url) );
        $page_content = file_get_contents($url);
        $html = phpQuery::newDocument($page_content);
        $posts = $html->find('.def_bl_vac' );
        //echo iconv('windows-1251', 'utf-8',$posts);
        foreach($posts as $post){ // Вытаскиев ссылки со страниц
            $urlmore = pq($post)->attr('href'); // url поста
            echo $url_abs . $urlmore . "<br />" ;
            $url_all[] = array ( $pol , $url_abs . $urlmore );
        }
        echo "Взято из источника " . $url . "<br />";
    }
echo "</br></br>Выбор всех ссылок закончен, проверка массива : </br></br>";
    foreach($url_all as $ist2){
        echo "В категорию " . $ist2[0] . " будет внесена информамия из " . $ist2[1] . "</br>" ;
    }

    foreach($url_all as $ist2){
        $page_content_more = file_get_contents($ist2[1]);
        $htmlmore = phpQuery::newDocument($page_content_more);
        echo "------------------------------------</br>";
        echo $ist2[1]. "</br>";
        $phone = $htmlmore->find(".viewcontact"); // вытаскиваем phone
        //$phone = iconv('windows-1252', 'windows-1251',$phone);
        $phone = iconv('utf-8', 'cp1252', $phone);// перекодируем
        $phone = iconv('cp1251', 'utf-8', $phone);// перекодируем
        $pos0 = stripos($phone, 'E-mail:'); // смотрим, есть такая строка в текстве, или нет
        if ($pos0 !== false) { // если есть
            $text_0part = explode('E-mail:',$phone); // разбиваем на две части
            $phone = $text_0part[0]; // весь текст 
        }                                        
        $phone = strip_tags($phone); // удаляем html - теги
        echo $phone. "</br>";
 if (substr_count($phone, 'этот работодатель ждет резюме на имейл') == 0) { // если телефона нет , то не продолжаем парсить
            $phone_ar = explode('E-mail',$phone);
            $cont = $phone_ar[0];
            echo $cont. "</br>";
        $title = $htmlmore->find(".post_vac > h1"); // вытаскиваем название
        $title = strip_tags($title); // удаляем html - теги        
        $title = iconv('utf-8', 'cp1252', $title);// перекодируем
        $title = iconv('cp1251', 'utf-8', $title);// перекодируем
        $title = substr_replace($title, '', 0, 14); // удалаем слово "Работа"                                
        echo $title. "</br>";            

        $city = $htmlmore->find(".viewcontcenter > li:eq(1)"); // вытаскиваем название города
        $city = strip_tags($city);
        $city = iconv('utf-8', 'cp1252', $city);// перекодируем
        $city = iconv('cp1251', 'utf-8', $city);// перекодируем                                
        $city = trim($city);
        echo "----|" . $city . "|---</br>";
        // вытаскиваем то, что находится в "Описание вакансии"
        $text = $htmlmore->find(".infovacansy"); // вытаскиваем текст
        $text = str_replace("<br />","<br>",$text); // заменям br на те, которые распознает strip_tags
        $text = strip_tags($text,'<br>'). "</br>"; // удалаем все теги, кроме br
        $text = preg_replace ('/\s+/',' ',  $text); // удалаем лишние пробелы и знаки переноса в тексте
        $text = trim($text) ;
        //$text = htmlentities($text);        
        $text = iconv('utf-8', 'cp1252', $text);// перекодируем
        $text = iconv('cp1251', 'utf-8', $text);// перекодируем
        $text_2part = explode('Территориальное место работы',$text); // разбиваем на две части
        $text = $text_2part[0]; // весь текст
        $text_3part = explode('Требования к кандидату / информация о вакансии:',$text); // разбиваем на две части
        $text = $text_3part[1]; // весь текст
        $pos1 = stripos($text, 'Высылайте свое резюме через форму'); // смотрим, есть такая строка в текстве, или нет
        if ($pos1 !== false) { // если есть
            $text_4part = explode('Высылайте свое резюме через форму',$text); // разбиваем на две части
            $text = $text_4part[0]; // весь текст 
        }
        $pos2 = stripos($text, 'Ждем Ваши резюме и звонки'); // смотрим, есть такая строка в текстве, или нет
        if ($pos2 !== false) { // если есть
            $text_5part = explode('Ждем Ваши резюме и звонки',$text); // разбиваем на две части
            $text = $text_5part[0]; // весь текст 
        }                                                                                                              
        echo $text . "<br>";
               
                        
        $query = mysql_query("SELECT id FROM jb_city WHERE city_name = '".$city."' LIMIT 1");
        echo "SELECT id FROM jb_city WHERE city_name = '".$city."' LIMIT 1 <br>";
        echo "Вхождение " . mysql_num_rows($query)."<br>";
        if(mysql_num_rows($query)) { // если город совпадает 
            $board = mysql_fetch_array($query); // получаем id города
            echo "Город совпал ".$city." с ". $board['id'] . "<br />";
            echo "<br> ---------------------------------------------------------------------------------- <br>";
                $query_dubl=mysql_query("SELECT id FROM jb_board WHERE parser = '".$ist2[1]."'");
	            if(mysql_num_rows($query_dubl)){ // проверяем на дубли
	               echo "Ошибка! Аналогичное объявление уже присутствует в Базе Данных";
                } else { // иначе вставляем в базу данных
            	$sql = "INSERT jb_board SET id_category='".$ist2[0]."',
                         type='v', title='".$title."',
                         city='".$city."', city_id='".$board['id']."',
                         text='".$text."', old_mess='old', contacts = '". $phone . "',
                         time_delete='30', date_add=NOW(), parser = '".$ist2[1]."'";
                $insert=mysql_query($sql); 
                //echo $sql . "<br />";
                //echo $insert;
	            $last_id=mysql_insert_id();
                echo "<br> ID объявления - " . $last_id . "<br><span style='color:green'>Вставлено в БД</span><br>";
                }
        }  else echo "<br><span style='color:red'>Город не совпал</span>";
} else echo "нет телефона</br>";        
        
phpQuery::unloadDocuments(); // удалаемдокумент из памяти
gc_collect_cycles(); // запуск сборщика мусора. от утечек памяти. только в php 5.3 и выше
}


?>