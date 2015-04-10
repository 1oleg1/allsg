<?php
 
// AdminCP Overall header: this usually doesn't need any editing
function template_overall_header() {
	global $settings, $lang, $page_title;
	
	echo '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>

<head>

<title>'. (strlen($page_title) ? $settings['sitename'] .' - '. $page_title : $settings['sitename']) .'</title>
<link rel="alternate" type="application/rss+xml" title="'. $lang['rss_newest_files'] .' (RSS 2.0)" href="'. $settings['siteurl'] .'/rss.php?r=newest&l=10" />
<link rel="stylesheet" type="text/css" href="'. $settings['siteurl'] .'/templates/'. $settings['template'] .'/admincp_style.css" />
<meta http-equiv="Content-Type" content="text/html; charset='. $lang['charset'] .'" />
<meta name="Description" content="'. $settings['sitedescription'] .'" />
<meta name="Keywords" content="'. $settings['sitekeywords'] .'" />

<SCRIPT LANGUAGE="JavaScript" type="text/JavaScript">
<!-- Begin

	var title_not_blank = "'. $lang['title_not_blank'] .'";
	var sure_want_delete = "'. $lang['sure_want_delete'] .'";

// -->
</script>
<script type="text/javascript" src="'. $settings['siteurl'] .'/jscripts/admin.js"></script>

<link rel="stylesheet" href="'. $settings['siteurl'] .'/css/bootstrap.css">
<script src="'. $settings['siteurl'] .'/jscripts/bootstrap.min.js" type="text/javascript"></script>


</head>';

}

// Header: header part of the AdminCP
function template_header() {
	global $settings, $lang;

	// Include overall header
	template_overall_header();
	
	echo '
<body>

<center>
<div class="content">
  <div class="top">
    <div class="top_left">
      Админ панель
    </div>
    <div class="top_right">
      <a href="index.php" class="top_link">Главная</a> | 
      <a href="content.php" class="top_link">Контент</a> | 
      <a href="members.php" class="top_link">Пользователи</a> | 
      <a href="configurations.php" class="top_link">Настройки</a>
    </div>
    <div style="clear: both;"></div>

  </div>';
  
}

// Footer: footer part of the AdminCP
function template_footer() {
	echo '
</div>
</center>

</body>


</html>';

}

// Main Menu: menu of main pages
function template_main_menu() {
	global $settings, $lang;
	
	echo '
    <div class="content_box">
      <div class="content_box_header">
        '. $lang['main'] .'
      </div>
      <div class="box_text">
  	    <a href="index.php">'. $lang['main'] .'</a><br />
  	    <a href="index.php?a=statistics">'. $lang['statistics'] .'</a>
  	  </div>
  	</div>';

}

// Main: main page of AdminCP
function template_main() {
	global $settings, $lang, $text, $stats;
	
	// Include header
	template_header();
	
	echo '
  <div class="admin_left">';
	template_main_menu();
	echo '
  </div>
  <div class="admin_right">
    <div class="content_box_2">
      <div class="content_box_2_header">
        '. $lang['main'] .'
      </div>
      <div class="box_text">';
	if (@$text['unapproved_files']) {
		echo '
        <div style="border: 1px dotted #FF6600; margin: 5px; padding: 2px; background: #FFCCCC;">
          <a href="content.php?a=approve_files"><font color="#FF6600">'. $lang['have_unapproved_files'] .'</font></a>
        </div>';
	}
	if (@$text['unapproved_links']) {
		echo '
        <div style="border: 1px dotted #6B8E23; margin: 5px; padding: 2px; background: #DFFFA5;">
          <a href="content.php?a=approve_links"><font color="#808000">'. $lang['have_unapproved_links'] .'</font></a>
        </div>';
	}
	if (@$text['unapproved_comments']) {
		echo '
        <div style="border: 1px dotted #FFC125; margin: 5px; padding: 2px; background: #FEF1B5;">
          <a href="content.php?a=approve_comments"><font color="#385E0F">'. $lang['have_unapproved_comments'] .'</font></a>
        </div>';
	}
	if ($text['broken_files']) {
		echo '
        <div style="border: 1px dotted #00688B; margin: 5px; padding: 2px; background: #ADD8E6;">
          <a href="content.php?a=broken_files"><font color="#00688B">'. $lang['have_broken_file_reports'] .'</font></a>
        </div>';
	}
	echo '
        '. @$text['update_message'] .'
        <br /><br />
        '. $lang['total_files'] .' '. number_format($stats['total_files']) .' - <a href="'. $settings['siteurl'] .'/admin/index.php?a=recount_files">'. $lang['recount'] .'</a><br />
        '. $lang['played_today'] .' '. number_format($stats['played_today']) .'<br />
        '. $lang['total_played'] .' '. number_format($stats['total_played']) .'<br />
        '. $lang['total_members'] .' '. number_format($stats['total_members']) .' - <a href="'. $settings['siteurl'] .'/admin/index.php?a=recount_members">'. $lang['recount'] .'</a><br />
        <a href="'. $settings['siteurl'] .'/admin/index.php?a=clear_cache">'. $lang['clear_cache'] .'</a><br />
        <br />
        <a href="http://www.onarcade.com/forums/" target="_blank">'. $lang['support_forums'] .'</a> - <a href="mailto:support@onarcade.com">'. $lang['email_support'] .'</a>
        <div align="right">
		  <a href="http://www.onarcade.com/onarcade2_help/?p=100" target="_blank"><img src="images/help.gif" border="0" alt="'. $lang['help'] .'" title="'. $lang['help'] .'" /></a>
		</div>
  	  </div>
  	</div>
  </div>
  <div style="clear: both;"></div>
  ';
	
	// Include footer
	template_footer();

}

// Statistics: here we see all the important statistics that onArcade has stored
function template_statistics() {
	global $settings, $lang, $statistics;

	// Include header
	template_header();
	
	echo '
  <div class="admin_left">';
	template_main_menu();
	echo '
  </div>
  <div class="admin_right">
    <div class="content_box_2">
      <div class="content_box_2_header">
        '. $lang['statistics'] .'
      </div>
      <div class="box_text">
        <div style="float: left; width: 20%; text-align: center;"><b>'. $lang['date'] .'</b></div><div style="float: left; width: 20%; text-align: center;"><b>'. $lang['played'] .'</b></div><div style="float: left; width: 20%; text-align: center;"><b>'. $lang['total_played'] .'</b></div><div style="float: left; width: 20%; text-align: center;"><b>'. $lang['total_files'] .'</b></div><div style="float: left; width: 20%; text-align: center;"><b>'. $lang['total_members'] .'</b></div>
        <div class="line"></div>';
	foreach ($statistics as $s) {
		echo '
<div style="float: left; width: 20%; text-align: center;">'. $s['date'] .'</div><div style="float: left; width: 20%; text-align: center;">'. $s['played'] .'</div><div style="float: left; width: 20%; text-align: center;">'. $s['total_played'] .'</div><div style="float: left; width: 20%; text-align: center;">'. $s['total_files'] .'</div><div style="float: left; width: 20%; text-align: center;">'. $s['total_members'] .'</div>
        <div class="line"></div>';
	}
	echo '
		<div align="center">
		  <a href="index.php?a=clear_stats">'. $lang['clear_stats'] .'</a> - <a href="index.php?a=empty_stats" onclick="return confirm_delete()">'. $lang['empty_stats'] .'</a>
		</div>
		<div align="right">
		  <a href="http://www.onarcade.com/onarcade2_help/?p=101" target="_blank"><img src="images/help.gif" border="0" alt="'. $lang['help'] .'" title="'. $lang['help'] .'" /></a>
		</div>
  	  </div>
  	</div>
  </div>
  <div style="clear: both;"></div>
  ';
	
	// Include footer
	template_footer();

}

// Content Menu: menu of content pages
function template_content_menu() {
	global $settings, $lang;
	
	echo '
    <div class="content_box">
      <div class="content_box_header">
        Игры
      </div>
      <ul class="box_text nav nav-pills nav-stacked"">
  	    <li><a href="content.php">Игры</a></li>
  	    <li><a href="content.php?a=add_file">Добавить игру</a></li>
  	    <li><a href="content.php?a=select_file">Выбрать файлы</a></li>
  	    <li><a href="content.php?a=upload_file">Загрузить файлы</a></li>
  	    <li><a href="content.php?a=grab_file">Сграбить файлы</a></li>
  	    <li><a href="content.php?a=download_youtube">Скачать из YouTube</a></li>
  	    <li><a href="content.php?a=approve_files">Одобрить игры пользователей</a></li>
  	    <li><a href="content.php?a=broken_files">Поврежденные файлы</a></li>
  	    <li><a href="content.php?a=approve_comments">Одобрить комментарии</a></li>
  	    <li><a href="content.php?a=install_game_pack">'. $lang['install_game_pack'] .'</a></li>
  	    <li><a href="content.php?a=game_feed">'. $lang['game_feed'] .'</a></li>
  	    <li><a href="configurations.php?a=file_settings">Настройки</a></li>
  	  </ul>
  	</div>
	<div class="content_box">
      <div class="content_box_header">
        Поисковые теги
      </div>
      <div class="box_text">
  	    <a href="content.php?a=tags">Поисковые теги</a><br />
  	    <a href="content.php?a=add_tags">Добавить поисковые теги</a><br />
  	  </div>
  	</div>    
	<div class="content_box">
      <div class="content_box_header">
        Категории
      </div>
      <div class="box_text">
  	    <a href="content.php?a=categories">Категории</a><br />
  	    <a href="content.php?a=add_category">Добавить категорию</a><br />
  	  </div>
  	</div>
  	<div class="content_box">
      <div class="content_box_header">
        Новости
      </div>
      <div class="box_text">
  	    <a href="content.php?a=news">Статьи</a><br />
  	    <a href="content.php?a=add_news">Добавить статью</a><br />
  	  </div>
  	</div>
	<div class="content_box">
      <div class="content_box_header">
        Линки
      </div>
      <div class="box_text">
  	    <a href="content.php?a=links">Линк</a><br />
  	    <a href="content.php?a=approve_links">Разрешить линк</a><br />
  	    <a href="content.php?a=add_link">Добавить линк</a><br />
  	  </div>
  	</div>
	<div class="content_box">
      <div class="content_box_header">
        '. $lang['ads'] .'
      </div>
      <div class="box_text">
  	    <a href="content.php?a=ads">'. $lang['ads'] .'</a><br />
  	    <a href="content.php?a=add_ad">'. $lang['add_ad'] .'</a><br />
  	  </div>
  	</div>
	<div class="content_box">
      <div class="content_box_header">Отдельные страницы</div>
      <div class="box_text">
  	    <a href="content.php?a=custom_pages">Страницы</a><br />
  	    <a href="content.php?a=add_custom_page">Добавить отдельную страницу</a><br />
  	  </div>
  	</div>';

}

// Files: here is a list of files
function template_files() {

}

// Add File: here we can add new files
function template_add_file() {
	global $settings, $lang, $add_file;

	// Include header
	template_header();
	
	echo '
  <div class="admin_left">';
	template_content_menu();
	echo '
  </div>
  <div class="admin_right">';
	if (strlen($add_file['error'])) {
		echo '
    <div class="error_box">
      '. $add_file['error'] .'
    </div>';
	}
	echo '
    <div class="content_box_2">
      <div class="content_box_2_header">
        '. $lang['add_file'] .'
      </div>
      <div class="box_text">
        <form action="" method="POST" name="form" onsubmit="return verify_add()">
		  <div class="content_text_left" style="width: 20%;">'. $lang['title'] .'</div>
		  <div class="content_text_right" style="width: 80%;"><input type="text" name="title" size="85" /></div>
		  <div style="clear: both;"></div>
          
		  <div class="content_text_left" style="width: 20%;">Title для SEO</div>
		  <div class="content_text_right" style="width: 80%;"><input type="text" name="title_m" size="85" /></div>
		  <div style="clear: both;"></div>          
          
		  <div class="content_text_left" style="width: 20%;">'. $lang['description'] .'</div>
		  <div class="content_text_right" style="width: 80%;"><textarea name="description" rows="10" cols="65"></textarea></div>
		  <div style="clear: both;"></div>
          
		  <div class="content_text_left" style="width: 20%;">Дискрипшин для SEO</div>
		  <div class="content_text_right" style="width: 80%;"><textarea name="description_m" rows="3" cols="85"></textarea></div>
		  <div style="clear: both;"></div>          
          
		  <div class="content_text_left" style="width: 20%;">'. $lang['keywords'] .'</div>
		  <div class="content_text_right" style="width: 80%;"><textarea name="keywords" rows="3" cols="85"></textarea></div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">'. $lang['file_category'] .'</div>
		  <div class="content_text_right" style="width: 80%;">
		  '. $add_file['categories'] .'
		  </div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">'. $lang['size_width_height'] .'</div>
		  <div class="content_text_right" style="width: 80%;"><input type="text" name="width" size="3" /> x <input type="text" name="height" size="3" /></div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">'. $lang['file_type'] .'</div>
		  <div class="content_text_right" style="width: 80%;">
		    '. $add_file['file_types'] .'
		  </div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">'. $lang['file_file'] .'</div>
		  <div id="enter_file" class="content_text_right" style="width: 80%;">
		    <input type="text" name="file_file" value="'. $add_file['file'] .'" size="35" />
			<select name="file_location">
			  <option value="1">'. $lang['local'] .'</option>
			  <option value="2">'. $lang['linked'] .'</option>
			  <option value="3">'. $lang['frame'] .'</option>
			</select>
		  </div>
		  <div id="enter_custom_code" class="content_text_right" style="width: 80%; display: none;">
		    <textarea name="custom_code" rows="3" cols="35"></textarea>
		  </div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">'. $lang['file_image'] .'</div>
		  <div class="content_text_right" style="width: 80%;">
		    <input type="text" name="file_image" value="'. $add_file['image'] .'" size="35" />
			<select name="image_location">
			  <option value="1">'. $lang['local'] .'</option>
			  <option value="2">'. $lang['linked'] .'</option>
			</select>
		  </div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">'. $lang['status'] .'</div>
		  <div class="content_text_right" style="width: 80%;">
		    <select name="status">
			  <option value="1">'. $lang['active'] .'</option>
			  <option value="2">'. $lang['inactive'] .'</option>
			  <option value="3">'. $lang['game_slave'] .'</option>
			</select>
		  </div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">'. $lang['adult_file'] .'</div>
		  <div class="content_text_right" style="width: 80%;">
		    <select name="adult">
			  <option value="0">'. $lang['no'] .'</option>
			  <option value="1">'. $lang['yes'] .'</option>
			</select>
		  </div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">'. $lang['scores'] .'</div>
		  <div class="content_text_right" style="width: 80%;">
		    <select name="scores" onchange="javascript:highscore_type_show()">
			  <option value="0">'. $lang['off'] .'</option>
			  <option value="1">'. $lang['on'] .'</option>
			</select>
		  </div>
		  <div style="clear: both;"></div>
		  <span id="highscore_type" style="display: none;">
		  <div class="content_text_left" style="width: 20%;">'. $lang['highscore_type'] .'</div>
		  <div class="content_text_right" style="width: 80%;">
		    <select name="score_type">
			  <option value="1">'. $lang['score_high'] .'</option>
			  <option value="2">'. $lang['score_low'] .'</option>
			</select>
		  </div>
		  <div style="clear: both;"></div>
		  </span>
		  <div align="center">
		    <input class = "myButton" type="submit" name="submit_file" value="'. $lang['submit'] .'" />
		  </div>
		</form>
  	  </div>
  	</div>
  </div>
  <div style="clear: both;"></div>
  ';
	
	// Include footer
	template_footer();

}

