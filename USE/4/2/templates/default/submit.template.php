<?php
/**
 * onArcade 2.0.0
 * Copyright © 2006-2007 Hans Mдesalu & Eveterm OЬ, All Rights Reserved
 *
 * Template: Dark
 **/

// Browse: here are all the files displayed
function template_submit() {
	global $settings, $lang, $content;
	
	template_header();
	if (strlen($content['submit_error'])) {
    	echo '
	  <div class="error_box">
        '. $content['submit_error'] .'
	  </div>';
    }
	echo '
	  <div class="content_box_header">
        '. $lang['submit_content'] .'
      </div>
      <div class="content_box">
	    <form action="'. $settings['siteurl'] .'/submit.php" method="POST" name="submit_form" enctype="multipart/form-data"  onsubmit="return verify_submit_content();">
		  <div class="content_text_left" style="width: 20%;">'. $lang['submit_title'] .'</div>
		  <div class="content_text_right" style="width: 80%;"><input type="text" name="title" size="30" maxlength="255" value="'. $content['title'] .'" /></div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">'. $lang['submit_description'] .'</div>
		  <div class="content_text_right" style="width: 80%;"><input type="input" name="description" size="30" maxlength="255" value="'. $content['description'] .'" /></div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">'. $lang['submit_category'] .'</div>
		  <div class="content_text_right" style="width: 80%;">
		    '. $content['categories'] .'
		  </div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">'. $lang['submit_file'] .'</div>
		  <div class="content_text_right" style="width: 80%;"><input name="upload_file" type="file" /> '. $lang['max_size_kb'] .' ('. $content['valid_files'] .')</div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">'. $lang['submit_file_type'] .'</div>
		  <div class="content_text_right" style="width: 80%;">
		    '. $content['file_types'] .'
		  </div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">'. $lang['submit_image'] .'</div>
		  <div class="content_text_right" style="width: 80%;"><input name="upload_image" type="file" /> '. $lang['max_image_size_kb'] .' ('. $content['valid_images'] .')</div>
		  <div style="clear: both;"></div>';
	if ($settings['image_verification'] == '1') {
		echo '
		  <div class="content_text_left" style="width: 22%;">
			'. $lang['image_verification'] .'<br />
			<img src="'. $settings['siteurl'] .'/includes/verification_image.php" width="100" height="30" border="0" />
		  </div>
		  <div class="content_text_right" style="width: 78%;"><input type="input" name="verification_code" size="30" maxlength="4" /></div>
		  <div style="clear: both;"></div>';
	}
	echo '
		  <div align="center">
		    <input type="submit" name="submit_file" value="'. $lang['submit'] .'" />
		  </div>
		</form>
	  </div>';
	template_footer();
	
}

?>