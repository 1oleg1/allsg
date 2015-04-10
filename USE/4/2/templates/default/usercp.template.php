<?php
/**
 * onArcade 2.0.0
 * Copyright © 2006-2007 Hans Mдesalu & Eveterm OЬ, All Rights Reserved
 *
 * Template: Dark
 **/

// Menu: User CP menu
function template_cp_menu() {
	global $settings, $lang;
	
	echo '
      <div class="content_box_header">
        '. $lang['control_panel'] .'
      </div>
      <div class="content_box">
	    <a href="'. $settings['siteurl'] .'/usercp.php">'. $lang['cp_home'] .'</a><br />
		<a href="'. $settings['siteurl'] .'/usercp.php?a=profile">'. $lang['edit_profile'] .'</a><br />
		<a href="'. $settings['siteurl'] .'/usercp.php?a=options">'. $lang['edit_options'] .'</a><br />
		<a href="'. $settings['siteurl'] .'/usercp.php?a=emailpassword">'. $lang['edit_email_password'] .'</a><br />
		<a href="'. $settings['siteurl'] .'/usercp.php?a=avatar">'. $lang['edit_avatar'] .'</a><br />
		<a href="'. $settings['siteurl'] .'/privatemessages.php">'. $lang['private_messages'] .'</a>
	  </div>';

}


// Main: main page of User CP
function template_cp_main() {
	global $settings, $lang, $user;
	
	template_header();
	echo '
	  <div style="float: left; width: 79.5%;">
        <div class="content_box_header">
          '. $lang['welcome_username'] .'
        </div>
        <div class="content_box">
	      <div style="float: left; width: 25%; text-align: center;">
		    <img src="'. $user['avatar'] .'" title="'. $lang['avatar'] .'" alt="'. $lang['avatar'] .'" border="0" />
		  </div>
		  <div style="float: right; width: 75%;">
		    '. $lang['email'] .' '. $user['email'] .'<br />
		    '. $lang['total_played'] .' '. number_format($user['played']) .'<br />
			'. $lang['total_comments'] .' '. number_format($user['comments']) .'<br />
			'. $lang['date_joined'] .' '. mod_date($user['joined']) .'<br />
		  </div><div style="clear: both;"></div>
	    </div>
	  </div>
	  <div style="float: right; width: 20%;">';
	template_cp_menu();
	echo '
	  </div>
	  <div style="clear: both;"></div>';
	template_footer();

}

// Profile: edit profile
function template_cp_profile() {
	global $settings, $lang, $user;

	template_header();
	echo '
	  <div style="float: left; width: 79.5%;">
        <div class="content_box_header">
          '. $lang['edit_profile'] .'
        </div>
        <div class="content_box">
	      <form action="" method="post">
		    <div class="content_text_left" style="width: 20%;">
		      '. $lang['cp_location'] .'
		    </div>
		    <div class="content_text_right" style="width: 80%;">
		      <input type="text" name="location" maxlength="25" size="20" value="'. $user['location'] .'" />
		    </div>
		    <div style="clear: both;"></div>
		    <div class="content_text_left" style="width: 20%;">
		      '. $lang['cp_website'] .'
		    </div>
		    <div class="content_text_right" style="width: 80%;">
		      <input type="text" name="website" maxlength="50" size="20" value="'. $user['website'] .'" />
		    </div>
		    <div style="clear: both;"></div>
		    <div class="content_text_left" style="width: 20%;">
		      '. $lang['cp_gender'] .'
		    </div>
		    <div class="content_text_right" style="width: 80%;">
		      <select name="gender">
		        <option value="0" '. ($user['gender'] == '0' ? 'selected' : '') .'>'. $lang['cp_unspecified'] .'</option>
			    <option value="1" '. ($user['gender'] == '1' ? 'selected' : '') .'>'. $lang['cp_male'] .'</option>
			    <option value="2" '. ($user['gender'] == '2' ? 'selected' : '') .'>'. $lang['cp_female'] .'</option>
		      </select>
		    </div>
		    <div style="clear: both;"></div>
		    <div class="content_text_left" style="width: 20%;">
		      '. $lang['cp_msn'] .'
		    </div>
		    <div class="content_text_right" style="width: 80%;">
		      <input type="text" name="msn" maxlength="50" size="20" value="'. $user['msn'] .'" />
		    </div>
		    <div style="clear: both;"></div>
		    <div class="content_text_left" style="width: 20%;">
		      '. $lang['cp_aim'] .'
		    </div>
		    <div class="content_text_right" style="width: 80%;">
		      <input type="text" name="aim" maxlength="50" size="20" value="'. $user['aim'] .'" />
		    </div>
		    <div style="clear: both;"></div>
		    <div class="content_text_left" style="width: 20%;">
		      '. $lang['cp_skype'] .'
		    </div>
		    <div class="content_text_right" style="width: 80%;">
		      <input type="text" name="skype" maxlength="50" size="20" value="'. $user['skype'] .'" />
		    </div>
		    <div style="clear: both;"></div>
		    <div class="content_text_left" style="width: 20%;">
		      '. $lang['cp_yahoo'] .'
		    </div>
		    <div class="content_text_right" style="width: 80%;">
		      <input type="text" name="yahoo" maxlength="50" size="20" value="'. $user['yahoo'] .'" />
		    </div>
		    <div style="clear: both;"></div>
		    <div class="content_text_left" style="width: 20%;">
		      '. $lang['cp_icq'] .'
		    </div>
		    <div class="content_text_right" style="width: 80%;">
		      <input type="text" name="icq" maxlength="50" size="20" value="'. $user['icq'] .'" />
		    </div>
		    <div style="clear: both;"></div>
		    <div class="content_text_left" style="width: 20%;">
		      '. $lang['cp_google_talk'] .'
		    </div>
		    <div class="content_text_right" style="width: 80%;">
		      <input type="text" name="google_talk" maxlength="50" size="20" value="'. $user['google_talk'] .'" />
		    </div>
		    <div style="clear: both;"></div>
		    <div class="content_text_left" style="width: 20%;">
		      '. $lang['cp_show_ims_to'] .'
		    </div>
		    <div class="content_text_right" style="width: 80%;">
		      <select name="show_messenger">
		        <option value="0" '. ($user['show_messengers'] == '0' ? 'selected' : '') .'>'. $lang['cp_no_one'] .'</option>
			    <option value="1" '. ($user['show_messengers'] == '1' ? 'selected' : '') .'>'. $lang['cp_members'] .'</option>
			    <option value="2" '. ($user['show_messengers'] == '2' ? 'selected' : '') .'>'. $lang['cp_everyone'] .'</option>
		      </select>
		    </div>
		    <div style="clear: both;"></div>
		    <div align="center">
		      <input type="submit" name="edit_profile" value="'. $lang['edit'] .'" />
		    </div>
		  </form>
	    </div>
	  </div>
	  <div style="float: right; width: 20%;">';
	template_cp_menu();
	echo '
	  </div>
	  <div style="clear: both;"></div>';
	template_footer();

}