// Edit File: here we can edit old files
function template_edit_file() {

}

// File Comments: these are comments added to file
function template_file_comments() {
	global $settings, $lang, $comments;

	// Include header
	template_header();
	
	echo '
  <div class="admin_left">';
	template_content_menu();
	echo '
  </div>
  <div class="admin_right">
    <div class="content_box_2">
      <div class="content_box_2_header">
	    '. $lang['comments'] .'
      </div>
      <div class="box_text">
        <form name="form" action="" method="post">
        <div style="float: left; width: 35%; text-align: center;"><b>'. $lang['comment'] .'</b></div><div style="float: left; width: 15%; text-align: center;"><b>'. $lang['poster'] .'</b></div><div style="float: left; width: 15%; text-align: center;"><b>'. $lang['ip'] .'</b></div><div style="float: left; width: 15%; text-align: center;"><b>'. $lang['date_added'] .'</b></div><div style="float: left; width: 15%; text-align: center;"><b>'. $lang['status'] .'</b></div><div style="float: left; width: 5%; text-align: center;"><input type="checkbox" name="all" onclick="check_all();" /></div>
        <div class="line"></div>';
	foreach ($comments as $comment) {
		echo '
        <div style="float: left; width: 35%; text-align: left;">'. $comment['comment'] .'</div><div style="float: left; width: 15%; text-align: center;">'. $comment['poster_username'] .'&nbsp;</div><div style="float: left; width: 15%; text-align: center;">'. $comment['ip'] .'</div><div style="float: left; width: 15%; text-align: center;">'. $comment['date'] .'</div><div style="float: left; width: 15%; text-align: center;">'. $comment['status'] .'</div><div style="float: left; width: 5%; text-align: center;"><input type="checkbox" name="comment_id['. $comment['id'] .']" value="ok" /></div>
        <div class="line"></div>';
	}
	echo '
		<div align="right">
			  <select name="comments_action">
				<option value="delete_comment">'. $lang['delete'] .'</option>
				<option value="approve">'. $lang['approve'] .'</option>
			  </select> 
			<input type="submit" name="submit_comments" value="'. $lang['go'] .'" />
		</div>
		<div align="right">
		  <a href="http://www.onarcade.com/onarcade2_help/?p=104" target="_blank"><img src="images/help.gif" border="0" alt="'. $lang['help'] .'" title="'. $lang['help'] .'" /></a>
		</div>
        </form>
  	  </div>
  	</div>
  </div>
  <div style="clear: both;"></div>
  ';
	
	// Include footer
	template_footer();

}

// Edit scores: on this page we see scores
function template_edit_scores() {
	global $settings, $lang, $file, $scores;

	// Include header
	template_header();
	
	echo '
  <div class="admin_left">';
	template_content_menu();
	echo '
  </div>
  <div class="admin_right">
    <div class="content_box_2">
      <div class="content_box_2_header">
	    '. $file['title'] .' '. $lang['scores_title'] .'
      </div>
      <div class="box_text">
        <form name="form" action="" method="post">
        <div style="float: left; width: 5%; text-align: center; margin: 2px;">#</div><div style="float: left; width: 20%; text-align: center; margin: 2px;">'. $lang['username'] .'</div><div style="float: left; width: 10%; text-align: center; margin: 2px;">'. $lang['score'] .'</div><div style="float: left; width: 30%; text-align: center; margin: 2px;">'. $lang['comment'] .'</div><div style="float: left; width: 10%; text-align: center; margin: 2px;">'. $lang['ip'] .'</div><div style="float: left; width: 15%; text-align: center; margin: 2px;">'. $lang['date'] .'</div><div style="float: left; width: 5%; text-align: center;"><input type="checkbox" name="all" onclick="check_all();" /></div>
        <div class="line"></div>';
	foreach ($scores AS $score)
		echo '
		<div style="float: left; width: 5%; text-align: center; margin: 2px;">'. $score['position'] .'</div><div style="float: left; width: 20%; text-align: center; margin: 2px;"><a href="'. profileurl($score['user_id'], $score['username']) .'" target="_blank">'. $score['username'] .'</a></div><div style="float: left; width: 10%; text-align: center; margin: 2px;">'. $score['score'] .'</div><div style="float: left; width: 30%; text-align: center; margin: 2px;">'. $score['comment'] .'</div><div style="float: left; width: 10%; text-align: center; margin: 2px;">'. $score['ip'] .'</div><div style="float: left; width: 15%; text-align: center; margin: 2px;">'. $score['date'] .'</div><div style="float: left; width: 5%; text-align: center;"><input type="checkbox" name="score_id['. $score['id'] .']" value="ok" /></div>
        <div class="line"></div>';
	echo '
		<div align="right">
			  <select name="scores_action">
				<option value="delete_scores">'. $lang['delete'] .'</option>
			  </select> 
			<input class = "myButton" type="submit" name="submit_scores" value="'. $lang['go'] .'" />
		</div>
        </form>
  	  </div>
  	</div>
  </div>
  <div style="clear: both;"></div>
  ';
	
	// Include footer
	template_footer();

}

// Select File: already uploaded, but not used files can be found here
function template_select_file() {
	global $settings, $lang, $file_selection, $image_selection;

	// Include header
	template_header();
	
	echo '
  <div class="admin_left">';
	template_content_menu();
	echo '
  </div>
  <div class="admin_right">
    <div class="content_box_2">
      <div class="content_box_2_header">
        '. $lang['select_file'] .'
      </div>
      <div class="box_text">
        <form action="" method="POST" name="form">
		  <div class="content_text_left" style="width: 15%;">'. $lang['file_file'] .'</div>
		  <div class="content_text_right" style="width: 85%;">
		    '. $file_selection .'
		  </div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 15%;">'. $lang['file_image'] .'</div>
		  <div class="content_text_right" style="width: 85%;">
		    '. $image_selection .'
		  </div>
		  <div style="clear: both;"></div>
		  <div align="center">
		    <input class = "myButton" type="submit" name="submit_file" value="'. $lang['submit'] .'" />
		  </div>
		</form>
  	  </div>
  	</div>
  </div>
  <div style="clear: both;"></div>
  ';
	
	// Include footer
	template_footer();

}

// Upload File: ever now and then you don't wish to onen your FTP client to upload files, this is what you use then
function template_upload_file() {
	global $settings, $lang, $upload_error;

	// Include header
	template_header();
	
	echo '
  <div class="admin_left">';
	template_content_menu();
	echo '
  </div>
  <div class="admin_right">';
	if (strlen($upload_error)) {
		echo '
    <div class="error_box">
      '. $upload_error .'
    </div>';
	}
	echo '
    <div class="content_box_2">
      <div class="content_box_2_header">
        '. $lang['upload_file'] .'
      </div>
      <div class="box_text">
        <form action="" method="POST" name="form" ENCTYPE="multipart/form-data">
		  <div class="content_text_left" style="width: 15%;">'. $lang['file_file'] .'</div>
		  <div class="content_text_right" style="width: 85%;"><input type="file" name="upload_file" /></div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 15%;">'. $lang['file_image'] .'</div>
		  <div class="content_text_right" style="width: 85%;"><input type="file" name="upload_image" /></div>
		  <div style="clear: both;"></div>
		  <div align="center">
		    <input class = "myButton" type="submit" name="submit_file" value="'. $lang['submit'] .'" />
		  </div>
		</form>
  	  </div>
  	</div>
  </div>
  <div style="clear: both;"></div>
  ';
	
	// Include footer
	template_footer();

}

// Grab File: if there is some kind of cool file on some other site and you want it on yours, then here is where you download it
function template_grab_file() {
	global $settings, $lang, $grab_error;

	// Include header
	template_header();
	
	echo '
  <div class="admin_left">';
	template_content_menu();
	echo '
  </div>
  <div class="admin_right">';
	if (strlen($grab_error)) {
		echo '
    <div class="error_box">
      '. $grab_error .'
    </div>';
	}
	echo '
    <div class="content_box_2">
      <div class="content_box_2_header">
        '. $lang['grab_file'] .'
      </div>
      <div class="box_text">
        <form action="" method="POST" name="form">
		  <div class="content_text_left" style="width: 15%;">'. $lang['file_file'] .'</div>
		  <div class="content_text_right" style="width: 85%;"><input type="text" name="grab_file" size="35" /></div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 15%;">'. $lang['file_image'] .'</div>
		  <div class="content_text_right" style="width: 85%;"><input type="text" name="grab_image" size="35" /></div>
		  <div style="clear: both;"></div>
		  <div align="center">
		    <input class = "myButton"  type="submit" name="submit_file" value="'. $lang['submit'] .'" />
		  </div>
		</form>
  	  </div>
  	</div>
  </div>
  <div style="clear: both;"></div>
  ';
	
	// Include footer
	template_footer();

}

// Download from YouTube: YouTube has some great videos, you can downlod them here
function template_download_youtube() {
	global $settings, $lang, $youtube_error;

	// Include header
	template_header();
	
	echo '
  <div class="admin_left">';
	template_content_menu();
	echo '
  </div>
  <div class="admin_right">';
	if (strlen($youtube_error)) {
		echo '
    <div class="error_box">
      '. $youtube_error .'
    </div>';
	}
	echo '
    <div class="content_box_2">
      <div class="content_box_2_header">
        '. $lang['download_from_youtube'] .'
      </div>
      <div class="box_text">
        <form action="" method="POST" name="form">
		  <div class="content_text_left" style="width: 10%;">'. $lang['url'] .'</div>
		  <div class="content_text_right" style="width: 90%;"><input type="text" name="url" size="50" /></div>
		  <div style="clear: both;"></div>
		  <div align="center">
		    <input type="submit" name="submit_file" value="'. $lang['submit'] .'" />
		  </div>
		</form>
  	  </div>
  	</div>
  </div>
  <div style="clear: both;"></div>
  ';
	
	// Include footer
	template_footer();

}


// Files: here is a list of files that need approval
function template_approve_files() {
	global $settings, $lang, $files, $nav, $search_term;

	// Include header
	template_header();
	
	echo '
  <div class="admin_left">';
	template_content_menu();
	echo '
  </div>
  <div class="admin_right">
    <div class="content_box_2">
      <div class="content_box_2_header">
        '. $lang['approve_files'] .'
      </div>
      <div class="box_text">
        <form name="form" action="" method="post">
        <div style="float: left; width: 20%; text-align: center;"><b>'. $lang['title'] .'</b></div><div style="float: left; width: 15%; text-align: center;"><b>'. $lang['file_category'] .'</b></div><div style="float: left; width: 10%; text-align: center;"><b>'. $lang['file_type'] .'</b></div><div style="float: left; width: 20%; text-align: center;"><b>'. $lang['date_added'] .'</b></div><div style="float: left; width: 10%; text-align: center;"><b>'. $lang['added_by'] .'</b></div><div style="float: left; width: 25%; text-align: center;">&nbsp;</div>
        <div class="line"></div>';
	foreach ($files as $f) {
		echo '
        <div style="float: left; width: 20%; text-align: left;"><a href="'. $f['file'] .'" target="_blank">'. $f['title'] .'</a></div><div style="float: left; width: 15%; text-align: center;">'. $f['category'] .'</div><div style="float: left; width: 10%; text-align: center;">'. $f['file_type'] .'</div><div style="float: left; width: 20%; text-align: center;">'. $f['date_added'] .'</div><div style="float: left; width: 10%; text-align: center;">'. $f['added_by'] .'</div><div style="float: left; width: 25%; text-align: center;"><a href="content.php?a=approve_files&approve='. $f['id'] .'">'. $lang['approve'] .'</a> - <a href="content.php?a=edit_file&f='. $f['id'] .'">'. $lang['edit'] .'</a> - <a href="content.php?a=approve_files&delete='. $f['id'] .'" onclick="return confirm_delete();">'. $lang['delete'] .'</a></div>
        <div class="line"></div>';
	}
	echo '
        </form>
  	  </div>
  	</div>
  </div>
  <div style="clear: both;"></div>
  ';
	
	// Include footer
	template_footer();

}

// Broken Files: here we see a list of files that have bee reported broken by visitors
function template_broken_files() {
	global $settings, $lang, $broken_files;

	// Include header
	template_header();
	
	echo '
  <div class="admin_left">';
	template_content_menu();
	echo '
  </div>
  <div class="admin_right">
    <div class="content_box_2">
      <div class="content_box_2_header">
        '. $lang['broken_files'] .'
      </div>
      <div class="box_text">
        <form name="form" action="" method="post">
        <div style="float: left; width: 20%; text-align: center;"><b>'. $lang['title'] .'</b></div><div style="float: left; width: 35%; text-align: center;"><b>'. $lang['comment'] .'</b></div><div style="float: left; width: 15%; text-align: center;"><b>'. $lang['ip'] .'</b></div><div style="float: left; width: 15%; text-align: center;"><b>'. $lang['date_added'] .'</b></div><div style="float: left; width: 10%; text-align: center;">&nbsp;</div><div style="float: left; width: 5%; text-align: center;"><input type="checkbox" name="all" onclick="check_all();" /></div>
        <div class="line"></div>';
	foreach ($broken_files as $file) {
		echo '
        <div style="float: left; width: 20%; text-align: left;"><a href="'. fileurl($file['file_id'], $file['file_title']) .'" target="_blank">'. $file['file_title'] .'</a></div><div style="float: left; width: 35%; text-align: left;">'. $file['comment'] .'&nbsp;</div><div style="float: left; width: 15%; text-align: center;">'. $file['ip'] .'</div><div style="float: left; width: 15%; text-align: center;">'. $file['date'] .'</div><div style="float: left; width: 10%; text-align: center;"><a href="content.php?a=edit_file&f='. $file['file_id'] .'">'. $lang['edit'] .'</a></div><div style="float: left; width: 5%; text-align: center;"><input type="checkbox" name="broken_id['. $file['id'] .']" value="ok" /></div>
        <div class="line"></div>';
	}
	echo '
		<div align="right">
			  <select name="broken_files_action">
				<option value="delete_report">'. $lang['delete_report'] .'</option>
				<option value="delete_file">'. $lang['delete_file'] .'</option>
			  </select> 
			<input type="submit" name="submit_broken_files" value="'. $lang['go'] .'" />
		</div>
        </form>
  	  </div>
  	</div>
  </div>
  <div style="clear: both;"></div>
  ';
	
	// Include footer
	template_footer();

}

