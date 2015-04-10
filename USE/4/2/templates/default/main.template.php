<?php

// Overall header: usually you don't need to edit anything in it
require_once ('includes/config.php');
function template_overall_header() {
global $settings, $lang, $page_title;

echo '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>'. (strlen($page_title) ? $page_title .' - '. $settings['sitename'] : $settings['sitename']) .'</title>
<link rel="stylesheet" type="text/css" href="/templates/'. $settings['template'] .'/style.css" />
<meta http-equiv="Content-Type" content="text/html; charset='. $lang['charset'] .'" />
<meta name="Description" content="'. $settings['sitedescription'] .'" />
<meta name="Keywords" content="'. $settings['sitekeywords'] .'" />
<meta name="msvalidate.01" content="FFF984E3940FE8298ABD096E4357C15B" />';

if (!empty($settings['icon'])) { 
$objJpeg = new Image_jpeg();
//    $image_url = '/files/image/'. $settings['icon'];
//    echo '<meta content="http://'.$_SERVER['HTTP_HOST'].$image_url.'" property="og:image">';
    echo '<meta content="http://'.$_SERVER['HTTP_HOST'].$objJpeg->getJpeg($settings['icon'],250,250,0).'" property="og:image">';
} else 
    echo '<meta content="http://'.$_SERVER['HTTP_HOST'] .'/images/logo.png" property="og:image">';

echo '<meta content="'.(strlen($page_title) ? $page_title .' - '. $settings['sitename'] : $settings['sitename']).'" property="og:title">
<meta content="'.$settings['sitedescription'].'" property="og:description">  

<SCRIPT LANGUAGE="JavaScript" type="text/JavaScript">
<!-- Begin

	var comment_not_blank = "'. $lang['comment_not_blank'] .'";
	var message_not_blank = "'. $lang['message_not_blank'] .'";
	var sure_want_delete_message = "'. $lang['sure_want_delete_message'] .'";
	var all_fields_required = "'. $lang['all_fields_required'] .'";
	var recipient_not_blank = "'. $lang['recipient_not_blank'] .'";
	var new_pm_click_ok_cancel = "'. $lang['new_pm_click_ok_cancel'] .'";
	var siteurl = "'. $settings['siteurl'] .'";
	var submit_button = "'. $lang['submit'] .'";
	var edit_button = "'. $lang['edit'] .'";
	var thank_you = "'. $lang['thank_you'] .'";

// -->
</script>';
?>
<script type="text/javascript">
var _m_domain = '<?= $_SERVER['HTTP_HOST'] ?>';
var _DATA_PREF = 'http://<?= $_SERVER['HTTP_HOST'] ?>/files/video/';
</script>

<?
echo '
<script type="text/javascript" src="/jscripts/jquery-1.8.2.min.js"></script>
<script type="text/javascript" src="'. $settings['siteurl'] .'/jscripts/global.js"></script>
<script type="text/javascript" src="'. $settings['siteurl'] .'/jscripts/ajax.js"></script>
<script type="text/javascript" src="'. $settings['siteurl'] .'/jscripts/swfobject.js"></script>
<script type="text/javascript" src="'. $settings['siteurl'] .'/jscripts/jquery.paulund_modal_box.js"></script>
<script type="text/javascript" src="'. $settings['siteurl'] .'/jscripts/jquery.qtip.min.js"></script>

<link type="text/css" rel="stylesheet" href="'. $settings['siteurl'] .'/css/jquery.qtip.css" />
<link rel="stylesheet" type="text/css" href="/login_panel/css/slide.css" media="screen" />
<script src="/login_panel/js/slide.js" type="text/javascript"></script>
<link rel="stylesheet" href="'. $settings['siteurl'] .'/css/bootstrap.css">
<script src="'. $settings['siteurl'] .'/jscripts/bootstrap.min.js" type="text/javascript"></script>
<script async="" type="text/javascript" src="'. $settings['siteurl'] .'/jscripts/all.js"></script>

';

echo"
<script type=\"text/javascript\">
  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-40366971-1']);
  _gaq.push(['_trackPageview']);
  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();
</script>
";


echo'
<!--LiveInternet counter-->
<script type="text/javascript"><!--
new Image().src = "//counter.yadro.ru/hit?r"+
escape(document.referrer)+((typeof(screen)=="undefined")?"":
";s"+screen.width+"*"+screen.height+"*"+(screen.colorDepth?
screen.colorDepth:screen.pixelDepth))+";u"+escape(document.URL)+
";h"+escape(document.title.substring(0,80))+
";"+Math.random();//--></script><!--/LiveInternet-->';


echo '</head>';

}

