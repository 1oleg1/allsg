<?php
/**
 * onArcade 2.0.0
 * Copyright © 2006-2007 Hans Mдesalu & Eveterm OЬ, All Rights Reserved
 *
 * Template: Dark
 **/

// Contact: here we can send emails
function template_contact() {
	global $settings, $lang, $contact;
	
	template_header();
	if (strlen($contact['error'])) {
		echo '
      <div class="error_box">
		'. $contact['error'] .'
	  </div>';
	}
	echo '
      <div class="content_box_header">
        '. $lang['contact_us'] .'
      </div>
      <div class="content_box">
	    <form action="'. $settings['siteurl'] .'/contact.php" method="POST" name="form" onsubmit="return verify_contact();">
		  <div class="content_text_left" style="width: 15%;">'. $lang['contact_name'] .'</div>
		  <div class="content_text_right" style="width: 85%;"><input type="text" name="sendername" size="30" maxlength="255" value="'. $contact['name'] .'" /></div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 15%;">'. $lang['contact_email'] .'</div>
		  <div class="content_text_right" style="width: 85%;"><input type="input" name="senderemail" size="30" maxlength="255" value="'. $contact['email'] .'" /></div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 15%;">'. $lang['contact_subject'] .'</div>
		  <div class="content_text_right" style="width: 85%;"><input type="input" name="messagesubject" size="30" maxlength="255" value="'. $contact['subject'] .'" /></div>
		  <div style="clear: both;"></div>
		  <div>
			<b>'. $lang['contact_message'] .'</b><br />
		    <textarea name="message" rows="4" cols="42">'. $contact['message'] .'</textarea>
		  </div>';
	if ($settings['image_verification'] == '1') {
		echo '
		  <div class="content_text_left" style="width: 22%;">
			'. $lang['image_verification'] .'<br />
			<img src="'. $settings['siteurl'] .'/includes/verification_image.php" width="100" height="30" border="0" />
		  </div>
		  <div class="content_text_right" style="width: 78%;"><input type="input" name="verification_code" size="30" maxlength="4" value="'. $contact['verification_code'] .'" /></div>
		  <div style="clear: both;"></div>';
	}
	echo '
		  <div align="center"><input type="submit" name="send" value="'. $lang['send'] .'" /></div>
		</form>
	  </div>';
	template_footer();
	
}

?>