// Approve Comments: sometimes we want to approve new comments
function template_approve_comments() {
	global $settings, $lang, $comments;

	// Include header
	template_header();
	
	echo '
  <div class="admin_left">';
	template_content_menu();
	echo '
  </div>
  <div class="admin_right">
    <div class="content_box_2">
      <div class="content_box_2_header">
	    '. $lang['approve_comments'] .'
      </div>
      <div class="box_text">
        <form name="form" action="" method="post">
        <div style="float: left; width: 30%; text-align: center;"><b>'. $lang['comment'] .'</b></div><div style="float: left; width: 20%; text-align: center;"><b>'. $lang['poster'] .'</b></div><div style="float: left; width: 15%; text-align: center;"><b>'. $lang['file_file'] .'</b></div><div style="float: left; width: 15%; text-align: center;"><b>'. $lang['ip'] .'</b></div><div style="float: left; width: 15%; text-align: center;"><b>'. $lang['date_added'] .'</b></div><div style="float: left; width: 5%; text-align: center;"><input type="checkbox" name="all" onclick="check_all();" /></div>
        <div class="line"></div>';
	foreach ($comments as $comment) {
		echo '
        <div style="float: left; width: 30%; text-align: left;">'. $comment['comment'] .'</div><div style="float: left; width: 20%; text-align: center;">'. $comment['poster_username'] .'&nbsp;</div><div style="float: left; width: 15%; text-align: center;"><a href="'. fileurl($comment['file_id'], $comment['file_title']) .'" target="_blank">'. $comment['file_title'] .'</a>&nbsp;</div><div style="float: left; width: 15%; text-align: center;">'. $comment['ip'] .'</div><div style="float: left; width: 15%; text-align: center;">'. $comment['date'] .'</div><div style="float: left; width: 5%; text-align: center;"><input type="checkbox" name="comment_id['. $comment['id'] .']" value="ok" /></div>
        <div class="line"></div>';
	}
	echo '
		<div align="right">
			  <select name="comments_action">
			    <option value="approve">'. $lang['approve'] .'</option>
				<option value="delete_comment">'. $lang['delete'] .'</option>
			  </select> 
			<input type="submit" name="submit_comments" value="'. $lang['go'] .'" />
		</div>
        </form>
  	  </div>
  	</div>
  </div>
  <div style="clear: both;"></div>
  ';
	
	// Include footer
	template_footer();

}

// Install Game Pack: this is where we install game packs for onArcade 2
function template_install_game_pack() {
	global $settings, $lang;

	// Include header
	template_header();
	
	echo '
  <div class="admin_left">';
	template_content_menu();
	echo '
  </div>
  <div class="admin_right">
    <div class="content_box_2">
      <div class="content_box_2_header">
        '. $lang['install_game_pack'] .'
      </div>
      <div class="box_text">
        <form action="" method="POST" name="form" ENCTYPE="multipart/form-data">
		  <div class="content_text_left" style="width: 15%;">'. $lang['sql_file'] .'</div>
		  <div class="content_text_right" style="width: 85%;"><input type="file" name="sql_file" /></div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 15%;">'. $lang['status'] .'</div>
		  <div class="content_text_right" style="width: 85%;">
		    <select name="file_status">
			  <option value="1">'. $lang['active'] .'</option>
			  <option value="2">'. $lang['inactive'] .'</option>
			  <option value="3">'. $lang['game_slave'] .'</option>
			</select>
		  </div>
		  <div style="clear: both;"></div>
		  <div align="center">
		    <input type="submit" name="submit_game_pack" value="'. $lang['install'] .'" />
		  </div>
		</form>
  	  </div>
  	</div>
  </div>
  <div style="clear: both;"></div>
  ';
	
	// Include footer
	template_footer();

}

// Game Feed: list of game feeds
function template_game_feed() {
	global $settings, $lang, $feed, $feed_error;

	// Include header
	template_header();
	
	echo '
  <div class="admin_left">';
	template_content_menu();
	echo '
  </div>
  <div class="admin_right">';
	if (strlen($feed_error)) {
		echo '
    <div class="error_box">
      '. $feed_error .'
    </div>';
	}
	echo '
    <div class="content_box_2">
      <div class="content_box_2_header">
        '. $lang['game_feed'] .'
      </div>
      <div class="box_text">
	    '. $lang['feed_welcome_message'] .'<br />';
	foreach ($feed as $f) {
		echo '
        <a href="content.php?a=game_feed&f='. $f['file'] .'">'. $f['title'] .'</a><br />';
	}
	echo '
  	  </div>
  	</div>
  </div>
  <div style="clear: both;"></div>
  ';
	
	// Include footer
	template_footer();

}

// Game Feed Games: list of games
function template_game_feed_games() {
	global $settings, $lang, $feed, $feed_name;

	// Include header
	template_header();
	
	echo '
  <div class="admin_left">';
	template_content_menu();
	echo '
  </div>
  <div class="admin_right">
    <div class="content_box_2">
      <div class="content_box_2_header">
        '. $lang['game_feed'] .'
      </div>
      <div class="box_text">';
	foreach ($feed as $f) {
		echo '
		<div class="content_text_left" style="width: 15%;">
          <img src="'. $f['image'] .'" title="'. $f['title'] .'" alt="'. $f['title'] .'" border="0" width="'. $settings['image_width'] .'" height="'. $settings['image_height'] .'" />
		</div>
		<div class="content_text_right" style="width: 85%;">
          <b>'. $f['title'] .'</b><br />
		  '. $f['description'] .'<br />
		  ('. $f['category'] .'; '. $f['file_type'] . $f['scores'] .')<br />
		  <a href="content.php?a=game_feed&f='. $feed_name .'&g='. $f['id'] .'" class="game_feed_link">'. $lang['install'] .'</a>
		</div>
		<div class="line"></div>';
	}
	echo '
  	  </div>
  	</div>
  </div>
  <div style="clear: both;"></div>
  ';
	
	// Include footer
	template_footer();

}

// Categories: here we see a list of categories
function template_categories() {
	global $settings, $lang, $categories;

	// Include header
	template_header();
	
	echo '
  <div class="admin_left">';
	template_content_menu();
	echo '
  </div>
  <div class="admin_right">
    <div class="content_box_2">
      <div class="content_box_2_header">
        '. $lang['categories'] .'
      </div>
      <div class="box_text">
        <form name="form" action="" method="post">
        <div style="float: left; width: 25%; text-align: center;"><b>'. $lang['title'] .'</b></div><div style="float: left; width: 15%; text-align: center;"><b>'. $lang['permissions'] .'</b></div><div style="float: left; width: 15%; text-align: center;"><b>'. $lang['status'].'</b></div><div style="float: left; width: 15%; text-align: center;"><b>'. $lang['category_order'] .'</b></div><div style="float: left; width: 15%; text-align: center;"><b>'. $lang['parent_category'] .'</b></div><div style="float: left; width: 10%; text-align: center;">&nbsp;</div><div style="float: left; width: 5%; text-align: center;"><input type="checkbox" name="all" onclick="check_all();" /></div>
        <div class="line"></div>';
	foreach ($categories as $category) {
		echo '
        <div style="float: left; width: 25%; text-align: left;"><a href="'. categoryurl($category['id'], $category['title']) .'" target="_blank">'. $category['title'] .'</a></div><div style="float: left; width: 15%; text-align: center;">'. $category['permissions'] .'</div><div style="float: left; width: 15%; text-align: center;">'. $category['status'] .'</div><div style="float: left; width: 15%; text-align: center;"><input type="text" name="cat_order['. $category['id'] .']" size="1" value="'. $category['order'] .'" style="text-align: center;" /></div><div style="float: left; width: 15%; text-align: center;">'. $category['parent_category'] .'</div><div style="float: left; width: 10%; text-align: center;"><a href="content.php?a=edit_category&c='. $category['id'] .'">'. $lang['edit'] .'</a></div><div style="float: left; width: 5%; text-align: center;"><input type="checkbox" name="category_id['. $category['id'] .']" value="ok" /></div>
        <div class="line"></div>';
	}
	echo '
		<div align="right">
			  <select name="categories_action">
				<option value="delete">'. $lang['delete'] .'</option>
				<option value="active">'. $lang['mark_active'] .'</option>
				<option value="inactive">'. $lang['mark_inactive'] .'</option>
			  </select> 
			<input type="submit" name="submit_categories" value="'. $lang['go'] .'" />
		</div>
        </form>
  	  </div>
  	</div>
  </div>
  <div style="clear: both;"></div>
  ';
	
	// Include footer
	template_footer();

}

// Add Category: here you can add categories
function template_add_category() {
	global $settings, $lang, $add_category;

	// Include header
	template_header();
	
	echo '
  <div class="admin_left">';
	template_content_menu();
	echo '
  </div>
  <div class="admin_right">';
	if (strlen($add_category['error'])) {
		echo '
    <div class="error_box">
      '. $add_category['error'] .'
    </div>';
	}
	echo '
    <div class="content_box_2">
      <div class="content_box_2_header">
        '. $lang['add_category'] .'
      </div>
      <div class="box_text">
        <form action="" method="POST" name="form" onsubmit="return verify_add()">
		  <div class="content_text_left" style="width: 20%;">'. $lang['title'] .'</div>
		  <div class="content_text_right" style="width: 80%;"><input type="text" name="title" size="35" /></div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">'. $lang['description'] .'</div>
		  <div class="content_text_right" style="width: 80%;"><textarea name="description" rows="1" cols="35"></textarea></div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">'. $lang['keywords'] .'</div>
		  <div class="content_text_right" style="width: 80%;"><textarea name="keywords" rows="1" cols="35"></textarea></div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">'. $lang['permissions'] .'</div>
		  <div class="content_text_right" style="width: 80%;">
		    <select name="permissions">
			  <option value="1">'. $lang['everybody'].'</option>
			  <option value="2">'. $lang['members_only'] .'</option>
			</select>
		  </div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">'. $lang['status'].'</div>
		  <div class="content_text_right" style="width: 80%;">
		    <select name="category_status">
			  <option value="1">'. $lang['active'] .'</option>
			  <option value="0">'. $lang['inactive'] .'</option>
			</select>
		  </div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">'. $lang['category_order'] .'</div>
		  <div class="content_text_right" style="width: 80%;"><input type="text" name="order" size="1" value="0" /></div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">'. $lang['parent_category'].'</div>
		  <div class="content_text_right" style="width: 80%;">
		    '. $add_category['categories'] .'
		  </div>
		  <div style="clear: both;"></div>
		  <div align="center">
		    <input type="submit" name="submit_category" value="'. $lang['submit'] .'" />
		  </div>
		</form>
  	  </div>
  	</div>
  </div>
  <div style="clear: both;"></div>
  ';
	
	// Include footer
	template_footer();

}

// Edit Category: sometimes some weird people want to change details about categories
function template_edit_category() {
	global $settings, $lang, $edit_category;

	// Include header
	template_header();
	
	echo '
  <div class="admin_left">';
	template_content_menu();
	echo '
  </div>
  <div class="admin_right">
    <div class="content_box_2">
      <div class="content_box_2_header">
        '. $lang['edit_category'] .'
      </div>
      <div class="box_text">
        <form action="" method="POST" name="form" onsubmit="return verify_add()">
		  <div class="content_text_left" style="width: 20%;">'. $lang['title'] .'</div>
		  <div class="content_text_right" style="width: 80%;"><input type="text" name="title" size="35" value="'. $edit_category['title'] .'" /></div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">'. $lang['description'] .'</div>
		  <div class="content_text_right" style="width: 80%;"><textarea name="description" rows="1" cols="35">'. $edit_category['description'] .'</textarea></div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">'. $lang['keywords'] .'</div>
		  <div class="content_text_right" style="width: 80%;"><textarea name="keywords" rows="1" cols="35">'. $edit_category['keywords'] .'</textarea></div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">'. $lang['permissions'] .'</div>
		  <div class="content_text_right" style="width: 80%;">
		    <select name="permissions">
			  <option value="1" '. ($edit_category['permissions'] == '1' ? 'selected' : '') .'>'. $lang['everybody'].'</option>
			  <option value="2" '. ($edit_category['permissions'] == '2' ? 'selected' : '') .'>'. $lang['members_only'] .'</option>
			</select>
		  </div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">'. $lang['status'] .'</div>
		  <div class="content_text_right" style="width: 80%;">
		    <select name="category_status">
			  <option value="1" '. ($edit_category['status'] == '1' ? 'selected' : '') .'>'. $lang['active'] .'</option>
			  <option value="0" '. ($edit_category['status'] == '0' ? 'selected' : '') .'>'. $lang['inactive'] .'</option>
			</select>
		  </div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">'. $lang['category_order'] .'</div>
		  <div class="content_text_right" style="width: 80%;"><input type="text" name="order" size="1" value="'. $edit_category['order'] .'" /></div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">'. $lang['parent_category'] .'</div>
		  <div class="content_text_right" style="width: 80%;">
		    '. $edit_category['categories'] .'
		  </div>
		  <div style="clear: both;"></div>
		  <div align="center">
		    <input type="submit" name="submit_edit_category" value="'. $lang['submit'] .'" />
		  </div>
		</form>
  	  </div>
  	</div>
  </div>
  <div style="clear: both;"></div>
  ';
	
	// Include footer
	template_footer();

}