// Options: edit options
function template_cp_options() {
	global $settings, $lang, $user;
	
	template_header();
	echo '
	  <div style="float: left; width: 79.5%;">
        <div class="content_box_header">
          '. $lang['edit_options'] .'
        </div>
        <div class="content_box">
	      <form action="" method="post">
		    <div class="content_text_left" style="width: 20%;">
		      '. $lang['cp_template'] .'
		    </div>
		    <div class="content_text_right" style="width: 80%;">
		      '. $user['template_selector'] .'
		    </div>
		    <div style="clear: both;"></div>
		    <div class="content_text_left" style="width: 20%;">
		      '. $lang['cp_language'] .'
		    </div>
		    <div class="content_text_right" style="width: 80%;">
		      '. $user['language_selector'] .'
		    </div>
		    <div style="clear: both;"></div>
		    <div>
		      <input type="checkbox" name="receiveemails" value="1" '. ($user['receiveemails'] == '1' ? 'checked' : '') .' /> '. $lang['receive_emails_from_admins'] .'<br />
		      <input type="checkbox" name="email_pm" value="1" '. ($user['email_pm'] == '1' ? 'checked' : '') .' /> '. $lang['notify_email_new_pm'] .'
		    </div>
		    <div align="center">
		      <input type="submit" name="edit_options" value="'. $lang['edit'] .'" />
		    </div>
		  </form>
	    </div>
	  </div>
	  <div style="float: right; width: 20%;">';
	template_cp_menu();
	echo '
	  </div>
	  <div style="clear: both;"></div>';
	template_footer();

}

// Email & Password: edit email & password
function template_cp_email_password() {
	global $settings, $lang, $user;

	template_header();
	echo '
	  <div style="float: left; width: 79.5%;">';
	if (strlen($user['cp_error'])) {
		echo '
        <div class="error_box">
	      '. $user['cp_error'] .'
        </div>';
	}
	echo '
        <div class="content_box_header">
          '. $lang['edit_email_password'] .'
        </div>
        <div class="content_box">
	      <form action="" method="post">
		    <div class="content_text_left" style="width: 20%;">
		      '. $lang['current_password'] .'
		    </div>
		    <div class="content_text_right" style="width: 80%;">
		      <input type="password" name="current_password" maxlength="25" size="20" />
		    </div>
		    <div style="clear: both;"></div>
		    <div class="content_text_left" style="width: 20%;">
		      '. $lang['current_email'] .'
		    </div>
		    <div class="content_text_right" style="width: 80%;">
		      '. $user['email'] .'
		    </div>
		    <div style="clear: both;"></div><br />
		    <div class="content_text_left" style="width: 20%;">
		      '. $lang['new_password'] .'
		    </div>
		    <div class="content_text_right" style="width: 80%;">
		      <input type="password" name="new_password" maxlength="25" size="20" /> '. $lang['cp_optional'] .'
		    </div>
		    <div style="clear: both;"></div>
		    <div class="content_text_left" style="width: 20%;">
		      '. $lang['confirm_new_password'] .'
		    </div>
		    <div class="content_text_right" style="width: 80%;">
		      <input type="password" name="new_password_2" maxlength="25" size="20" />
		    </div>
		    <div style="clear: both;"></div><br />
		    <div class="content_text_left" style="width: 20%;">
		      '. $lang['new_email'] .'
		    </div>
		    <div class="content_text_right" style="width: 80%;">
		      <input type="text" name="new_email" maxlength="50" size="20" /> '. $lang['cp_optional'] .'
		    </div>
		    <div style="clear: both;"></div>
		    <div class="content_text_left" style="width: 20%;">
		      '. $lang['confirm_new_email'] .'
		    </div>
		    <div class="content_text_right" style="width: 80%;">
		      <input type="text" name="new_email_2" maxlength="50" size="20" />
		    </div>
		    <div style="clear: both;"></div>
		    <div align="center">
		      <input type="submit" name="edit_email_password" value="'. $lang['edit'] .'" />
		    </div>
		  </form>
	    </div>
	  </div>
	  <div style="float: right; width: 20%;">';
	template_cp_menu();
	echo '
	  </div>
	  <div style="clear: both;"></div>';
	template_footer();

}

