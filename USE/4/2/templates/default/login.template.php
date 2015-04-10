<?php
/**
 * onArcade 2.0.0
 * Copyright © 2006-2007 Hans Mдesalu & Eveterm OЬ, All Rights Reserved
 *
 * Template: Dark
 */

// Log In: here we can log in
function template_login() {
	global $settings, $lang, $login_error;
	
	template_header();
	if (strlen($login_error)) {
		echo '
      <div class="error_box">
		'. $login_error .'
      </div>';
	}
	echo '
      <div class="content_box_header">
        '. $lang['log_in'] .'
      </div>
      <div class="content_box">
	    <form action="'. $settings['siteurl'] .'/login.php?a=login" method="post">
		  <div class="content_text_left" style="width: 15%;">'. $lang['username'] .'</div>
		  <div class="content_text_right" style="width: 85%;"><input type="text" name="username" maxlength="25" size="20" /></div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 15%;">'. $lang['password'] .'</div>
		  <div class="content_text_right" style="width: 85%;"><input type="password" name="password" maxlength="20" size="20" /></div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 15%;">'. $lang['remember'] .'</div>
		  <div class="content_text_right" style="width: 85%;"><input type="checkbox" name="remember" value="1" checked="checked" /></div>
		  <div style="clear: both;"></div>
		  <div class="content_text_right" style="width: 85%;">
		    <input type="submit" name="submit" value="'. $lang['log_in'] .'" /><br />
		    <a href="'. $settings['siteurl'] .'/login.php?a=lost_password">'. $lang['forgot_password'] .'</a>
		  </div>
		  <div style="clear: both;"></div>
		</form>
	  </div>';
	template_footer();
	
}

// Lost Password: here we can recover our password
function template_lost_password() {
	global $settings, $lang;
	
	// Include header
	template_header();
	echo '
      <div class="content_box_header">
        '. $lang['lost_password_recovery'] .'
      </div>
      <div class="content_box">
	    <form action="" method="post" name="recover_password">
		  '. $lang['you_have_forgotten_password'] .'<br />
		  '. $lang['lost_email'] .' <input type="text" name="recover_email" size="20" /><br />
		  <input type="submit" name="submit_email" value="'. $lang['submit'] .'" />
		</form>
	  </div>';
	// Include footer
	template_footer();
	
}

?>