// News: news need some editing
function template_news() {
	global $settings, $lang, $news;

	// Include header
	template_header();
	
	echo '
  <div class="admin_left">';
	template_content_menu();
	echo '
  </div>
  <div class="admin_right">
    <div class="content_box_2">
      <div class="content_box_2_header">
        '. $lang['news'] .'
      </div>
      <div class="box_text">
        <form name="form" action="" method="post">
        <div style="float: left; width: 25%; text-align: center;"><b>'. $lang['title'] .'</b></div><div style="float: left; width: 25%; text-align: center;"><b>'. $lang['author'] .'</b></div><div style="float: left; width: 15%; text-align: center;"><b>'. $lang['date_added'] .'</b></div><div style="float: left; width: 15%; text-align: center;"><b>'. $lang['status'] .'</b></div><div style="float: left; width: 15%; text-align: center;">&nbsp;</div><div style="float: left; width: 5%; text-align: center;"><input type="checkbox" name="all" onclick="check_all();" /></div>
        <div class="line"></div>';
	foreach ($news as $n) {
		echo '
        <div style="float: left; width: 25%; text-align: left;">'. $n['title'] .'</div><div style="float: left; width: 25%; text-align: center;"><a href="'. profileurl($n['author_id'], $n['author']) .'" target="_blank">'. $n['author'] .'</a></div><div style="float: left; width: 15%; text-align: center;">'. $n['date'] .'&nbsp;</div><div style="float: left; width: 15%; text-align: center;">'. $n['status'] .'</div><div style="float: left; width: 15%; text-align: center;"><a href="content.php?a=edit_news&n='. $n['id'] .'">'. $lang['edit'] .'</a></div><div style="float: left; width: 5%; text-align: center;"><input type="checkbox" name="news_id['. $n['id'] .']" value="ok" /></div>
        <div class="line"></div>';
	}
	echo '
		<div align="right">
		  <select name="news_action">
			<option value="delete">'. $lang['delete'] .'</option>
			<option value="active">'. $lang['mark_active'] .'</option>
			<option value="inactive">'. $lang['mark_inactive'] .'</option>
		  </select> 
		  <input type="submit" name="submit_news" value="'. $lang['go'] .'" />
		</div>
        </form>
  	  </div>
  	</div>
  </div>
  <div style="clear: both;"></div>
  ';
	
	// Include footer
	template_footer();

}

// Add News: here you can add news
function template_add_news() {
	global $settings, $lang;

	// Include header
	template_header();
	
	echo '
  <div class="admin_left">';
	template_content_menu();
	echo '
  </div>
  <div class="admin_right">
    <div class="content_box_2">
      <div class="content_box_2_header">
        '. $lang['add_news'] .'
      </div>
      <div class="box_text">
        <form action="" method="POST" name="form" onsubmit="return verify_add()">
		  <div class="content_text_left" style="width: 20%;">'. $lang['title'] .'</div>
		  <div class="content_text_right" style="width: 80%;"><input type="text" name="title" size="30" /></div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">'. $lang['news_message'] .'</div>
		  <div class="content_text_right" style="width: 80%;"><textarea name="message" rows="10" cols="50"></textarea></div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">'. $lang['status'] .'</div>
		  <div class="content_text_right" style="width: 80%;">
		    <select name="news_status">
			  <option value="1">'. $lang['active'] .'</option>
			  <option value="0">'. $lang['inactive'] .'</option>
			</select>
		  </div>
		  <div style="clear: both;"></div>
		  <div align="center">
		    <input type="submit" name="submit_news" value="'. $lang['submit'] .'" />
		  </div>
		</form>
  	  </div>
  	</div>
  </div>
  <div style="clear: both;"></div>
  ';
	
	// Include footer
	template_footer();

}

// Edit News: made a stupid mistake when posting news? well, fix it here
function template_edit_news() {
	global $settings, $lang, $edit_news;

	// Include header
	template_header();
	
	echo '
  <div class="admin_left">';
	template_content_menu();
	echo '
  </div>
  <div class="admin_right">
    <div class="content_box_2">
      <div class="content_box_2_header">
        '. $lang['edit_news'] .'
      </div>
      <div class="box_text">
        <form action="" method="POST" name="form" onsubmit="return verify_add()">
		  <div class="content_text_left" style="width: 20%;">'. $lang['title'] .'</div>
		  <div class="content_text_right" style="width: 80%;"><input type="text" name="title" size="30" value="'. $edit_news['title'] .'" /></div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">'. $lang['edit_news'] .'</div>
		  <div class="content_text_right" style="width: 80%;"><textarea name="message" rows="10" cols="50">'. $edit_news['message'] .'</textarea></div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">'. $lang['status'] .'</div>
		  <div class="content_text_right" style="width: 80%;">
		    <select name="news_status">
			  <option value="1" '. ($edit_news['status'] == '1' ? 'selected' : '') .'>'. $lang['active'] .'</option>
			  <option value="0" '. ($edit_news['status'] == '0' ? 'selected' : '') .'>'. $lang['inactive'] .'</option>
			</select>
		  </div>
		  <div style="clear: both;"></div>
		  <div align="center">
		    <input type="submit" name="submit_news" value="'. $lang['submit'] .'" />
		  </div>
		</form>
  	  </div>
  	</div>
  </div>
  <div style="clear: both;"></div>
  ';
	
	// Include footer
	template_footer();

}

// Links: links are very important to arcades
function template_links() {
	global $settings, $lang, $links;

	// Include header
	template_header();
	
	echo '
  <div class="admin_left">';
	template_content_menu();
	echo '
  </div>
  <div class="admin_right">
    <div class="content_box_2">
      <div class="content_box_2_header">
        '. $lang['links'] .'
      </div>
      <div class="box_text">
        <form name="form" action="" method="post">
        <div style="float: left; width: 15%; text-align: center;"><b>'. $lang['url'] .'</b></div><div style="float: left; width: 40%; text-align: center;"><b>'. $lang['description'] .'</b></div><div style="float: left; width: 10%; text-align: center;"><b>'. $lang['hits_in'] .'</b></div><div style="float: left; width: 10%; text-align: center;"><b>'. $lang['hits_out'] .'</b></div><div style="float: left; width: 10%; text-align: center;"><b>'. $lang['status'] .'</b></div><div style="float: left; width: 10%; text-align: center;">&nbsp;</div><div style="float: left; width: 5%; text-align: center;"><input type="checkbox" name="all" onclick="check_all();" /></div>
        <div class="line"></div>';
	foreach ($links as $link) {
		echo '
        <div style="float: left; width: 15%; text-align: left;"><a href="'. $link['url'] .'" target="_blank">'. $link['title'] .'</a></div><div style="float: left; width: 40%; text-align: left;">'. $link['description'] .'</div><div style="float: left; width: 10%; text-align: center;">'. $link['hits_in'] .'</div><div style="float: left; width: 10%; text-align: center;">'. $link['hits_out'] .'</div><div style="float: left; width: 10%; text-align: center;">'. $link['status'] .'</div><div style="float: left; width: 10%; text-align: center;"><a href="content.php?a=edit_link&l='. $link['id'] .'">'. $lang['edit'] .'</a></div><div style="float: left; width: 5%; text-align: center;"><input type="checkbox" name="link_id['. $link['id'] .']" value="ok" /></div>
        <div class="line"></div>';
	}
	echo '
		<div align="right">
		  <select name="links_action">
			<option value="delete">'. $lang['delete'] .'</option>
			<option value="active">'. $lang['mark_active'] .'</option>
			<option value="inactive">'. $lang['mark_inactive'].'</option>
		  </select> 
		  <input type="submit" name="submit_links" value="'. $lang['go'] .'" />
		</div>
        </form>
  	  </div>
  	</div>
  </div>
  <div style="clear: both;"></div>
  ';
	
	// Include footer
	template_footer();

}

// Approve Links: and here we can approve new links
function template_approve_links() {
	global $settings, $lang, $links;

	// Include header
	template_header();
	
	echo '
  <div class="admin_left">';
	template_content_menu();
	echo '
  </div>
  <div class="admin_right">
    <div class="content_box_2">
      <div class="content_box_2_header">
        '. $lang['approve_links'] .'
      </div>
      <div class="box_text">
        <div style="float: left; width: 15%; text-align: center;"><b>'. $lang['url'] .'</b></div><div style="float: left; width: 35%; text-align: center;"><b>'. $lang['description'] .'</b></div><div style="float: left; width: 20%; text-align: center;"><b>'. $lang['email'] .'</b></div><div style="float: left; width: 15%; text-align: center;"><b>'. $lang['ip'] .'</b></div><div style="float: left; width: 15%; text-align: center;">&nbsp;</div>
        <div class="line"></div>';
	foreach ($links as $link) {
		echo '
        <div style="float: left; width: 15%; text-align: left;"><a href="'. $link['url'] .'" target="_blank">'. $link['title'] .'</a></div><div style="float: left; width: 35%; text-align: left;">'. $link['description'] .'</div><div style="float: left; width: 20%; text-align: center;">'. $link['email'] .'&nbsp;</div><div style="float: left; width: 15%; text-align: center;">'. $link['ip'] .'&nbsp;</div><div style="float: left; width: 15%; text-align: center;"><a href="content.php?a=approve_links&approve_link='. $link['id'] .'">'. $lang['approve'] .'</a> - <a href="content.php?a=approve_links&delete_link='. $link['id'] .'" onclick="return confirm_delete();">'. $lang['delete'] .'</a></div>
        <div class="line"></div>';
	}
	echo '
  	  </div>
  	</div>
  </div>
  <div style="clear: both;"></div>
  ';
	
	// Include footer
	template_footer();

}

// Add Link: here you can add links
function template_add_link() {
	global $settings, $lang;

	// Include header
	template_header();
	
	echo '
  <div class="admin_left">';
	template_content_menu();
	echo '
  </div>
  <div class="admin_right">
    <div class="content_box_2">
      <div class="content_box_2_header">
        '. $lang['add_link'] .'
      </div>
      <div class="box_text">
        <form action="" method="POST" name="form" onsubmit="return verify_add()">
		  <div class="content_text_left" style="width: 20%;">'. $lang['title'] .'</div>
		  <div class="content_text_right" style="width: 80%;"><input type="text" name="title" size="30" /></div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">'. $lang['url'] .'</div>
		  <div class="content_text_right" style="width: 80%;"><input type="text" name="url" size="35" /></div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">'. $lang['description'] .'</div>
		  <div class="content_text_right" style="width: 80%;"><textarea name="description" rows="1" cols="35"></textarea></div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">'. $lang['email'] .'</div>
		  <div class="content_text_right" style="width: 80%;"><input type="text" name="email" size="35" /></div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">'. $lang['status'] .'</div>
		  <div class="content_text_right" style="width: 80%;">
		    <select name="link_status">
			  <option value="1">'. $lang['active'] .'</option>
			  <option value="2">'. $lang['inactive'].'</option>
			</select>
		  </div>
		  <div style="clear: both;"></div>
		  <div align="center">
		    <input type="submit" name="submit_link" value="'. $lang['submit'] .'" />
		  </div>
		</form>
  	  </div>
  	</div>
  </div>
  <div style="clear: both;"></div>
  ';
	
	// Include footer
	template_footer();

}

// Edit Link: some links just need editing
function template_edit_link() {
	global $settings, $lang, $edit_link;

	// Include header
	template_header();
	
	echo '
  <div class="admin_left">';
	template_content_menu();
	echo '
  </div>
  <div class="admin_right">
    <div class="content_box_2">
      <div class="content_box_2_header">
        '. $lang['edit_link'] .'
      </div>
      <div class="box_text">
        <form action="" method="POST" name="form" onsubmit="return verify_add()">
		  <div class="content_text_left" style="width: 20%;">'. $lang['title'] .'</div>
		  <div class="content_text_right" style="width: 80%;"><input type="text" name="title" size="30" value="'. $edit_link['title'] .'" /></div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">'. $lang['url'] .'</div>
		  <div class="content_text_right" style="width: 80%;"><input type="text" name="url" size="35" value="'. $edit_link['url'] .'" /></div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">'. $lang['description'] .'</div>
		  <div class="content_text_right" style="width: 80%;"><textarea name="description" rows="1" cols="35">'. $edit_link['description'] .'</textarea></div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">'. $lang['email'] .'</div>
		  <div class="content_text_right" style="width: 80%;"><input type="text" name="email" size="35" value="'. $edit_link['email'] .'" /></div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">'. $lang['status'] .'</div>
		  <div class="content_text_right" style="width: 80%;">
		    <select name="link_status">
			  <option value="1" '. ($edit_link['status'] == '1' ? 'selected' : '') .'>'. $lang['active'] .'</option>
			  <option value="2" '. ($edit_link['status'] == '2' ? 'selected' : '') .'>'. $lang['inactive'].'</option>
			</select>
		  </div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">'. $lang['hits_in'] .'</div>
		  <div class="content_text_right" style="width: 80%;">'. $edit_link['hits_in'] .'</div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">'. $lang['hits_out'] .'</div>
		  <div class="content_text_right" style="width: 80%;">'. $edit_link['hits_out'] .'</div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">'. $lang['ip'] .'</div>
		  <div class="content_text_right" style="width: 80%;">'. $edit_link['ip'] .'</div>
		  <div style="clear: both;"></div>
		  <div align="center">
		    <input type="submit" name="submit_link" value="'. $lang['submit'] .'" />
		  </div>
		</form>
  	  </div>
  	</div>
  </div>
  <div style="clear: both;"></div>
  ';
	
	// Include footer
	template_footer();

}

// Ads: here we see all ads
function template_ads() {
	global $settings, $lang, $ads;

	// Include header
	template_header();
	
	echo '
  <div class="admin_left">';
	template_content_menu();
	echo '
  </div>
  <div class="admin_right">
    <div class="content_box_2">
      <div class="content_box_2_header">
        '. $lang['ads'] .'
      </div>
      <div class="box_text">
        <form name="form" action="" method="post">
        <div style="float: left; width: 15%; text-align: center;"><b>'. $lang['ad_id'] .'</b></div><div style="float: left; width: 20%; text-align: center;"><b>'. $lang['ad_zone'] .'</b></div><div style="float: left; width: 25%; text-align: center;"><b>'. $lang['date_added'] .'</b></div><div style="float: left; width: 20%; text-align: center;"><b>'. $lang['status'] .'</b></div><div style="float: left; width: 15%; text-align: center;">&nbsp;</div><div style="float: left; width: 5%; text-align: center;"><input type="checkbox" name="all" onclick="check_all();" /></div>
        <div class="line"></div>';
	foreach ($ads as $ad) {
		echo '
        <div style="float: left; width: 15%; text-align: center;">'. $ad['id'] .'</div><div style="float: left; width: 20%; text-align: center;">'. $ad['zone'] .'</div><div style="float: left; width: 25%; text-align: center;">'. $ad['date'] .'</div><div style="float: left; width: 20%; text-align: center;">'. $ad['status'] .'</div><div style="float: left; width: 15%; text-align: center;"><a href="content.php?a=edit_ad&ad='. $ad['id'] .'">'. $lang['edit'].'</a></div><div style="float: left; width: 5%; text-align: center;"><input type="checkbox" name="ad_id['. $ad['id'] .']" value="ok" /></div>
        <div class="line"></div>';
	}
	echo '
		<div align="right">
		  <select name="ads_action">
			<option value="delete">'. $lang['delete'].'</option>
			<option value="active">'. $lang['mark_active'] .'</option>
			<option value="inactive">'. $lang['mark_inactive'] .'</option>
		  </select> 
		  <input type="submit" name="submit_ads" value="'. $lang['go'] .'" />
		</div>
        </form>
  	  </div>
  	</div>
  </div>
  <div style="clear: both;"></div>
  ';
	
	// Include footer
	template_footer();

}

