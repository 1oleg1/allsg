<?php
/**
 * Парсер новостей с http://www.silvergames.com/
 */

require_once(dirname(__FILE__) . '/phpQuery/phpQuery.php');
require_once(dirname(__FILE__) . '/DB.php');

class Parser1 {

    public static function save_image($kartinka,$kuda_sohranit){
        $kurl = curl_init($kartinka);
        curl_setopt($kurl, CURLOPT_HEADER, 0);
        curl_setopt($kurl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($kurl, CURLOPT_BINARYTRANSFER,1);
        $rawdata = curl_exec($kurl);
        curl_close($kurl);
        if (file_exists($kuda_sohranit)) :
            unlink($kuda_sohranit);
        endif;
        $fp = fopen($kuda_sohranit,'x');
        fwrite($fp, $rawdata);
        fclose($fp);
    }            
    
    public static function getRssNews(){
        ini_set('max_execution_time', '0');
        error_reporting(E_ALL);
        DB::getInstance();// инициализация экземпляра класса для работы с БД                       
        //phpQuery::$debug = true;
        $url_abs = "http://www.silvergames.com/";
        $url_id = array( 
	            'polit' => '/politics/',   
	            'fin' =>   '/finance/',
	            'event' => '/society/',            
        );       
    $url_all = array ();    
    foreach($url_id as $ist=>$pol){
        $i = 0;        
        $url = $url_abs . $pol ; //         
        $page_content = file_get_contents($url);
        usleep(1000000);        
        $html = phpQuery::newDocument($page_content);
        $posts = $html->find('.other_news ul li a');
        foreach($posts as $post){ // Вытаскиев ссылки со страниц
        if ($i < 2) { // по две новости от каждой страницы
        $i++;        
            $urlmore = pq($post)->attr('href'); // url поста
            echo $urlmore . "<br />" ;
            $url_all[] = array ( $ist, $pol , $urlmore );
        }
        }
        echo "Взято из источника " . $url . "<br />";
    }
    //echo "<pre>";
    //print_r($url_all);
    //echo "</pre>";
    
echo "</br></br>Выбор всех ссылок закончен, проверка массива : </br></br>";
    foreach($url_all as $ist2){
        echo "В категорию " . $ist2[0] . " будет внесена информамия из " . $ist2[2] . "</br>" ;
    }
    foreach($url_all as $ist2){        
        $page_content_more = file_get_contents($ist2[2]);
        $htmlmore = phpQuery::newDocument($page_content_more);
        echo "------------------------------------</br>";
        echo "<span style='color:red'><b>Источник:</b>".$ist2[2]. "</span></br>";
        $htmlmore->find(".read_also")->remove();        
        $title = $htmlmore->find(".central_article h1"); // вытаскиваем название
        $title = strip_tags($title); // удаляем html - теги
        $title = iconv('utf-8','windows-1251',$title);                                       
        $htmlmore->find("h2")->remove();
        $text = $htmlmore->find(".article_body"); // вытаскиваем phone
        $text = strip_tags($text,'<p>'); // удалаем все теги, кроме p               
        $text = iconv('utf-8','windows-1251', $text);
        $text = str_replace("УНИАН","DENGI-INFO",$text); // заменям br на те, которые распознает strip_tags        
	$text = mysql_real_escape_string($text);
        $urlphoto = $htmlmore->find(".photo_block img")->attr("src"); // вытаскиваем phone                               
  	    $date = date("Y-m-d");
        $time = date("H:i:s");                
        $img = 'img_'.$date.rand().'.jpg';
        $img2 = null;
        $save1 = ($_SERVER['DOCUMENT_ROOT'].'/newsimages/'.$img);
        $save2 = ($_SERVER['DOCUMENT_ROOT'].'/newsimages/m_'.$img);
        $save3 = ($_SERVER['DOCUMENT_ROOT'].'/newsimages/mm_'.$img);
        echo $save1."</br>".$save2."</br>".$save3."</br>";                 
        self::save_image($urlphoto, $save1);        
        self::save_image($urlphoto, $save2);
        self::save_image($urlphoto, $save3);
        $size = filesize($_SERVER['DOCUMENT_ROOT'].'/newsimages/'.$img);
        echo "Размер фотографии: ".$size."</br>";                                       
        $query=DB::query("SELECT id FROM yandex_news WHERE title = '".$title."' LIMIT 1");
        echo "Вхождение " . mysql_num_rows($query)."<br>";
        if((mysql_num_rows($query)) OR ($size == 0) OR (empty($text))){ // проверяем на дубли
	       echo "Ошибка! Аналогичная статья уже присутствует в Базе Данных или другие причины";
        } else {
        echo '<h1>'.$title. "</h1></br>";     
        echo $text. "</br>";
        echo $urlphoto. "</br>";            
        $sql =DB::query("INSERT INTO yandex_news (date, time, type, title, text, img, img_small, parser)       
                        VALUES('$date','$time','$ist2[0]', '$title', '$text', '$img', '$img2', 'UNIAN')");            
        $last_id=DB::insert_id();
        echo "<br> ID статьи - " . $last_id . "<br><span style='color:green'>Вставлено в БД</span><br>";    
        }
        phpQuery::unloadDocuments(); // удалаемдокумент из памяти
        gc_collect_cycles(); // запуск сборщика мусора. от утечек памяти. только в php 5.3 и выше
        echo date('h:i:s') . "\n";
        // ждать 1 секунды
        usleep(1000000);
        echo date('h:i:s') . "\n";                
        }
    }    
}

Parser1::getRssNews();

?>