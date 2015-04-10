<?php
/**
 * onArcade 2.0.0
 * Copyright © 2006-2007 Hans Mдesalu & Eveterm OЬ, All Rights Reserved
 *
 * Template: Dark
 **/

// Member List: here we can log in
function template_member_list() {
	global $settings, $lang, $member_list, $navigation;
	
	template_header();
	echo '
      <div class="content_box_header">
        '. $lang['member_list'] .'
      </div>
      <div class="content_box">
	    <div style="float: left; width: 30%; text-align: center;"><b>'. $lang['member_list_username'] .'</b></div><div style="float: left; width: 25%; text-align: center;"><b>'. $lang['member_list_location'] .'</b></div><div style="float: left; width: 20%; text-align: center;"><b>'. $lang['member_list_joined'] .'</b></div><div style="float: left; width: 15%; text-align: center;"><b>'. $lang['member_list_played'] .'</b></div><div style="float: left; width: 10%; text-align: center;"><b>'. $lang['member_list_pm'] .'</b></div>
	    <div style="clear: both; border-bottom: 1px solid #000000; padding-top: 1px; margin-bottom: 1px;"></div>';
	foreach ($member_list as $member) {
		echo '
	    <div style="float: left; width: 30%; text-align: center;"><a href="'. profileurl($member['id'],$member['name']) .'">'. $member['name'] .'</a></div><div style="float: left; width: 25%; text-align: center;">'. $member['location'] .'&nbsp;</div><div style="float: left; width: 20%; text-align: center;">'. $member['joined'] .'</div><div style="float: left; width: 15%; text-align: center;">'. $member['played'] .'</div><div style="float: left; width: 10%; text-align: center;"><a href="'. $settings['siteurl'] .'/privatemessages.php?a=compose&u='. $member['id'] .'">'. $lang['member_list_pm'] .'</a></div>
	    <div style="clear: both; border-bottom: 1px solid #000000; padding-top: 1px; margin-bottom: 1px;"></div>';
	}
	echo '
		<div style="padding: 5px; text-align: right;">
		  <form action="'. $settings['siteurl'] .'/memberlist.php" method="post">
			'. $lang['member_list_sort_by'] .'
		    <input type="submit" class="button" value="'. $lang['member_list_go'] .'" />
		  </form>
		</div>
		<div align="center">
		  '. $navigation .'
		</div>
	  </div>';
	template_footer();
	
}

?>