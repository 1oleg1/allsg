<?php
ini_set('max_execution_time', '0');
error_reporting(E_ALL);
$t=mktime();
require_once(dirname(__FILE__) . "/conf.php");
require_once(dirname(__FILE__) . '/phpQuery/phpQuery.php');
$url = "http://www.jobua.com/jobs?p=1";
$page_content = file_get_contents($url);
$html = phpQuery::newDocument($page_content);
$posts = $html->find('.row-info');

foreach($posts as $post){  
	$post_link = pq($post)->find('a');
	$titilemore = $post_link->text(); // заголовок поста
	$urlmore = $post_link->attr('href'); // url поста
    //echo "<br>";
        $telephone = array('тел.','номер','тел:','Тел.','Тел:');
        $page_content_more = file_get_contents($urlmore);
        //echo $page_content_more;    
        $htmlmore = phpQuery::newDocument($page_content_more);
        //$title = $htmlmore->find('#job-details h1');
        $city = $htmlmore->find('#job-details p strong:odd');        
        $city2 = $city->text(); // город                        
        $postsmore = $htmlmore->find('#job-description'); 
        $postsmore2 = $postsmore->text(); // сам текст объявления
        $query = mysql_query("SELECT id FROM jb_city WHERE city_name = '".$city2."' LIMIT 1");
        if(mysql_num_rows($query)) { // если город совпадает
            $board = mysql_fetch_array($query);
            //echo $board['id']; 
            foreach($telephone as $value) {
                
   	            if (preg_match ("/$value/i", $postsmore2)){
                //if (preg_match ("/тел./i", $postsmore2)){   	                
                echo $value; 
                echo "<br />ПРошло<br />";
                echo "--->" . $titilemore . "<---";
                echo $board['id'];
                echo $city;
                echo "<br />----||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||---------------<br />";        
                echo $postsmore2;
                echo "<br />----------------------------------------------------------------------------------<br />";                                

    $query_dubl=mysql_query("SELECT id FROM jb_board 
                             WHERE city='".$city2."' AND title='".$titilemore."' 
                             AND text='".$postsmore2."' LIMIT 1");
	if(mysql_num_rows($query_dubl)){ // проверяем на дубли
	   echo "Ошибка! Аналогичное объявление уже присутствует в Базе Данных";
    } else { // иначе вставляем в базу данных
            	$insert=mysql_query("INSERT jb_board SET id_category='1', 
                         type='v', title='".$titilemore."',  
                         city='".$city2."', city_id='".$board['id']."',  
                         text='".$postsmore2."', old_mess='new', 
                         time_delete='180', date_add=NOW(), parser='jobua.com'");  cq(); 
	            $last_id=mysql_insert_id();                
    }                   	           
   	          } 
          }

        }                               
phpQuery::unloadDocuments(); // удалаемдокумент из памяти
gc_collect_cycles(); // запуск сборщика мусора. от утечек памяти. только в php 5.3 и выше
}


// gc_collect_cycles(); // это запуск PHPшного сборщика мусора. от утечек памяти. только в php 5.3 и выше

?>