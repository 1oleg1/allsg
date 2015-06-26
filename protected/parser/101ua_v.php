<?php
ini_set('max_execution_time', '0');
error_reporting(E_ALL);
require_once(dirname(__FILE__) . "/conf.php");
require_once(dirname(__FILE__) . '/phpQuery/phpQuery.php');
//phpQuery::$debug = true;
$url_abs = "http://101.com.ua";
$url_site_vac = "http://101.com.ua/cgi-bin/srch.cgi?vac=1&razd=";   // вакансии первая часть URL
//-----------------------------------------------------------

$url_id = array( // массив соответствий
    21 => 1,   //'Программисты в ИТ-компании',IT, компьютеры, Интернет
    24 => 1,   //'Программисты систем учета',IT, компьютеры, Интернет
    1  => 1,   //'Web-разработчики',IT, компьютеры, Интернет
    25 => 1,   //'Программисты другие',IT, компьютеры, Интернет
    16 => 1,   //'Системные администраторы',IT, компьютеры, Интернет
    5  => 7,   //'Бухгалтера, финансисты',Бухгалтеры, аудиторы
    15 => 190, //'Коммуникации и связь',Телекоммуникации , связь
    8  => 10,  //'Инженеры технологи', 	Инженеры, технологи, проектировщики
    9  => 3,   //'СМИ, лингвистика', Журналисты, редакторы, медиа, СМИ
    11 => 12,  //'Медицина, спорт', Медицина, фармацевтика
    3  => 6,   //'Полиграфия, дизайн', Дизайнеры, художники, оформители
    27 => 117, //'Недвижимость',Недвижимость
    14 => 178, //'Безопасность, охрана',Охранники, служба безопасности
    18 => 2,   //'Транспорт',Транспорт, автобизнес , автосервис
    28 => 200, //'Строительство',Строительство
    20 => 191, //'Туризм, путешествия',Туризм, спорт, красота, фитнес
    13 => 175, //'Торговые агенты, продавцы',Мерчендайзеры, торговые представители
    21 => 176, //'Продажа, работа с клиентами',Менеджеры по продажам/закупкам
    17 => 8,   //'Маркетологи, рекламисты, PR',Маркетинг, реклама, PR
    4  => 182, //'HR, управление персоналом',Психологи, HR специалисты, тренеры
    7  => 187, //'Офисный персонал',Секретари, офис-менеджеры
    31 => 194, //'Работа для моряков',Морские специальности
    30 => 198, //'РАЗНОЕ',Разное, прочее
    26 => 11, //'Логистика, склад, ВЕД',Логистика, таможня, склад, ВЭД
    12 => 172, //'Сфера услуг, рестораны',Гостиницы, рестораны, кафе
    10 => 180, //'Образование, наука',Образование, Наука
    6  => 193,  //'Юриспруденция',Юристы, адвокаты, нотариусы
    36 => 185, // 'Производство',	Разнорабочие, сборщики
    34 => 183, // 'Работа для студентов',Работа для студентов, без опыта
    35 => 183, // 'Работа для выпускников',Работа для студентов, без опыта
    22 => 4, //'Банковская сфера',Банки, финансы, экономика
    37 => 189, // 'Консалтинг, аналитика',Страхование, право , консалтинг
    29 => 189, // 'Страхование',Страхование, право , консалтинг
    19 => 192, //'Культура, искусство',Шоу-бизнес, культура, музыка
    32 => 197, //'Работа за рубежом',Работа за рубежом
    2 => 184, //'Удаленная работа',Работа на дому, удаленная работа
    33 => 198, //'Удаленная работа',Разное, прочее
);

$url_all = array ();
    foreach($url_id as $ist=>$pol){
        $url = $url_site_vac . $ist . "&fmt=2&p=2"; // страница 3
        //$page_content = iconv('windows-1251', 'utf-8', file_get_contents($url) );
        $page_content = file_get_contents($url);
        $html = phpQuery::newDocument($page_content);
        $posts = $html->find('.vakres' );
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
        //$htmlmore = iconv('windows-1251', 'utf-8',$htmlmore);
        echo $ist2[1]. "</br>";
        $phone = $htmlmore->find("td:contains('Телефон:') + td"); // вытаскиваем phone
        $phone = strip_tags($phone); // удаляем html - теги
        $phone = iconv('windows-1251', 'utf-8',$phone);
        echo $phone. "</br>";

        $email = $htmlmore->find("td:contains('E-mail:') + td"); // вытаскиваем email
        $email = strip_tags($email); // удаляем html - теги
        $email = iconv('windows-1251', 'utf-8',$email);
        //echo $email. "</br>";

        $title = $htmlmore->find("td:contains('Должность:') + td"); // вытаскиваем название
        $title = strip_tags($title); // удаляем html - теги
        $title = iconv('windows-1251', 'utf-8',$title);
        //echo $title. "</br>";

        $city = $htmlmore->find("td:contains('Регион вакансии:') + td"); // вытаскиваем название города
        $city = strip_tags($city);
        $city = iconv('windows-1251', 'utf-8',$city);
        //$city = htmlspecialchars_decode($city);
        $city = trim($city);
        $city = substr_replace($city, '', 0, 2); // удалаем какой-то знак перед названием города
        echo "----|" . $city . "|---</br>";
        // вытаскиваем то, что находится в "Описание вакансии"
        $text = $htmlmore->find("td:contains('Описание вакансии') + td"); // вытаскиваем текст
        $text = str_replace("<br />","<br>",$text); // заменям br на те, которые распознает strip_tags
        $text = strip_tags($text,'<br>'). "</br>"; // удалаем все теги, кроме br
        $text = preg_replace ('/\s+/',' ',  $text); // удалаем лишние пробелы и знаки переноса в тексте
        $text = trim($text) ;
        $text = substr_replace($text, '', 0, 1); // удалаем какой-то знак перед названием города
        $text = iconv('windows-1251', 'utf-8',$text);
        echo $text . "<br>";

        $contact = "e-mail: " . $email . "Телефон: " . $phone ;
        $sql_add = " contacts = '". $contact . "' , "; // дполнительное поле в SQL-запрос
        echo "Контакты ->".$contact . "<br>";

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
                         text='".$text."', old_mess='old', ".$sql_add."
                         time_delete='30', date_add=NOW(), parser = '".$ist2[1]."'";
                $insert=mysql_query($sql);
                //echo $sql . "<br />";
                //echo $insert;
	            $last_id=mysql_insert_id();
                echo "<br> ID объявления - " . $last_id . "<br><span style='color:green'>Вставлено в БД</span><br>";
                }
        }  else echo "<br><span style='color:red'>Город не совпал</span>";
phpQuery::unloadDocuments(); // удалаемдокумент из памяти
gc_collect_cycles(); // запуск сборщика мусора. от утечек памяти. только в php 5.3 и выше
}


?>