// Avatar: edit avatar
function template_cp_avatar() {
	global $settings, $lang, $user;
	
	
	template_header();
	echo '
	  <div style="float: left; width: 79.5%;">';
	if (strlen($user['cp_error'])) {
		echo '
        <div class="error_box">
	      '. $user['cp_error'] .'
        </div>';
	}
	echo '
        <div class="content_box_header">
          '. $lang['edit_avatar'] .'
        </div>
        <div class="content_box">
	      <div>
		    <b>'. $lang['current_avatar'] .'</b><br />
		    <img src="'. $user['avatar'] .'" title="'. $lang['current_avatar'] .'" alt="'. $lang['current_avatar'] .'" border="0" />
		  </div>';
	if ($settings['avatar_gallery'] == '1') {
		echo '
		  <div class="content_text_left" style="width: 20%;">
		    '. $lang['avatar_galleries'] .'
		  </div>
		  <div class="content_text_right" style="width: 80%;">
		    <form action="'. $settings['siteurl'] .'/usercp.php?a=avatar_galleries" method="post">
			  '. $user['gallery_selector'] .'
			  <input type="submit" value="'. $lang['go_gallery_button'] .'" name="submit_gallery" />
			</form>
		  </div>
		  <div style="clear: both;"></div>';
	}
	echo '
		  <form action="" method="post" ENCTYPE="multipart/form-data">';
	if ($settings['remote_avatar'] == '1') {
		echo '
		    <div class="content_text_left" style="width: 20%;">
		      '. $lang['avatar_url'] .'
		    </div>
		    <div class="content_text_right" style="width: 80%;">
		      <input type="text" name="avatar_url" maxlength="100" size="30" />
		    </div>
		    <div style="clear: both;"></div>';
	}
	if ($settings['avatar_uploading'] == '1') {
		echo '
		    <div class="content_text_left" style="width: 20%;">
		      '. $lang['avatar_upload'] .'
		    </div>
		    <div class="content_text_right" style="width: 80%;">
		      <input name="avatar_upload" type="file" /> '. $lang['max_avatar_size_kb'] .'
		    </div>
		    <div style="clear: both;"></div>';
	}
	echo '
		    <div align="center">
		      <input type="submit" name="edit_avatar" value="'. $lang['edit'] .'" />
		    </div>
		  </form>
	    </div>
	  </div>
	  <div style="float: right; width: 20%;">';
	template_cp_menu();
	echo '
	  </div>
	  <div style="clear: both;"></div>';
	template_footer();

}

// Avatar Galleries: in onArcade v2 users can also select avatars from galleries. That is what that page is for.
function template_cp_avatar_galleries() {
	global $settings, $lang, $user, $gallery_images;
	
	template_header();
	echo '
	  <div style="float: left; width: 79.5%;">
        <div class="content_box_header">
          '. $lang['edit_avatar'] .'
        </div>
        <div class="content_box">
	      <form action="" method="post">
		    <div align="center">
		      '. $lang['choose_another_gallery'] .' 
			  '. $user['gallery_selector'] .'
			  <input type="submit" value="'. $lang['go_gallery_button'] .'" name="submit_gallery" />
		    </div>
		    <div style="margin-top: 10px; margin-bottom: 10px;">';
	foreach ($gallery_images as $image) {
		echo '
		      <div style="float: left; margin: 2px;"><img src="'. $settings['siteurl'] .'/images/avatars/galleries/'. $image .'" />
		        <p style="text-align: center; margin: 1px;"><input type="radio" name="avatar_selected" value="'. $image .'" /></p></div>';
	}
	echo '
		      <div style="clear: both;"></div>
		    </div>
			<div align="center">
		      <input type="submit" name="edit_avatar" value="'. $lang['select_avatar_button'] .'" />
		    </div>
		  </form>
	    </div>
	  </div>
	  <div style="float: right; width: 20%;">';
	template_cp_menu();
	echo '
	  </div>
	  <div style="clear: both;"></div>';
	template_footer();

}

?>