// Add Ad: here you can add ads
function template_add_ad() {
	global $settings, $lang;

	// Include header
	template_header();
	
	echo '
  <div class="admin_left">';
	template_content_menu();
	echo '
  </div>
  <div class="admin_right">
    <div class="content_box_2">
      <div class="content_box_2_header">
        '. $lang['add_ad'] .'
      </div>
      <div class="box_text">
        <form action="" method="POST" name="form">
          <div class="content_text_left" style="width: 20%;">'. $lang['ad_type'] .'</div>
		  <div class="content_text_right" style="width: 80%;">
		    <select name="ad_type" onChange="ad_type_change()">
			  <option value="code">'. $lang['type_code'] .'</option>
			  <option value="banner">'. $lang['type_banner'] .'</option>
			</select>
		  </div>
		  <div style="clear: both;"></div>
          <span id="ad_code_field">
		    <div class="content_text_left" style="width: 20%;">'. $lang['ad_code'] .'</div>
		    <div class="content_text_right" style="width: 80%;"><textarea name="ad_code" rows="10" cols="50"></textarea></div>
		    <div style="clear: both;"></div>
		  </span>
		  <span id="banner_field" style="display: none;">
		    <div class="content_text_left" style="width: 20%;">'. $lang['banner'] .'</div>
		    <div class="content_text_right" style="width: 80%;"><input type="text" name="banner" size="50" /></div>
		    <div style="clear: both;"></div>
		    <div class="content_text_left" style="width: 20%;">'. $lang['url'].'</div>
		    <div class="content_text_right" style="width: 80%;"><input type="text" name="link" size="50" /></div>
		    <div style="clear: both;"></div>
		  </span>
		  <div class="content_text_left" style="width: 20%;">'. $lang['ad_zone'] .'</div>
		  <div class="content_text_right" style="width: 80%;">
		    <select name="ad_zone">
			  <option value="1">'. $lang['header_zone'] .'</option>
			  <option value="2">'. $lang['footer_zone'] .'</option>
			  <option value="3">'. $lang['file_zone'] .'</option>
			  <option value="4">'. $lang['before_file_zone'] .'</option>
			</select>
		  </div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">'. $lang['status'] .'</div>
		  <div class="content_text_right" style="width: 80%;">
		    <select name="ad_status">
			  <option value="1">'. $lang['active'] .'</option>
			  <option value="0">'. $lang['inactive'] .'</option>
			</select>
		  </div>
		  <div style="clear: both;"></div>
		  <div align="center">
		    <input type="submit" name="submit_ad" value="'. $lang['submit'].'" />
		  </div>
		</form>
  	  </div>
  	</div>
  </div>
  <div style="clear: both;"></div>
  ';
	
	// Include footer
	template_footer();

}

// Edit Ad: here you can edit ads
function template_edit_ad() {
	global $settings, $lang, $edit_ad;

	// Include header
	template_header();
	
	echo '
  <div class="admin_left">';
	template_content_menu();
	echo '
  </div>
  <div class="admin_right">
    <div class="content_box_2">
      <div class="content_box_2_header">
        '. $lang['edit_ad'] .'
      </div>
      <div class="box_text">
        <form action="" method="POST" name="form">
		  <div class="content_text_left" style="width: 20%;">'. $lang['ad_code'] .'</div>
		  <div class="content_text_right" style="width: 80%;"><textarea name="ad_code" rows="10" cols="50">'. $edit_ad['code'] .'</textarea></div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">'. $lang['ad_zone'] .'</div>
		  <div class="content_text_right" style="width: 80%;">
		    <select name="ad_zone">
			  <option value="1" '. ($edit_ad['ad_zone'] == '1' ? 'selected' : '') .'>'. $lang['header_zone'] .'</option>
			  <option value="2" '. ($edit_ad['ad_zone'] == '2' ? 'selected' : '') .'>'. $lang['footer_zone'] .'</option>
			  <option value="3" '. ($edit_ad['ad_zone'] == '3' ? 'selected' : '') .'>'. $lang['file_zone'] .'</option>
			  <option value="4" '. ($edit_ad['ad_zone'] == '4' ? 'selected' : '') .'>'. $lang['before_file_zone'] .'</option>
			</select>
		  </div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">'. $lang['status'] .'</div>
		  <div class="content_text_right" style="width: 80%;">
		    <select name="ad_status">
			  <option value="1" '. ($edit_ad['ad_status'] == '1' ? 'selected' : '') .'>'. $lang['active'] .'</option>
			  <option value="0" '. ($edit_ad['ad_status'] == '0' ? 'selected' : '') .'>'. $lang['inactive'] .'</option>
			</select>
		  </div>
		  <div style="clear: both;"></div>
		  <div align="center">
		    <input type="submit" name="submit_ad" value="'. $lang['submit'] .'" />
		  </div>
		</form>
  	  </div>
  	</div>
  </div>
  <div style="clear: both;"></div>
  ';
	
	// Include footer
	template_footer();

}

// Custom Pages: custom pages can be managed here
function template_custom_pages() {
	global $settings, $lang, $custom_pages;

	// Include header
	template_header();
	
	echo '
  <div class="admin_left">';
	template_content_menu();
	echo '
  </div>
  <div class="admin_right">
    <div class="content_box_2">
      <div class="content_box_2_header">
        '. $lang['custom_pages'] .'
      </div>
      <div class="box_text">
        <form name="form" action="" method="post">
        <div style="float: left; width: 25%; text-align: center;"><b>'. $lang['title'] .'</b></div><div style="float: left; width: 40%; text-align: center;"><b>'. $lang['description'] .'</b></div><div style="float: left; width: 15%; text-align: center;"><b>'. $lang['status'] .'</b></div><div style="float: left; width: 15%; text-align: center;">&nbsp;</div><div style="float: left; width: 5%; text-align: center;"><input type="checkbox" name="all" onclick="check_all();" /></div>
        <div class="line"></div>';
	foreach ($custom_pages as $page) {
		echo '
        <div style="float: left; width: 25%; text-align: left;">'. $page['title'] .'</div><div style="float: left; width: 40%; text-align: left;">'. $page['description'] .'</div><div style="float: left; width: 15%; text-align: center;">'. $page['status'] .'</div><div style="float: left; width: 15%; text-align: center;"><a href="content.php?a=edit_page&page='. $page['id'] .'">'. $lang['edit'] .'</a></div><div style="float: left; width: 5%; text-align: center;"><input type="checkbox" name="page_id['. $page['id'] .']" value="ok" /></div>
        <div class="line"></div>';
	}
	echo '
		<div align="right">
		  <select name="pages_action">
			<option value="delete">'. $lang['delete'] .'</option>
			<option value="active">'. $lang['mark_active'] .'</option>
			<option value="inactive">'. $lang['mark_inactive'] .'</option>
		  </select> 
		  <input type="submit" name="submit_pages" value="'. $lang['go'] .'" />
		</div>
        </form>
  	  </div>
  	</div>
  </div>
  <div style="clear: both;"></div>
  ';
	
	// Include footer
	template_footer();

}

// Add Custom Page: here you can add custom page
function template_add_custom_page() {
	global $settings, $lang;

	// Include header
	template_header();
	
	echo '
  <div class="admin_left">';
	template_content_menu();
	echo '
  </div>
  <div class="admin_right">
    <div class="content_box_2">
      <div class="content_box_2_header">
        '. $lang['add_custom_page'] .'
      </div>
      <div class="box_text">
        <form action="" method="POST" name="form" onsubmit="return verify_add()">
		  <div class="content_text_left" style="width: 20%;">'. $lang['title'] .'</div>
		  <div class="content_text_right" style="width: 80%;"><input type="text" name="title" size="30" /></div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">'. $lang['description'] .'</div>
		  <div class="content_text_right" style="width: 80%;"><input type="text" name="description" size="35" /></div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">'. $lang['keywords'] .'</div>
		  <div class="content_text_right" style="width: 80%;"><input type="text" name="keywords" size="35" /></div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">'. $lang['page_content'] .'</div>
		  <div class="content_text_right" style="width: 80%;"><textarea name="content" rows="20" cols="80"></textarea></div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">'. $lang['status'] .'</div>
		  <div class="content_text_right" style="width: 80%;">
		    <select name="page_status">
			  <option value="1">'. $lang['active'] .'</option>
			  <option value="0">'. $lang['inactive'] .'</option>
			</select>
		  </div>
		  <div style="clear: both;"></div>
		  <div align="center">
		    <input type="submit" name="submit_page" value="'. $lang['submit'] .'" />
		  </div>
		</form>
  	  </div>
  	</div>
  </div>
  <div style="clear: both;"></div>
  ';
	
	// Include footer
	template_footer();

}

// Edit Custom Page: change content of custom pages here
function template_edit_custom_page() {
	global $settings, $lang, $edit_page;

	// Include header
	template_header();
	
	echo '
  <div class="admin_left">';
	template_content_menu();
	echo '
  </div>
  <div class="admin_right">
    <div class="content_box_2">
      <div class="content_box_2_header">
        '. $lang['edit_custom_page'] .'
      </div>
      <div class="box_text">
        <form action="" method="POST" name="form" onsubmit="return verify_add()">
          <div class="content_text_left" style="width: 20%;">'. $lang['url'] .'</div>
		  <div class="content_text_right" style="width: 80%;">
		    '. $edit_page['page_url'] .'
		  </div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">'. $lang['title'] .'</div>
		  <div class="content_text_right" style="width: 80%;"><input type="text" name="title" size="30" value="'. $edit_page['title'] .'" /></div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">'. $lang['description'] .'</div>
		  <div class="content_text_right" style="width: 80%;"><input type="text" name="description" size="35" value="'. $edit_page['description'] .'" /></div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">'. $lang['keywords'] .'</div>
		  <div class="content_text_right" style="width: 80%;"><input type="text" name="keywords" size="35" value="'. $edit_page['keywords'] .'" /></div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">'. $lang['page_content'] .'</div>
		  <div class="content_text_right" style="width: 80%;"><textarea name="content" rows="20" cols="80">'. $edit_page['content'] .'</textarea></div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">'. $lang['status'] .'</div>
		  <div class="content_text_right" style="width: 80%;">
		    <select name="page_status">
			  <option value="1" '. ($edit_page['status'] == '1' ? 'selected' : '') .'>'. $lang['active'] .'</option>
			  <option value="0" '. ($edit_page['status'] == '0' ? 'selected' : '') .'>'. $lang['inactive'] .'</option>
			</select>
		  </div>
		  <div style="clear: both;"></div>
		  <div align="center">
		    <input type="submit" name="submit_page" value="'. $lang['submit'] .'" />
		  </div>
		</form>
  	  </div>
  	</div>
  </div>
  <div style="clear: both;"></div>
  ';
	
	// Include footer
	template_footer();

}

// Members Menu: menu of members pages
function template_members_menu() {
	global $settings, $lang;
	
	echo '
    <div class="content_box">
      <div class="content_box_header">
        '. $lang['members'] .'
      </div>
      <div class="box_text">
  	    <a href="members.php">'. $lang['members'] .'</a><br />
  	    <a href="members.php?a=add_member">'. $lang['add_member'] .'</a><br />
  	    <a href="members.php?a=mass_email">'. $lang['mass_email'] .'</a><br />
  	    <a href="configurations.php?a=member_settings">'. $lang['member_settings'] .'</a><br />
  	  </div>
  	</div>';

}

// Members: members are displayed here
function template_members() {
	global $settings, $lang, $members, $navigation, $search_term;

	// Include header
	template_header();
	
	echo '
  <div class="admin_left">';
	template_members_menu();
	echo '
  </div>
  <div class="admin_right">
    <div class="content_box_2">
      <div class="content_box_2_header">
        '. $lang['members'] .'
      </div>
      <div class="box_text">
        <form name="form" action="" method="post">
        <div style="float: left; width: 20%; text-align: center;"><b>'. $lang['username'] .'</b></div><div style="float: left; width: 25%; text-align: center;"><b>'. $lang['joined'] .'</b></div><div style="float: left; width: 20%; text-align: center;"><b>'. $lang['user_group'] .'</b></div><div style="float: left; width: 15%; text-align: center;"><b>'. $lang['status'] .'</b></div><div style="float: left; width: 15%; text-align: center;">&nbsp;</div><div style="float: left; width: 5%; text-align: center;"><input type="checkbox" name="all" onclick="check_all();" /></div>
        <div class="line"></div>';
	foreach ($members as $member) {
		echo '
        <div style="float: left; width: 20%; text-align: left;"><a href="'. profileurl($member['id'], $member['username']) .'" target="_blank">'. $member['username'] .'</a></div><div style="float: left; width: 25%; text-align: center;">'. $member['joined'] .'</div><div style="float: left; width: 20%; text-align: center;">'. $member['group'] .'</div><div style="float: left; width: 15%; text-align: center;">'. $member['status'] .'</div><div style="float: left; width: 15%; text-align: center;"><a href="members.php?a=edit_member&m='. $member['id'] .'">'. $lang['edit'] .'</a> - <a href="members.php?a=member_comments&m='. $member['id'] .'">'. $lang['comments'] .'</a></div><div style="float: left; width: 5%; text-align: center;"><input type="checkbox" name="member_id['. $member['id'] .']" value="ok" /></div>
        <div class="line"></div>';
	}
	echo '
		<div align="right">
			  <select name="members_action">
				<option value="delete">'. $lang['delete'] .'</option>
				<option value="active">'. $lang['mark_active'] .'</option>
				<option value="ban">'. $lang['ban'] .'</option>
			  </select> 
			<input type="submit" name="submit_members" value="'. $lang['go'] .'" />
		</div>
		<div align="center">
		'. $navigation .'
		</div>
        <div align="right">
		  <input type="text" name="t" size="20" value="'. $search_term .'" /> <input type="submit" name="submit_search" value="'. $lang['search'] .'" />
		</div>
        '. $lang['delete_members_days'] .' <input type="submit" name="submit_remove" value="'. $lang['submit'] .'" />
        </form>
   	  </div>
  	</div>
  </div>
  <div style="clear: both;"></div>
  ';
	
	// Include footer
	template_footer();

}

