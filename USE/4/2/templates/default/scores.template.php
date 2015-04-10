<?php
/**
 * onArcade 2.0.0
 * Copyright © 2006-2007 Hans Mдesalu & Eveterm OЬ, All Rights Reserved
 *
 * Template: Dark
 **/

// Browse: here are all the files displayed
function template_scores() {
	global $settings, $lang, $user, $file, $scores, $navigation;
	
	// Include header
	template_header();
	echo '
	  <div class="content_box_header">
        <a href="'. $settings['siteurl'] .'/" class="contentheaderlink">'. $settings['sitename'] .'</a> > <a href="'. categoryurl($file['category_id'], $file['category_title']) .'" class="contentheaderlink">'. $file['category_title'] .'</a> > <a href="'. fileurl($file['id'], $file['title']) .'" class="contentheaderlink">'. $file['title'] .'</a> > '. $lang['scores'] . ($user['group'] == '2' ? ' [<a href="'. $settings['siteurl'] .'/admin/content.php?a=edit_scores&f='. $file['id'] .'">'. $lang['edit_scores'] .'</a>]' : '') .'
      </div>
      <div class="content_box">
        <div align="center">
	      <div style="width: 300px; margin-bottom: 5px; padding: 3px;">
	        <div style="float: left; width: 30%; text-align: center;">
		      <a href="'. fileurl($file['id'], $file['title']) .'"><img src="'. $file['image'] .'" width="'. $settings['image_width'] .'" height="'. $settings['image_height'] .'" title="'. $file['title'] .'" alt="'. $file['title'] .'" border="0" /></a>
		    </div>
		    <div style="float: right; width: 70%; text-align: left;">
		      <a href="'. fileurl($file['id'], $file['title']) .'">'. $file['title'] .'</a><br />
		      <img src="'. $settings['siteurl'] .'/images/stars'. stars($file['rating']) .'.gif" border="0" width="67" height="15" alt="'. $file['rating'] .'" title="'. $file['rating'] .'" id="file_rating" />';
	if ($settings['rate'] == '2' && $user['status'] == '1' || $settings['rate'] == '1') {
        echo ' <span id="rate_file">[<a href="javascript: rate_file_form(\''. $file['id'] .'\')"><b>'. $lang['file_rate'] .'</b></a>]</span>';
    }
    echo '<br />
		      '. $file['description'] .'
		    </div>
	        <div style="clear: both;"></div>
	      </div>
	    </div>
		<div style="float: left; width: 5%; text-align: center; margin: 2px;">#</div><div style="float: left; width: 20%; text-align: center; margin: 2px;">'. $lang['score_username'] .'</div><div style="float: left; width: 20%; text-align: center; margin: 2px;">'. $lang['score_score'] .'</div><div style="float: left; width: 30%; text-align: center; margin: 2px;">'. $lang['score_comment'] .'</div><div style="float: left; width: 20%; text-align: center; margin: 2px;">'. $lang['score_date'] .'</div>
		<div style="clear: both; border-bottom: 1px solid #000000;"></div>';
	foreach ($scores as $number => $score) {
		echo '
		<div style="float: left; width: 5%; text-align: center; margin: 1px; padding: 1px;">'. $number .'.</div><div style="float: left; width: 20%; text-align: center; margin: 2px;"><a href="'. profileurl($score['user_id'], $score['username']) .'">'. $score['username'] .'</a></div><div style="float: left; width: 20%; text-align: center; margin: 2px;">'. $score['score'] .'</div><div style="float: left; width: 30%; text-align: center; margin: 2px;" id="edit_comment_'. $score['id'] .'">'. $score['comment'] . ($score['user_id'] == $user['id'] ? ' [<a onclick="edit_comment(\''. $score['id'] .'\');">'. $lang['edit'] .'</a>]' : '') .'</div><div style="float: left; width: 20%; text-align: center; margin: 2px;">'. $score['date_score'] .'</div>
		<div style="clear: both; border-bottom: 1px solid #000000;"></div>';
	}
	echo '
		<div class="pagination">
		  '. $navigation .'
		</div>
	  </div>';
	template_footer();
	
}

?>