// Header: header part of the arcade site
function template_header() {
	global $settings, $lang, $user, $tbl_prefix,$stats;    

	// Include overall header
	template_overall_header();

	// Show pop-up if user has new PM
	if ($user['newpm'] == '1') {
		echo '
<body onload="Javascript:new_pm();">';

		// Update newpm field, this may not be deleted
		$update_pm_query = mysql_query("UPDATE ". $tbl_prefix ."users SET newpm = '0' WHERE userid = '". $user['id'] ."'");
	} else {
		echo '
<body>';
	}

	echo '

<svg class="hide">
  <defs>
    <path d="M100,25C79.568,25,84.815,0,59.692,0H11.149C5.027,0,0,4.634,0,10.385V25" id="shape-tab"/>             
    <path d="M100,25C79.568,25,84.815,0,59.692,0H11.149C5.027,0,0,4.634,0,10.385V25" id="shape2-tab"/>             
    <path d="M0,25C20.432,25,15.185,0,40.308,0h48.543C94.973,0,100,4.634,100,10.385V25" id="shape-tab-right"/>
  </defs>
</svg> 
        
<!-- Panel -->
<div id="toppanel">
	<div id="panel">
		<div class="content clearfix">
			<div class="left">
				<h1>Панель пользователя:</h1>
				<h2>Вход, регистрация, управление своим профилем</h2>		
				<p class="grey">Обмен сообщениями между пользователями</p>
			</div>
            

		<div class="left right">';
                        			
	    if ($user['status'] == '1') {
			echo '
        '. $lang['welcome_username'] .'<br /><br />
		'. $lang['total_played'] .' '. number_format($user['played']) .'<br />
		'. $lang['total_comments'] .' '. number_format($user['comments']) .'<br />
		'. $lang['date_joined'] .' '. mod_date($user['joined']) .'<br /><br />
		'. ($user['group'] == '2' ? '<a href="'. $settings['siteurl'] .'/admin/index.php" target="_self">'. $lang['admin_cp'] .'</a><br />' : '') .'
		<a href="'. $settings['siteurl'] .'/privatemessages.php" target="_self">'. $lang['private_messages'] .'</a> ('. $lang['unread_messages'] .')<br />
		<a href="'. $settings['siteurl'] .'/usercp.php" target="_self">'. $lang['user_cp'] .'</a><br />
		<a href="'. $settings['siteurl'] .'/login.php?a=logout" target="_self">'. $lang['logout'] .'</a>';
		} else {
			echo '
		<form action="'. $settings['siteurl'] .'/login.php?a=login" method="post">
		  '. $lang['username'] .'<br />
		  <input class="form-control" type="text" name="username" maxlength="25" size="20" />
		  '. $lang['password'] .'<br />
		  <input class="form-control" type="password" name="password" maxlength="20" size="20" />
		  <label><input class="" type="checkbox" name="remember" value="1" checked="checked" />&nbsp;'. $lang['remember_me'] .'</label>
          <br />
		  <input type="submit" name="submit" class="btn btn-success" value="'. $lang['log_in'] .'" />
		</form>
		';
		}                            
echo 			'</div>                        
            <div class="left">            
            <h1>Регистрация:</h1>'
            
. ($settings['guestcredits'] == '1' ? $lang['more_plays_left'] .'<br />' : '') .'
		<a href="'. $settings['siteurl'] .'/profile.php?a=register" target="_self">Нажмите здесь</a>            
            </div>            
            <div class="left right">
            </div>            
		</div>
	</div> <!-- /login -->
	<div id="chat"></div>    
    	

    <!-- The tab on top -->	
	<div class="tab">
		<ul class="login">
	    	<li class="left">&nbsp;</li>
<!--	        <li>Здравствуйте!</li>
			<li class="sep">|</li> -->
			<li id="toggle">
				<a id="open" class="open" href="#">';
                
echo $user['status'] == '1'?'Открыть панель':'Вход | Регистрация';
               
echo '</a><a id="close" style="display: none;" class="close" href="#">Закрыть панель</a>			
			</li>
	    	<li class="right">&nbsp;</li>
		</ul> 
	</div> <!-- / top -->';


?>
<!--
<div class="tab2">
		<ul class="login">
	    	<li class="left">&nbsp;</li>
			<li id="toggle">
				<a href="#" class="open2" id="open2">ЧАТ</a>
                <a href="#" class="close2" style="display: none;" id="close2">Закрыть Чат</a>			
			</li>
	    	<li class="right">&nbsp;</li>
		</ul> 
</div>
-->  
<?




echo '</div> <!--panel -->
    
<center>
<div id="main_body">
  <div id="header">

<nav id="main-nav" class="main-nav">

  <a class="home" href="'. $settings['siteurl'] .'/mostpopular.html">
    <svg class="shape-tab" viewBox="0 0 100 25">
      <use xlink:href="#shape-tab"/>
    </svg>
    <span>ПОПУЛЯРНЫЕ</span>
  </a>
  <a class="videos" href="'. $settings['siteurl'] .'/newgames.html">
    <svg class="shape-tab" viewBox="0 0 100 25">
      <use xlink:href="#shape-tab"/>
    </svg>
    <span>НОВИНКИ</span>
  </a>
  <a class="almanac" href="'. $settings['siteurl'] .'/votegames.html">
    <svg class="shape-tab" viewBox="0 0 100 25">
      <use xlink:href="#shape2-tab"/>
    </svg>
    <span>ПО СИМПАТИЯМ</span>
  </a>
<!--  <a class="snippets" href="/snippets/">
    <svg class="shape-tab" viewBox="0 0 100 25">
      <use xlink:href="#shape-tab"/>
    </svg>
    <span>Snippets</span>
  </a>
  <a class="forums" href="/forums/">
    <svg class="shape-tab" viewBox="0 0 100 25">
      <use xlink:href="#shape-tab"/>
    </svg>
    <span>Forums</span>
  </a>

  <a class="lodge" href="/lodge/">
    <svg class="shape-tab-right" viewBox="0 0 100 25">
      <use xlink:href="#shape-tab-right"/>
    </svg>
    <span><em>The</em> Lodge</span>
  </a>
-->

  <div id="count_today">';
$yesterday = time() - 86400*2;
$date1 = mysql_query("SELECT COUNT(fileid) 
			FROM on_files WHERE 
                        status = '1'
			AND dateadded >= '".$yesterday."'") or die (mysql_error());
$pop = mysql_fetch_array($date1);

echo "За последние 48 часов новых игр: ";  
echo $pop[0] != 0 ? '<font color="red">'.$pop[0].'</font>':$pop[0];


echo  '</div>
</nav>

  </div>
  <div id="categories_bar">
    <ul id="categories_menu">';
	foreach (menu_categories() as $category) {
//echo '<li>'. ($category['break'] == TRUE ? ' | ' : '') .'<a href="'. $category['url'] .'">'. $category['title'] .'</a></li>';

	 echo '<li>
	  <a href="'. $category['url'] .'" class="'.$category['imgclass'].' cat_img12" ></a>
	  <a href="'. $category['url'] .'" class="categ_text" >'. $category['title'] .'</a>
	   </li>';
	}
    echo '
    </ul>
  </div>
  <div id="main_content">';
	template_header_ad();

echo '<div id="main_content_right">';
echo '<div id="int_main_content_right">';
}

// Footer: footer part of the arcade site
function template_footer() {
	global $settings, $lang;

	echo '</div>'; // конец <div id="int_main_content_right">
	echo '</div>'; // конец <div id="main_content_right">


//================== ШАБЛОНА ВСЕГО ЧТО ИДЕТ СЛЕВА ==========================================
	echo '
    <div id="main_content_left">';
//	template_menu();
	global $settings, $lang, $user, $stats;

	echo '
      <div class="content_box_header">
        '. $lang['search'] .'
      </div>
      <div class="content_box srch3">
        <form name="search" action="'. $settings['siteurl'] .'/search.php" method="POST">
		  <div class="srch1"><input class="form-control" type="text" name="t" maxlength="25" size="20" /></div>
		  <div class="srch2"><input class="btn btn-success" type="submit" value="'. $lang['search'] .'" /></div>
		</form>
	  </div>';
      
      
	if ($settings['most_popular_list'] == '1') {
$objImageGd = new Vi_Image_Gd();
echo '<div class="content_box_header m1p"><a href="http://game2ok.com/mostpopular.html"><h2>Наиболее полулярные<h2></a></div>
<div class="content_box lft">';
		$most_popular_query = mysql_query("SELECT description, icon,title,title_en, fileid, timesplayed
                                           FROM on_files
                                           WHERE status = '1'
                                           ORDER BY timesplayed
                                           DESC LIMIT ". $settings['max_most_popular'])
                                           or die (mysql_error());
$i=0;
		while ($popular = mysql_fetch_assoc($most_popular_query)) {
if ($i % 3 == 0) echo '<div class="clearfix"></div>';
$i++;
$img = "/files/image/" . $popular['icon'];
echo '<div class="topgames1" descr="'. $popular['description'] .'">';
echo '<a href="'. fileurl($popular['fileid'], $popular['title']) .'">
<img class="popimg1" src="'. $objImageGd->getImage($img,100,90,0) .'" width="78" height="65"
alt="Онлайн игра '. $popular['title'] .'.Играть онлайн бесплатно в '. $popular['title_en'] .'." border="1" /></a><br />';
echo '<h4 class="smll"><a href="'. fileurl($popular['fileid'], $popular['title']) .'">'. $popular['title'] .'</h4></a>';
// echo '<br>'. number_format($popular['timesplayed']) .' играли<br />';
echo '</div>';
        }
echo '</div>';

	}
	if ($settings['newest_list'] == '1') { // НОВИНКИ СЛЕВА
$objImageGd = new Vi_Image_Gd();
echo '<div class="content_box_header m1p"><a href="http://game2ok.com/newgames.html"><h2>НОВИНКИ<h2></a></div>';
echo '<div class="content_box lft">';
		$most_popular_query = mysql_query("SELECT description,icon,title,title_en, fileid, timesplayed
                                           FROM on_files
                                           WHERE status = '1'
                                           ORDER BY dateadded
                                           DESC LIMIT ". $settings['max_newest'])
                                           or die (mysql_error());
$i=0;
		while ($popular = mysql_fetch_assoc($most_popular_query)) {
if ($i % 3 == 0) echo '<div class="clearfix"></div>';
$i++;
$img = "/files/image/" . $popular['icon'];
echo '<div class="topgames1" descr="'. $popular['description'] .'">';
echo '<a href="'. fileurl($popular['fileid'], $popular['title']) .'">
<img class="popimg1" src="'. $objImageGd->getImage($img,100,90,0) .'" width="78" height="65"
alt="Онлайн игра '. $popular['title'] .'.Играть онлайн бесплатно в '. $popular['title_en'] .'." border="1" /></a><br />';
echo '<h4 class="smll"><a href="'. fileurl($popular['fileid'], $popular['title']) .'">'. $popular['title'] .'</h4></a>';
// echo '<br>'. number_format($popular['timesplayed']) .' играли<br />';
echo '</div>';
        }
	echo '</div>';
	}




$objImageGd = new Vi_Image_Gd();
echo '<div class="content_box_header m1p"><a href="http://game2ok.com/votegames.html"><h2>ПО СИМПАТИЯМ<h2></a></div>
<div class="content_box lft">';
		$most_popular_query = mysql_query("SELECT description,icon,title,title_en, fileid, timesplayed
                                           FROM on_files
                                           WHERE status = '1'
                                           AND rating > '0'
                                           ORDER BY rating 
                                           DESC LIMIT ". $settings['max_most_popular'])
                                           or die (mysql_error());
$i=0;
		while ($popular = mysql_fetch_assoc($most_popular_query)) {
if ($i % 3 == 0) echo '<div class="clearfix"></div>';
$i++;
$img = "/files/image/" . $popular['icon'];
echo '<div class="topgames1" descr="'. $popular['description'] .'">';
echo '<a href="'. fileurl($popular['fileid'], $popular['title']) .'">
<img class="popimg1" src="'. $objImageGd->getImage($img,100,90,0) .'" width="78" height="65"
alt="Онлайн игра '. $popular['title'] .'.Играть онлайн бесплатно в '. $popular['title_en'] .'." border="1" /></a><br />';
echo '<h4 class="smll"><a href="'. fileurl($popular['fileid'], $popular['title']) .'">'. $popular['title'] .'</h4></a>';
// echo '<br>'. number_format($popular['timesplayed']) .' играли<br />';
echo '</div>';
        }
echo '</div>';








	echo '
      <div class="content_box_header">
        '. $lang['statistics'] .'
      </div>
      <div class="content_box">
        '. $lang['total_files'] .' '. number_format($stats['total_files']) .'<br />
		'. $lang['played_today'] .' '. number_format($stats['played_today']) .'<br />
		'. $lang['overall_played'] .' '. number_format($stats['total_played']) .'<br />
		'. ($settings['memberlogin'] == '1' ? $lang['total_members'] .' '. $stats['total_members'] .'<br />' : '') .'
		'. $lang['users_online'] .' '. $stats['total_online'];
	if ($settings['memberlogin'] == '1') {
		echo '
		('. $stats['members_online_number'] .' '. ($stats['members_online_number'] == '1' ? $lang['member_online'] : $lang['members_online']) .', '. $stats['guests_online_number'] .' '. ($stats['guests_online_number'] == '1' ? $lang['guest_online'] : $lang['guests_online']) .')<br />';
		if ($stats['members_online_number'] > '0') {
			echo '
		'. $lang['members_online_list'] .' '. $stats['members_online_list'];
		}
	}
	echo '
	  </div>';


	if ($settings['top_players_list'] == '1' && $settings['memberlogin'] == '1') {
		echo '
      <div class="content_box_header">
        '. $lang['top_players'] .'
      </div>
      <div class="content_box">';
		foreach (top_players() as $players) {
			echo '
        '. $players['position'] .'. <a href = "'. profileurl($players['id'],$players['name']) .'" target="_self">'. $players['name'] .'</a> ('. $players['plays'] .' '. ($settings['top_players_list'] == '2' ? $lang['top_wins'] : $lang['top_plays']) .')<br />';
		}
		echo '
	  </div>';
	}

	if ($settings['links'] == '1') {
		echo '
      <div class="content_box_header">
        '. $lang['links'] .'
      </div>
      <div class="content_box">';
		foreach (top_links() as $links) {
			echo '
        <a href = "'. $links['url'] .'" onclick="return link_out('. $links['id'] .');">'. $links['title'] .'</a><br />';
		}
		echo'
        <br /><a href="'. $settings['siteurl'] .'/links.php" target="_self">'. $lang['more_links'] .'</a>
	  </div>';
	}
//	echo '
//	  <div align="center">
//	    <a href="'. $settings['siteurl'] .'/rss.php"><img src="'. $settings['siteurl'] .'/images/rss_feed.gif" width="80" height="15" title="'. $lang['rss_feed'] .'" alt="'. $lang['rss_feed'] .'" border="0" /></a>
//	  </div> ';

	echo '</div>';
//================== КОНЕЦ ШАБЛОНА ВСЕГО ЧТО ИДЕТ СЛЕВА ==========================================



    echo'<div style="clear: both;"></div>';
    template_footer_ad();
    template_footer_index();
    echo'<ul id="main_menu">
	  <li><a href="'. $settings['siteurl'] .'/contact.php">'. $lang['contact_us'] .'</a></li>';
	if ($settings['submit'] == '1' || $user['status'] == '1' && $settings['submit'] == '2') {
		echo '
	  <li><a href="'. $settings['siteurl'] .'/submit.php">'. $lang['submit_content'] .'</a> | </li>';
	}
	if ($settings['links'] == '1') {
		echo '
	  <li><a href="'. $settings['siteurl'] .'/links.php">'. $lang['links'] .'</a> | </li>';
	}
	if ($settings['memberlogin'] == '1') {
		echo '
	  <li><a href="'. $settings['siteurl'] .'/memberlist.php">'. $lang['member_list'] .'</a> | </li>';
	}
	echo '
	  <li><a href="'. $settings['siteurl'] .'/">'. $lang['home'] .'</a> | </li>
	</ul>';
    echo'<div style="clear: both;"></div>';
    echo '
    '. $lang['footer_copyright'] .'<br /><br />';
echo'
<!-- Yandex.Metrika informer -->
<a href="http://metrika.yandex.ru/stat/?id=21271045&amp;from=informer"
target="_blank" rel="nofollow"><img src="//bs.yandex.ru/informer/21271045/3_1_E0E0E0FF_C0C0C0FF_0_pageviews"
style="width:88px; height:31px; border:0;" alt="Яндекс.Метрика" title="Яндекс.Метрика: данные за сегодня (просмотры, визиты и уникальные посетители)" onclick="try{Ya.Metrika.informer({i:this,id:21271045,lang:\'ru\'});return false}catch(e){}"/></a>
<!-- /Yandex.Metrika informer -->

<!-- Yandex.Metrika counter -->
<script type="text/javascript">
(function (d, w, c) {
    (w[c] = w[c] || []).push(function() {
        try {
            w.yaCounter21271045 = new Ya.Metrika({id:21271045,
                    webvisor:true,
                    clickmap:true,
                    trackLinks:true,
                    accurateTrackBounce:true});
        } catch(e) { }
    });

    var n = d.getElementsByTagName("script")[0],
        s = d.createElement("script"),
        f = function () { n.parentNode.insertBefore(s, n); };
    s.type = "text/javascript";
    s.async = true;
    s.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//mc.yandex.ru/metrika/watch.js";

    if (w.opera == "[object Opera]") {
        d.addEventListener("DOMContentLoaded", f, false);
    } else { f(); }
})(document, window, "yandex_metrika_callbacks");
</script>
<noscript><div><img src="//mc.yandex.ru/watch/21271045" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter --> 




<!--LiveInternet logo--><a href="http://www.liveinternet.ru/click"
target="_blank"><img src="//counter.yadro.ru/logo?14.9"
title="LiveInternet: показано число просмотров за 24 часа, посетителей за 24 часа и за сегодня"
alt="" border="0" width="88" height="31"/></a><!--/LiveInternet-->





';

echo '</div>
</div>
</center>
</body>
</html>';

}

// Menu: arcade menu
//function template_menu() {
//
//}

// Index page: this is the home page of your arcade
//function template_index() {
//}

// Files: these files are displayed on your index page
//function template_files_index() {
//}

// News: display news on index page

function template_footer_index() {
    $arrRequestUri = explode('/', $_SERVER['REQUEST_URI']);
    $url000 = $arrRequestUri[1];
    if ($url000 == ''){    
?>
<div class="derindex">
<p>Здравствуйте, дорогие друзья! У нас Вы сможете найти любые игры (флеш и 3D игры), хорошо отдохнуть и сможете 
получить море удовольствие от игрового процесса. Для того, чтобы начать играть в онлайн игры, вам 
не нужно ничего скачивать. Вы сможете играть прямо из браузера, которым просматриваете данную страницу. 
У нас на сайте размещещаются только лучшие флеш и unity 3D игры. Все игры проходыт ручной отбор, чтобы исключить 
некачественные игры !</p>
<p>На сайте большое разнообразие различных жанров и видов флеш игр, и игр unity3D , все игры можно играть онлайн. 
Для того чтобы приступить к игре достаточно, чтобы в вашем браузере был установлен 
Adobe Flash Player , и для unity3d нужен Unity Web Player. Все игры можно играть бесплатно и без регистрации, они не требуют 
загрузки и каких-то специальных требований от компьютера.</p>
<p>На сайте вы найдете самые популярные жанры flash игр, такие как: аркадные игры, леталки,
стрелялки, спортивные, гонки, логические, игры с разных приставок, азартные,  активные игры, различные стратегии, 
стратегии, развивающие, для детей и многие другие. Мы стараемся следить за новинками игровой flash 
индустрии и пополнять нашу игровую коллекцию самыми свежими разработками в этой области. Мы 
всегда ждем Вас на нашем игровом портале и надеемся, что Вы по достоинству оцените качество 
размещенных на нем бесплатных флеш игр.</p>
</div>
<?
    }
}

function template_news_index() {
	global $news;

	$line = FALSE;
	// Display news
	foreach (news() as $news_p) {
		if ($line == TRUE) {
			echo '
        <div class="news_line">';
		} else {
			echo '
        <div>';
			$line = TRUE;
		}

		echo '
		  <b>'. $news_p['title'] .'</b> ('. $news_p['date'] .')<br />
		  '. $news_p['text'] .'
        </div>';
	}
}

// RSS Feeds: list of RSS Feeds
function template_rss_feeds() {
	global $settings, $lang;

	template_header();
	echo '
      <div class="content_box_header">
        '. $lang['rss_feed'] .'
      </div>
      <div class="content_box">
	    '. $lang['rss_feed_introduction'] .'<br />
		- <a href="'. $settings['siteurl'] .'/rss.php?r=mostpopular&l=10">'. $lang['rss_most_popular_files'] .'</a><br />
		- <a href="'. $settings['siteurl'] .'/rss.php?r=newest&l=10">'. $lang['rss_newest_files'] .'</a><br />
		- <a href="'. $settings['siteurl'] .'/rss.php?r=news&l=10">'. $lang['rss_news'] .'</a>
	  </div>';
	template_footer();

}

// Redirection Page: this is the redirection page
function template_redirect($redirect_url, $redirect_message) {
	global $settings, $lang;

	echo '
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>

<title>'. $lang['redirecting'] .'</title>
<link rel="stylesheet" type="text/css" href="'. $settings['siteurl'] .'/templates/'. $settings['template'] .'/style.css" />
<meta http-equiv="Content-Type" content="text/html; charset='. $lang['charset'] .'" />
<meta http-equiv="refresh" content="1; url='. $redirect_url .'" />

</head>

<body>
<center>

<div class="redirection_box">
  '. $redirect_message .'<br />
  <br />
  '. $lang['if_not_redirected'] .'
</div>

</center>
</body>
</html>';

}

// Header Ad: this is the ad that is showed on top of the page
function template_header_ad() {
	global $settings, $ads;

	if ($settings['header_ad'] == '1') {
		echo '
    <div class="ad_box">
	  '. $ads['header'] .'
    </div>';
	}
}

// Footer Ad: this is the ad that is showed on top of the page
function template_footer_ad() {
	global $settings, $ads;

	if ($settings['footer_ad'] == '1') {
		echo '
    <div class="ad_box">
	  '. $ads['footer'] .'
    </div>';
	}
}

// Blank Page: this is the page that is used for stuff that don't have their own template functions
function template_blank_page($blank_page) {

	template_header();
	echo '
      <div class="content_box_header">
        '. $blank_page['title'] .'
      </div>
      <div class="content_box">
	    '. $blank_page['content'] .'
	  </div>';
	template_footer();

}

?>