// Add Member: here we can add new members
function template_add_member() {
	global $settings, $lang, $add_member;

	// Include header
	template_header();
	
	echo '
  <div class="admin_left">';
	template_members_menu();
	echo '
  </div>
  <div class="admin_right">';
	if (strlen($add_member['error'])) {
		echo '
    <div class="error_box">
      '. $add_member['error'] .'
    </div>';
	}
	echo '
    <div class="content_box_2">
      <div class="content_box_2_header">
        '. $lang['add_member'] .'
      </div>
      <div class="box_text">
        <form action="" method="POST" name="form">
		  <div class="content_text_left" style="width: 20%;">'. $lang['username'] .'</div>
		  <div class="content_text_right" style="width: 80%;"><input type="text" name="username" size="30" maxlength="25" value="'. $add_member['username'] .'" /></div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">'. $lang['password'] .'</div>
		  <div class="content_text_right" style="width: 80%;"><input type="password" name="password" size="30" /></div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">'. $lang['email'] .'</div>
		  <div class="content_text_right" style="width: 80%;"><input type="text" name="email" size="30" value="'. $add_member['email'] .'" /></div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">'. $lang['user_group'] .'</div>
		  <div class="content_text_right" style="width: 80%;">
		    <select name="user_group">
			  <option value="1">'. $lang['member'].'</option>
			  <option value="2">'. $lang['administrator'] .'</option>
			</select>
		  </div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">'. $lang['status'] .'</div>
		  <div class="content_text_right" style="width: 80%;">
		    <select name="user_status">
			  <option value="1">'. $lang['active'] .'</option>
			  <option value="2">'. $lang['banned'] .'</option>
			</select>
		  </div>
		  <div style="clear: both;"></div>
		  <div align="center">
		    <input type="submit" name="submit_member" value="'. $lang['submit'].'" />
		  </div>
		</form>
  	  </div>
  	</div>
  </div>
  <div style="clear: both;"></div>
  ';
	
	// Include footer
	template_footer();

}

// Edit Member: here we can edit already existing members
function template_edit_member() {
	global $settings, $lang, $edit_member, $edit_member_error;

	// Include header
	template_header();
	
	echo '
  <div class="admin_left">';
	template_members_menu();
	echo '
  </div>
  <div class="admin_right">';
	if (strlen($edit_member_error)) {
		echo '
    <div class="error_box">
      '. $edit_member_error .'
    </div>';
	}
	echo '
    <div class="content_box_2">
      <div class="content_box_2_header">
        '. $lang['edit_member'] .'
      </div>
      <div class="box_text">
        <form action="" method="POST" name="form">
		  <div class="content_text_left" style="width: 20%;">'. $lang['username'] .'</div>
		  <div class="content_text_right" style="width: 80%;"><input type="text" name="username" size="30" maxlength="25" value="'. $edit_member['username'] .'" /></div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">'. $lang['email'] .'</div>
		  <div class="content_text_right" style="width: 80%;"><input type="text" name="email" size="30" value="'. $edit_member['email'] .'" /></div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">'. $lang['status'] .'</div>
		  <div class="content_text_right" style="width: 80%;">
		    <select name="user_status">
			  <option value="1" '. ($edit_member['status'] == '1' ? 'selected' : '') .'>'. $lang['active'] .'</option>
			  <option value="2" '. ($edit_member['status'] == '2' ? 'selected' : '') .'>'. $lang['banned'] .'</option>
			</select>
		  </div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">'. $lang['user_group'] .'</div>
		  <div class="content_text_right" style="width: 80%;">
		    <select name="user_group">
			  <option value="1" '. ($edit_member['user_group'] == '1' ? 'selected' : '') .'>'. $lang['member'].'</option>
			  <option value="2" '. ($edit_member['user_group'] == '2' ? 'selected' : '') .'>'. $lang['administrator'] .'</option>
			</select>
		  </div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">'. $lang['avatar'] .'</div>
		  <div class="content_text_right" style="width: 80%;"><input type="text" name="avatar" size="30" value="'. $edit_member['avatar'] .'" /></div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">'. $lang['location'] .'</div>
		  <div class="content_text_right" style="width: 80%;"><input type="text" name="location" size="30" value="'. $edit_member['location'] .'" /></div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">'. $lang['website'] .'</div>
		  <div class="content_text_right" style="width: 80%;"><input type="text" name="website" size="30" value="'. $edit_member['website'] .'" /></div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">'. $lang['gender'] .'</div>
		  <div class="content_text_right" style="width: 80%;">
		    <select name="gender">
		      <option value="0" '. ($edit_member['gender'] == '0' ? 'selected' : '') .'>Unspecified</option>
			  <option value="1" '. ($edit_member['gender'] == '1' ? 'selected' : '') .'>Male</option>
			  <option value="2" '. ($edit_member['gender'] == '2' ? 'selected' : '') .'>Female</option>
		    </select>
		  </div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">'. $lang['msn'] .'</div>
		  <div class="content_text_right" style="width: 80%;"><input type="text" name="msn" size="30" value="'. $edit_member['msn'] .'" /></div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">'. $lang['aim'] .'</div>
		  <div class="content_text_right" style="width: 80%;"><input type="text" name="aim" size="30" value="'. $edit_member['aim'] .'" /></div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">'. $lang['skype'] .'</div>
		  <div class="content_text_right" style="width: 80%;"><input type="text" name="skype" size="30" value="'. $edit_member['skype'] .'" /></div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">'. $lang['yahoo'] .'</div>
		  <div class="content_text_right" style="width: 80%;"><input type="text" name="yahoo" size="30" value="'. $edit_member['yahoo'] .'" /></div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">'. $lang['icq'] .'</div>
		  <div class="content_text_right" style="width: 80%;"><input type="text" name="icq" size="30" value="'. $edit_member['icq'] .'" /></div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">'. $lang['google_talk'] .'</div>
		  <div class="content_text_right" style="width: 80%;"><input type="text" name="google_talk" size="30" value="'. $edit_member['google_talk'] .'" /></div>
		  <div style="clear: both;"></div>
		  <div align="center">
		    <input type="submit" name="submit_member" value="'. $lang['submit'] .'" />
		  </div>
		</form>
  	  </div>
  	</div>
  </div>
  <div style="clear: both;"></div>
  ';
	
	// Include footer
	template_footer();

}

// File Comments: these are comments added to file
function template_member_comments() {
	global $settings, $lang, $comments;

	// Include header
	template_header();
	
	echo '
  <div class="admin_left">';
	template_members_menu();
	echo '
  </div>
  <div class="admin_right">
    <div class="content_box_2">
      <div class="content_box_2_header">
	    '. $lang['comments'] .'
      </div>
      <div class="box_text">
        <form name="form" action="" method="post">
        <div style="float: left; width: 35%; text-align: center;"><b>'. $lang['comment'] .'</b></div><div style="float: left; width: 15%; text-align: center;"><b>'. $lang['file_file'] .'</b></div><div style="float: left; width: 15%; text-align: center;"><b>'. $lang['ip'] .'</b></div><div style="float: left; width: 15%; text-align: center;"><b>'. $lang['date_added'] .'</b></div><div style="float: left; width: 15%; text-align: center;"><b>'. $lang['status'] .'</b></div><div style="float: left; width: 5%; text-align: center;"><input type="checkbox" name="all" onclick="check_all();" /></div>
        <div class="line"></div>';
	foreach ($comments as $comment) {
		echo '
        <div style="float: left; width: 35%; text-align: left;">'. $comment['comment'] .'</div><div style="float: left; width: 15%; text-align: center;"><a href="'. fileurl($comment['file_id'], $comment['file']) .'" target="_blank">'. $comment['file'] .'</a></div><div style="float: left; width: 15%; text-align: center;">'. $comment['ip'] .'</div><div style="float: left; width: 15%; text-align: center;">'. $comment['date'] .'</div><div style="float: left; width: 15%; text-align: center;">'. $comment['status'] .'</div><div style="float: left; width: 5%; text-align: center;"><input type="checkbox" name="comment_id['. $comment['id'] .']" value="ok" /></div>
        <div class="line"></div>';
	}
	echo '
		<div align="right">
			  <select name="comments_action">
				<option value="delete_comment">'. $lang['delete'] .'</option>
				<option value="approve">'. $lang['approve'] .'</option>
			  </select> 
			<input type="submit" name="submit_comments" value="'. $lang['go'] .'" />
		</div>
        </form>
  	  </div>
  	</div>
  </div>
  <div style="clear: both;"></div>
  ';
	
	// Include footer
	template_footer();

}

// Mass Email: send mass email / PM to all members
function template_mass_email() {
	global $settings, $lang, $mass_email;

	// Include header
	template_header();
	
	echo '
  <div class="admin_left">';
	template_members_menu();
	echo '
  </div>
  <div class="admin_right">';
	if (strlen($mass_email['error'])) {
		echo '
    <div class="error_box">
      '. $mass_email['error'] .'
    </div>';
	}
	if ($mass_email['preview'] == TRUE) {
		echo '
    <div class="content_box_2">
      <div class="box_text">
        <b>'. $lang['subject'] .'</b> '. $mass_email['subject'] .'<br />
        '. $mass_email['preview_message'] .'
      </div>
    </div>';
	}
	echo '
    <div class="content_box_2">
      <div class="content_box_2_header">
        '. $lang['mass_email'] .'
      </div>
      <div class="box_text">
        <form action="" method="POST" name="form">
		  <div class="content_text_left" style="width: 25%;">'. $lang['subject'] .'</div>
		  <div class="content_text_right" style="width: 75%;"><input type="text" name="subject" size="30" value="'. $mass_email['subject'] .'" /></div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 25%;">
		    '. $lang['email_message'] .'<br />
		    <i>'. $lang['you_may_use'] .' {username}</i>
		  </div>
		  <div class="content_text_right" style="width: 75%;"><textarea name="message" rows="10" cols="50">'. $mass_email['message'] .'</textarea></div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 25%;">'. $lang['delivery_method'] .'</div>
		  <div class="content_text_right" style="width: 75%;">
		    <select name="delivery">
			  <option value="normal_email" '. $mass_email['normal_email_selected'] .'>'. $lang['normal_email'] .'</option>
			  <option value="private_message" '. $mass_email['private_message_selected'] .'>'. $lang['private_message'] .'</option>
			</select>
		  </div>
		  <div style="clear: both;"></div>
		  <div align="center">
		    <input type="submit" name="send" value="'. $lang['send_mail'] .'" /> <input type="submit" name="preview" value="'. $lang['preview_message'] .'" />
		  </div>
		</form>
  	  </div>
  	</div>
  </div>
  <div style="clear: both;"></div>
  ';
	
	// Include footer
	template_footer();

}

// Settings Menu: menu of settings pages
function template_settings_menu() {
	global $settings, $lang;
	
	echo '
    <div class="content_box">
      <div class="content_box_header">
        '. $lang['settings'] .'
      </div>
      <div class="box_text">
    	<a href="configurations.php">'. $lang['general_settings'] .'</a><br />
  	    <a href="configurations.php?a=file_settings">'. $lang['file_settings'] .'</a><br />
  	    <a href="configurations.php?a=member_settings">'. $lang['member_settings'] .'</a><br />
  	    <a href="configurations.php?a=templates">'. $lang['templates'] .'</a><br />
  	  </div>
  	</div>';

}

// General Settings: all the general arcade related settings
function template_general_settings() {
	global $settings, $lang, $general_setting;

	// Include header
	template_header();
	
	echo '
  <div class="admin_left">';
	template_settings_menu();
	echo '
  </div>
  <div class="admin_right">';
	if (strlen($general_setting['error'])) {
		echo '
    <div class="error_box">
      '. $general_setting['error'] .'
    </div>';
	}
	echo '
    <div class="content_box_2">
      <div class="content_box_2_header">
        '. $lang['general_settings'] .'
      </div>
      <div class="box_text">
        <form action="" method="POST" name="form">
		  <div class="content_text_left" style="width: 20%;">'. $lang['site_title'] .'</div>
		  <div class="content_text_right" style="width: 80%;"><input type="text" name="site_title" value="'. $settings['sitename'] .'" size="30" /></div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">'. $lang['site_url'] .'</div>
		  <div class="content_text_right" style="width: 80%;"><input type="text" name="site_url" value="'. $settings['siteurl'] .'" size="30" /></div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">'. $lang['site_description'] .'</div>
		  <div class="content_text_right" style="width: 80%;"><textarea name="site_description" rows="1" cols="35">'. $settings['sitedescription'] .'</textarea></div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">'. $lang['site_keywords'] .'</div>
		  <div class="content_text_right" style="width: 80%;"><textarea name="site_keywords" rows="1" cols="35">'. $settings['sitekeywords'] .'</textarea></div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">'. $lang['contact_email'] .'</div>
		  <div class="content_text_right" style="width: 80%;"><input type="text" name="contact_email" value="'. $settings['sitecontactemail'] .'" size="30" /></div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">'. $lang['site_status'] .'</div>
		  <div class="content_text_right" style="width: 80%;">
		    <select name="site_status">
			  <option value="1" '. ($settings['siteonline'] == '1' ? 'selected' : '') .'>'. $lang['online'] .'</option>
			  <option value="0" '. ($settings['siteonline'] == '0' ? 'selected' : '') .'>'. $lang['offline'] .'</option>
			</select>
		  </div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">'. $lang['template'] .'</div>
		  <div class="content_text_right" style="width: 80%;">'. $general_setting['template_selector'] .'</div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">'. $lang['language'] .'</div>
		  <div class="content_text_right" style="width: 80%;">'. $general_setting['language_selector'] .'</div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">'. $lang['se_friendly_urls'] .'</div>
		  <div class="content_text_right" style="width: 80%;">
		    <select name="se_friendly_urls">
			  <option value="1" '. ($settings['sefriendly'] == '1' ? 'selected' : '') .'>'. $lang['on'] .'</option>
			  <option value="0" '. ($settings['sefriendly'] == '0' ? 'selected' : '') .'>'. $lang['off'] .'</option>
			</select>
		  </div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">'. $lang['category_url'] .'</div>
		  <div class="content_text_right" style="width: 80%;"><input type="text" name="category_url" value="'. $settings['categoryurl'] .'" size="30" /></div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">'. $lang['file_url'] .'</div>
		  <div class="content_text_right" style="width: 80%;"><input type="text" name="file_url" value="'. $settings['fileurl'] .'" size="30" /></div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">'. $lang['profile_url'] .'</div>
		  <div class="content_text_right" style="width: 80%;"><input type="text" name="profile_url" value="'. $settings['profileurl'] .'" size="30" /></div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">'. $lang['scores_url'] .'</div>
		  <div class="content_text_right" style="width: 80%;"><input type="text" name="scores_url" value="'. $settings['scoresurl'] .'" size="30" /></div>
		  <div style="clear: both;"></div>
		  
		  <div class="content_text_left" style="width: 20%;">'. $lang['image_verification'] .'</div>
		  <div class="content_text_right" style="width: 80%;">
		    <select name="image_verification">
			  <option value="1" '. ($settings['image_verification'] == '1' ? 'selected' : '') .'>'. $lang['on'] .'</option>
			  <option value="0" '. ($settings['image_verification'] == '0' ? 'selected' : '') .'>'. $lang['off'] .'</option>
			</select>
		  </div>
		  <div style="clear: both;"></div>
		  
		  <div class="content_text_left" style="width: 20%;">'. $lang['news_settings'] .'</div>
		  <div class="content_text_right" style="width: 80%;">
		    <select name="news">
			  <option value="1" '. ($settings['news'] == '1' ? 'selected' : '') .'>'. $lang['on'] .'</option>
			  <option value="0" '. ($settings['news'] == '0' ? 'selected' : '') .'>'. $lang['off'] .'</option>
			</select>
		  </div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">'. $lang['news_on_index'] .'</div>
		  <div class="content_text_right" style="width: 80%;"><input type="text" name="news_index" value="'. $settings['news_index'] .'" size="5" /></div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">'. $lang['links_settings'] .'</div>
		  <div class="content_text_right" style="width: 80%;">
		    <select name="links">
			  <option value="1" '. ($settings['links'] == '1' ? 'selected' : '') .'>'. $lang['on'] .'</option>
			  <option value="0" '. ($settings['links'] == '0' ? 'selected' : '') .'>'. $lang['off'] .'</option>
			</select>
		  </div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">'. $lang['links_on_menu'] .'</div>
		  <div class="content_text_right" style="width: 80%;"><input type="text" name="max_links" value="'. $settings['max_links'] .'" size="5" /></div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">'. $lang['header_ad'] .'</div>
		  <div class="content_text_right" style="width: 80%;">
		    <select name="header_ad">
			  <option value="1" '. ($settings['header_ad'] == '1' ? 'selected' : '') .'>'. $lang['on'] .'</option>
			  <option value="0" '. ($settings['header_ad'] == '0' ? 'selected' : '') .'>'. $lang['off'] .'</option>
			</select>
		  </div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">'. $lang['footer_ad'] .'</div>
		  <div class="content_text_right" style="width: 80%;">
		    <select name="footer_ad">
			  <option value="1" '. ($settings['footer_ad'] == '1' ? 'selected' : '') .'>'. $lang['on'] .'</option>
			  <option value="0" '. ($settings['footer_ad'] == '0' ? 'selected' : '') .'>'. $lang['off'] .'</option>
			</select>
		  </div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">'. $lang['file_ad'] .'</div>
		  <div class="content_text_right" style="width: 80%;">
		    <select name="file_ad">
			  <option value="1" '. ($settings['file_ad'] == '1' ? 'selected' : '') .'>'. $lang['on'] .'</option>
			  <option value="0" '. ($settings['file_ad'] == '0' ? 'selected' : '') .'>'. $lang['off'] .'</option>
			</select>
		  </div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">'. $lang['before_file_ad'] .'</div>
		  <div class="content_text_right" style="width: 80%;">
		    <select name="before_file_ad">
			  <option value="1" '. ($settings['before_file_ad'] == '1' ? 'selected' : '') .'>'. $lang['on'] .'</option>
			  <option value="2" '. ($settings['before_file_ad'] == '2' ? 'selected' : '') .'>'. $lang['guests_only'] .'</option>
			  <option value="0" '. ($settings['before_file_ad'] == '0' ? 'selected' : '') .'>'. $lang['off'] .'</option>
			</select>
		  </div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">'. $lang['cache'] .'</div>
		  <div class="content_text_right" style="width: 80%;">
		    <select name="cache">
			  <option value="1" '. ($settings['cache'] == '1' ? 'selected' : '') .'>'. $lang['on'] .'</option>
			  <option value="0" '. ($settings['cache'] == '0' ? 'selected' : '') .'>'. $lang['off'] .'</option>
			</select>
		  </div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">'. $lang['cache_time'] .'</div>
		  <div class="content_text_right" style="width: 80%;"><input type="text" name="cache_expire" value="'. $settings['cache_expire'] .'" size="5" /> '. $lang['minutes'] .'</div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">'. $lang['copy_function'] .'</div>
		  <div class="content_text_right" style="width: 80%;">
		    <select name="copy">
			  <option value="1" '. ($settings['copy'] == '1' ? 'selected' : '') .'>copy()</option>
			  <option value="2" '. ($settings['copy'] == '2' ? 'selected' : '') .'>cURL</option>
			</select>
		  </div>
		  <div style="clear: both;"></div>
		  <div align="center">
		    <input type="submit" name="submit_settings" value="'. $lang['submit'] .'" />
		  </div>
		</form>
  	  </div>
  	</div>
  </div>
  <div style="clear: both;"></div>
  ';
	
	// Include footer
	template_footer();

}

// File Settings: file settings can be changed here
function template_file_settings() {
	global $settings, $lang, $settings_error;

	// Include header
	template_header();
	
	echo '
  <div class="admin_left">';
	template_settings_menu();
	echo '
  </div>
  <div class="admin_right">';
	if (strlen($settings_error)) {
		echo '
    <div class="error_box">
      '. $settings_error .'
    </div>';
	}
	echo '
    <div class="content_box_2">
      <div class="content_box_2_header">
        '. $lang['file_settings'] .'
      </div>
      <div class="box_text">
        <form action="" method="POST" name="form">
		  <div class="content_text_left" style="width: 20%;">'. $lang['files_directory'] .'</div>
		  <div class="content_text_right" style="width: 80%;"><input type="text" name="files_dir" value="'. $settings['filesdir'] .'" size="30" /></div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">'. $lang['file_url'] .'</div>
		  <div class="content_text_right" style="width: 80%;"><input type="text" name="file_url" value="'. $settings['fileurl'] .'" size="30" /></div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">'. $lang['scores_url'] .'</div>
		  <div class="content_text_right" style="width: 80%;"><input type="text" name="scores_url" value="'. $settings['scoresurl'] .'" size="30" /></div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">'. $lang['category_url'] .'</div>
		  <div class="content_text_right" style="width: 80%;"><input type="text" name="category_url" value="'. $settings['categoryurl'] .'" size="30" /></div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">'. $lang['files_on_index'] .'</div>
		  <div class="content_text_right" style="width: 80%;"><input type="text" name="max_files_index" value="'. $settings['max_files_index'] .'" size="5" /></div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">'. $lang['stars_on_index'] .'</div>
		  <div class="content_text_right" style="width: 80%;">
		    <select name="stars_index">
			  <option value="1" '. ($settings['stars_index'] == '1' ? 'selected' : '') .'>'. $lang['on'] .'</option>
			  <option value="0" '. ($settings['stars_index'] == '0' ? 'selected' : '') .'>'. $lang['off'] .'</option>
			</select>
		  </div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">'. $lang['image_width'] .'</div>
		  <div class="content_text_right" style="width: 80%;"><input type="text" name="image_width" value="'. $settings['image_width'] .'" size="5" /></div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">'. $lang['image_height'] .'</div>
		  <div class="content_text_right" style="width: 80%;"><input type="text" name="image_height" value="'. $settings['image_height'] .'" size="5" /></div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">'. $lang['max_file_width'] .'</div>
		  <div class="content_text_right" style="width: 80%;"><input type="text" name="max_file_width" value="'. $settings['max_file_width'] .'" size="10" /></div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">'. $lang['max_file_height'] .'</div>
		  <div class="content_text_right" style="width: 80%;"><input type="text" name="max_file_height" value="'. $settings['max_file_height'] .'" size="10" /></div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">'. $lang['auto_resize'] .'</div>
		  <div class="content_text_right" style="width: 80%;">
		    <select name="auto_resize">
			  <option value="1" '. ($settings['auto_resize'] == '1' ? 'selected' : '') .'>'. $lang['on'] .'</option>
			  <option value="0" '. ($settings['auto_resize'] == '0' ? 'selected' : '') .'>'. $lang['off'] .'</option>
			</select>
		  </div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">'. $lang['tell_a_friend'] .'</div>
		  <div class="content_text_right" style="width: 80%;">
		    <select name="tell_friend">
			  <option value="0" '. ($settings['tellfriend'] == '0' ? 'selected' : '') .'>'. $lang['off'] .'</option>
			  <option value="1" '. ($settings['tellfriend'] == '1' ? 'selected' : '') .'>'. $lang['on'] .'</option>
			  <option value="2" '. ($settings['tellfriend'] == '2' ? 'selected' : '') .'>'. $lang['members_only'] .'</option>
			</select>
		  </div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">'. $lang['report_broken'] .'</div>
		  <div class="content_text_right" style="width: 80%;">
		    <select name="report_broken">
			  <option value="1" '. ($settings['report_broken'] == '1' ? 'selected' : '') .'>'. $lang['on'] .'</option>
			  <option value="0" '. ($settings['report_broken'] == '0' ? 'selected' : '') .'>'. $lang['off'] .'</option>
			</select>
		  </div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">'. $lang['comments_settings'] .'</div>
		  <div class="content_text_right" style="width: 80%;">
		    <select name="comments">
			  <option value="1" '. ($settings['comments'] == '1' ? 'selected' : '') .'>'. $lang['on'] .'</option>
			  <option value="0" '. ($settings['comments'] == '0' ? 'selected' : '') .'>'. $lang['off'] .'</option>
			</select>
		  </div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">'. $lang['comment_who'] .'</div>
		  <div class="content_text_right" style="width: 80%;">
		    <select name="comment_who">
			  <option value="1" '. ($settings['comments_who'] == '1' ? 'selected' : '') .'>'. $lang['everybody'] .'</option>
			  <option value="2" '. ($settings['comments_who'] == '2' ? 'selected' : '') .'>'. $lang['members_only'] .'</option>
			</select>
		  </div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">'. $lang['comment_approval'] .'</div>
		  <div class="content_text_right" style="width: 80%;">
		    <select name="comment_approval">
		      <option value="0" '. ($settings['comments_approval'] == '0' ? 'selected' : '') .'>'. $lang['off'] .'</option>
			  <option value="1" '. ($settings['comments_approval'] == '1' ? 'selected' : '') .'>'. $lang['guests_only'] .'</option>
			  <option value="2" '. ($settings['comments_approval'] == '2' ? 'selected' : '') .'>'. $lang['on'] .'</option>
			</select>
		  </div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">'. $lang['max_comments'] .'</div>
		  <div class="content_text_right" style="width: 80%;"><input type="text" name="max_comments" value="'. $settings['max_comments'] .'" size="5" /></div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">'. $lang['comment_flood'] .'</div>
		  <div class="content_text_right" style="width: 80%;"><input type="text" name="comments_flood_time" value="'. $settings['comments_flood_time'] .'" size="5" /></div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">
		    '. $lang['banned_ips'] .'<br />
		    <i>('. $lang['separate_space'] .')</i>
		  </div>
		  <div class="content_text_right" style="width: 80%;"><textarea name="comments_banned_ip" rows="1" cols="35">'. $settings['comments_banned_ip'] .'</textarea></div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">'. $lang['bad_word_filter'] .'</div>
		  <div class="content_text_right" style="width: 80%;">
		    <select name="bad_word_filter">
			  <option value="1" '. ($settings['bad_word_filter'] == '1' ? 'selected' : '') .'>'. $lang['on'] .'</option>
			  <option value="0" '. ($settings['bad_word_filter'] == '0' ? 'selected' : '') .'>'. $lang['off'] .'</option>
			</select>
		  </div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">
		    '. $lang['bad_words'] .'<br />
		    <i>('. $lang['separate_space'] .')</i>
		  </div>
		  <div class="content_text_right" style="width: 80%;"><textarea name="bad_words" rows="1" cols="35">'. $settings['bad_words'] .'</textarea></div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">'. $lang['added_by'] .'</div>
		  <div class="content_text_right" style="width: 80%;">
		    <select name="added_by">
			  <option value="1" '. ($settings['added_by'] == '1' ? 'selected' : '') .'>'. $lang['on'] .'</option>
			  <option value="0" '. ($settings['added_by'] == '0' ? 'selected' : '') .'>'. $lang['off'] .'</option>
			</select>
		  </div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">'. $lang['rate_settings'] .'</div>
		  <div class="content_text_right" style="width: 80%;">
		    <select name="rate">
			  <option value="0" '. ($settings['rate'] == '0' ? 'selected' : '') .'>'. $lang['off'] .'</option>
			  <option value="1" '. ($settings['rate'] == '1' ? 'selected' : '') .'>'. $lang['on'] .'</option>
			  <option value="2" '. ($settings['rate'] == '2' ? 'selected' : '') .'>'. $lang['members_only'] .'</option>
			</select>
		  </div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">'. $lang['add_to_website'] .'</div>
		  <div class="content_text_right" style="width: 80%;">
		    <select name="add_to_website">
			  <option value="1" '. ($settings['add_to_website'] == '1' ? 'selected' : '') .'>'. $lang['on'] .'</option>
			  <option value="0" '. ($settings['add_to_website'] == '0' ? 'selected' : '') .'>'. $lang['off'] .'</option>
			</select>
		  </div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">'. $lang['related_files'] .'</div>
		  <div class="content_text_right" style="width: 80%;">
		    <select name="related_files">
			  <option value="1" '. ($settings['related_files'] == '1' ? 'selected' : '') .'>'. $lang['on'] .'</option>
			  <option value="0" '. ($settings['related_files'] == '0' ? 'selected' : '') .'>'. $lang['off'] .'</option>
			</select>
		  </div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">'. $lang['max_related_files'] .'</div>
		  <div class="content_text_right" style="width: 80%;"><input type="text" name="max_related_files" value="'. $settings['max_related_files'] .'" size="5" /></div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">'. $lang['files_per_page'] .'</div>
		  <div class="content_text_right" style="width: 80%;"><input type="text" name="browse_per_page" value="'. $settings['browse_per_page'] .'" size="5" /></div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">'. $lang['submit_settings'] .'</div>
		  <div class="content_text_right" style="width: 80%;">
		    <select name="submit_file">
		      <option value="0" '. ($settings['submit'] == '0' ? 'selected' : '') .'>'. $lang['off'] .'</option>
			  <option value="1" '. ($settings['submit'] == '1' ? 'selected' : '') .'>'. $lang['on'] .'</option>
			  <option value="2" '. ($settings['submit'] == '2' ? 'selected' : '') .'>'. $lang['members_only'] .'</option>
			</select>
		  </div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">'. $lang['max_file_size'] .'</div>
		  <div class="content_text_right" style="width: 80%;"><input type="text" name="submit_file_size" value="'. $settings['submit_file_size'] .'" size="10" /> '. $lang['kb'] .'</div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">'. $lang['max_image_size'] .'</div>
		  <div class="content_text_right" style="width: 80%;"><input type="text" name="submit_image_size" value="'. $settings['submit_image_size'] .'" size="10" /> '. $lang['kb'] .'</div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">'. $lang['file_types'] .'</div>
		  <div class="content_text_right" style="width: 80%;"><input type="text" name="submit_valid_file" value="'. $settings['submit_valid_file'] .'" size="40" /></div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">'. $lang['image_types'] .'</div>
		  <div class="content_text_right" style="width: 80%;"><input type="text" name="submit_valid_image" value="'. $settings['submit_valid_image'] .'" size="40" /></div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">'. $lang['game_slave_on'] .'</div>
		  <div class="content_text_right" style="width: 80%;">
		    <select name="game_slave">
			  <option value="1" '. ($settings['game_slave'] == '1' ? 'selected' : '') .'>'. $lang['on'] .'</option>
			  <option value="0" '. ($settings['game_slave'] == '0' ? 'selected' : '') .'>'. $lang['off'] .'</option>
			</select>
		  </div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">'. $lang['add_per_day'] .'</div>
		  <div class="content_text_right" style="width: 80%;"><input type="text" name="game_slave_games" value="'. $settings['game_slave_games'] .'" size="5" /></div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">'. $lang['most_popular_list'] .'</div>
		  <div class="content_text_right" style="width: 80%;">
		    <select name="most_popular_list">
			  <option value="1" '. ($settings['most_popular_list'] == '1' ? 'selected' : '') .'>'. $lang['on'] .'</option>
			  <option value="0" '. ($settings['most_popular_list'] == '0' ? 'selected' : '') .'>'. $lang['off'] .'</option>
			</select>
		  </div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">'. $lang['max_most_popular'] .'</div>
		  <div class="content_text_right" style="width: 80%;"><input type="text" name="max_most_popular" value="'. $settings['max_most_popular'] .'" size="5" /></div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">'. $lang['newest_list'] .'</div>
		  <div class="content_text_right" style="width: 80%;">
		    <select name="newest_list">
			  <option value="1" '. ($settings['newest_list'] == '1' ? 'selected' : '') .'>'. $lang['on'] .'</option>
			  <option value="0" '. ($settings['newest_list'] == '0' ? 'selected' : '') .'>'. $lang['off'] .'</option>
			</select>
		  </div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">'. $lang['max_newest'] .'</div>
		  <div class="content_text_right" style="width: 80%;"><input type="text" name="max_newest" value="'. $settings['max_newest'] .'" size="5" /></div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">'. $lang['file_ad'] .'</div>
		  <div class="content_text_right" style="width: 80%;">
		    <select name="file_ad">
			  <option value="1" '. ($settings['file_ad'] == '1' ? 'selected' : '') .'>'. $lang['on'] .'</option>
			  <option value="0" '. ($settings['file_ad'] == '0' ? 'selected' : '') .'>'. $lang['off'] .'</option>
			</select>
		  </div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">'. $lang['before_file_ad'] .'</div>
		  <div class="content_text_right" style="width: 80%;">
		    <select name="before_file_ad">
			  <option value="1" '. ($settings['before_file_ad'] == '1' ? 'selected' : '') .'>'. $lang['on'] .'</option>
			  <option value="0" '. ($settings['before_file_ad'] == '0' ? 'selected' : '') .'>'. $lang['off'] .'</option>
			</select>
		  </div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">'. $lang['cheater_protection'] .'</div>
		  <div class="content_text_right" style="width: 80%;">
		    <select name="cheater_protection">
			  <option value="1" '. ($settings['cheater_protection'] == '1' ? 'selected' : '') .'>'. $lang['on'] .'</option>
			  <option value="0" '. ($settings['cheater_protection'] == '0' ? 'selected' : '') .'>'. $lang['off'] .'</option>
			</select>
		  </div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">'. $lang['file_sponsorship'] .'</div>
		  <div class="content_text_right" style="width: 80%;">
		    <select name="sponsor">
			  <option value="1" '. ($settings['sponsor'] == '1' ? 'selected' : '') .'>'. $lang['on'] .'</option>
			  <option value="0" '. ($settings['sponsor'] == '0' ? 'selected' : '') .'>'. $lang['off'] .'</option>
			</select>
		  </div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">'. $lang['sponsor_price'] .'</div>
		  <div class="content_text_right" style="width: 80%;">$<input type="text" name="sponsor_price" value="'. $settings['sponsor_price'] .'" size="5" /></div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">'. $lang['paypal_email'] .'</div>
		  <div class="content_text_right" style="width: 80%;"><input type="text" name="paypal_email" value="'. $settings['paypal_email'] .'" size="40" /></div>
		  <div style="clear: both;"></div>
		  
		  <div align="center">
		    <input type="submit" name="submit_settings" value="'. $lang['submit'] .'" />
		  </div>
		</form>
  	  </div>
  	</div>
  </div>
  <div style="clear: both;"></div>
  ';
	
	// Include footer
	template_footer();

}

// Member Settings: members and membership related settings
function template_member_settings() {
	global $settings, $lang, $settings_error;

	// Include header
	template_header();
	
	echo '
  <div class="admin_left">';
	template_settings_menu();
	echo '
  </div>
  <div class="admin_right">';
	if (strlen($settings_error)) {
		echo '
    <div class="error_box">
      '. $settings_error .'
    </div>';
	}
	echo '
    <div class="content_box_2">
      <div class="content_box_2_header">
        '. $lang['member_settings'] .'
      </div>
      <div class="box_text">
        <form action="" method="POST" name="form">
          <div class="content_text_left" style="width: 20%;">'. $lang['member_login'] .'</div>
		  <div class="content_text_right" style="width: 80%;">
		    <select name="member_login">
			  <option value="1" '. ($settings['memberlogin'] == '1' ? 'selected' : '') .'>'. $lang['on'] .'</option>
			  <option value="0" '. ($settings['memberlogin'] == '0' ? 'selected' : '') .'>'. $lang['off'] .'</option>
			</select>
		  </div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">'. $lang['email_confirmation'] .'</div>
		  <div class="content_text_right" style="width: 80%;">
		    <select name="email_confirmation">
			  <option value="1" '. ($settings['email_confirmation'] == '1' ? 'selected' : '') .'>'. $lang['on'] .'</option>
			  <option value="0" '. ($settings['email_confirmation'] == '0' ? 'selected' : '') .'>'. $lang['off'] .'</option>
			</select>
		  </div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">'. $lang['guest_credits'] .'</div>
		  <div class="content_text_right" style="width: 80%;">
		    <select name="guest_credits">
			  <option value="1" '. ($settings['guestcredits'] == '1' ? 'selected' : '') .'>'. $lang['on'] .'</option>
			  <option value="0" '. ($settings['guestcredits'] == '0' ? 'selected' : '') .'>'. $lang['off'] .'</option>
			</select>
		  </div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">'. $lang['guest_credits'] .'</div>
		  <div class="content_text_right" style="width: 80%;"><input type="text" name="max_guest_plays" value="'. $settings['maxguestplays'] .'" size="5" /></div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">'. $lang['failed_login_quota'] .'</div>
		  <div class="content_text_right" style="width: 80%;"><input type="text" name="failed_login_quota" value="'. $settings['failed_login_quota'] .'" size="5" /></div>
		  <div style="clear: both;"></div>
          <div class="content_text_left" style="width: 20%;">
		    '. $lang['banned_ips'] .'<br />
		    <i>('. $lang['separate_space'] .')</i>
		  </div>
		  <div class="content_text_right" style="width: 80%;"><textarea name="banned_ip" rows="1" cols="35">'. $settings['banned_ip'] .'</textarea></div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">'. $lang['profile_url'] .'</div>
		  <div class="content_text_right" style="width: 80%;"><input type="text" name="profile_url" value="'. $settings['profileurl'] .'" size="30" /></div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">'. $lang['remote_avatar'] .'</div>
		  <div class="content_text_right" style="width: 80%;">
		    <select name="remote_avatar">
			  <option value="1" '. ($settings['remote_avatar'] == '1' ? 'selected' : '') .'>'. $lang['on'] .'</option>
			  <option value="0" '. ($settings['remote_avatar'] == '0' ? 'selected' : '') .'>'. $lang['off'] .'</option>
			</select>
		  </div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">'. $lang['avatar_uploading'] .'</div>
		  <div class="content_text_right" style="width: 80%;">
		    <select name="avatar_uploading">
			  <option value="1" '. ($settings['avatar_uploading'] == '1' ? 'selected' : '') .'>'. $lang['on'] .'</option>
			  <option value="0" '. ($settings['avatar_uploading'] == '0' ? 'selected' : '') .'>'. $lang['off'] .'</option>
			</select>
		  </div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">'. $lang['avatar_size'] .'</div>
		  <div class="content_text_right" style="width: 80%;"><input type="text" name="avatar_size" value="'. $settings['avatar_size'] .'" size="5" /> '. $lang['kb'] .'</div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">'. $lang['max_avatar_width'] .'</div>
		  <div class="content_text_right" style="width: 80%;"><input type="text" name="avatar_width" value="'. $settings['avatar_width'] .'" size="5" /></div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">'. $lang['max_avatar_height'] .'</div>
		  <div class="content_text_right" style="width: 80%;"><input type="text" name="avatar_height" value="'. $settings['avatar_height'] .'" size="5" /></div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">'. $lang['avatar_gallery'] .'</div>
		  <div class="content_text_right" style="width: 80%;">
		    <select name="avatar_gallery">
			  <option value="1" '. ($settings['avatar_gallery'] == '1' ? 'selected' : '') .'>'. $lang['on'] .'</option>
			  <option value="0" '. ($settings['avatar_gallery'] == '0' ? 'selected' : '') .'>'. $lang['off'] .'</option>
			</select>
		  </div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">'. $lang['member_per_page'] .'</div>
		  <div class="content_text_right" style="width: 80%;"><input type="text" name="member_list_per_page" value="'. $settings['member_list_per_page'] .'" size="5" /></div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">'. $lang['top_players_list'] .'</div>
		  <div class="content_text_right" style="width: 80%;">
		    <select name="top_players_list">
			  <option value="1" '. ($settings['top_players_list'] == '1' ? 'selected' : '') .'>'. $lang['top_plays'] .'</option>
			  <option value="2" '. ($settings['top_players_list'] == '2' ? 'selected' : '') .'>'. $lang['top_wins'] .'</option>
			  <option value="0" '. ($settings['top_players_list'] == '0' ? 'selected' : '') .'>'. $lang['off'] .'</option>
			</select>
		  </div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">'. $lang['max_top_players'].'</div>
		  <div class="content_text_right" style="width: 80%;"><input type="text" name="max_top_players" value="'. $settings['max_top_players'] .'" size="5" /></div>
		  <div style="clear: both;"></div>
		  <div align="center">
		    <input type="submit" name="submit_settings" value="'. $lang['submit'] .'" />
		  </div>
		</form>
  	  </div>
  	</div>
  </div>
  <div style="clear: both;"></div>
  ';
	
	// Include footer
	template_footer();

}

// Templates: all templates are displayed here
function template_templates() {
	global $settings, $lang, $templates, $settings_error;

	// Include header
	template_header();
	
	echo '
  <div class="admin_left">';
	template_settings_menu();
	echo '
  </div>
  <div class="admin_right">';
	if (strlen($settings_error)) {
		echo '
    <div class="error_box">
      '. $settings_error .'
    </div>';
	}
	echo '
    <div class="content_box_2">
      <div class="content_box_2_header">
        '. $lang['templates'] .'
      </div>
      <div class="box_text">';
    foreach ($templates as $template) {
    	echo '
        <div style="float: left; width: 20%;">
          <img src="'. $settings['siteurl'] .'/templates/'. $template['folder'] .'/preview.jpg" width="100" height="100" alt="'. $lang['preview'].'" title="'. $lang['preview'].'" />
        </div>
		<div style="float: right; width: 80%;">
          <b>'. $template['title'] .'</b>'. ($settings['template'] == $template['folder'] ? ' ('. $lang['default'] .')' : '') .'<br />
          - <a href="configurations.php?a=template_files&t='. $template['folder'] .'" class="template_link">'. $lang['edit'] .'</a><br />
          '. ($settings['template'] != $template['folder'] ? '- <a href="configurations.php?a=templates&default_template='. $template['folder'] .'" class="template_link">'. $lang['set_default'] .'</a>' : '') .'
		</div>
		<div class="line"></div>';
    }
	echo '
  	  </div>
  	</div>
  </div>
  <div style="clear: both;"></div>
  ';
	
	// Include footer
	template_footer();

}

// Template Files: files of template are displayed here
function template_template_files() {
	global $settings, $lang, $files;

	// Include header
	template_header();
	
	echo '
  <div class="admin_left">';
	template_settings_menu();
	echo '
  </div>
  <div class="admin_right">
    <div class="content_box_2">
      <div class="content_box_2_header">
        '. $lang['templates'] .'
      </div>
      <div class="box_text">';
    foreach ($files as $file) {
    	echo '
    	<div style="float: left; width: 5%;">
          '. ($file['type'] == 'php' ? '<img src="images/php.gif" width="16" height="16" alt="PHP" title="PHP" />' : '<img src="images/css.gif" width="16" height="16" alt="CSS" title="CSS" />') .'
        </div>
		<div style="float: right; width: 95%;">
          '. ($file['write'] == TRUE ? '<a href="configurations.php?a=edit_template_file&f='. $file['location'] .'">'. $file['file'] .'</a>' : $file['file']) .'
		</div>
		<div class="line"></div>';
    }
	echo '
  	  </div>
  	</div>
  </div>
  <div style="clear: both;"></div>
  ';
	
	// Include footer
	template_footer();

}

// Edit Template File: here we can edit template files
function template_edit_template_file() {
	global $settings, $lang, $edit_template, $settings_error;

	// Include header
	template_header();
	
	echo '
  <div class="admin_left">';
	template_settings_menu();
	echo '
  </div>
  <div class="admin_right">';
	if (strlen($settings_error)) {
		echo '
    <div class="error_box">
      '. $settings_error .'
    </div>';
	}
	echo '
    <div class="content_box_2">
      <div class="content_box_2_header">
        '. $lang['templates'] .'
      </div>
      <div class="box_text">
	    <form action="" method="POST" name="form">';
	foreach ($edit_template as $key => $template) {
		echo (strlen($template['header']) ? '<br /><b>'. $template['header'] .'</b><br />' : '') .'
          <textarea name="template_file_content['. $key .']" rows="20" cols="110" style="font-size: 12px;">'. $template['code'] .'</textarea><br />';
	}
	echo '
          <div align="center"><input type="submit" name="submit_save" value="'. $lang['submit'] .'" /></div>
        </form>
  	  </div>
  	</div>
  </div>
  <div style="clear: both;"></div>
  ';
	
	// Include footer
	template_footer();

}

// Log In: Log in page
//function template_log_in() {
	
//}

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
'. $redirect_message .'<br /><br />
'. $lang['if_not_redirected'] .'
</div>

</center>
</body>
</html>';
	
}

?>