<?php
/**
 * onArcade 2.0.0
 * Copyright © 2006-2007 Hans Mдesalu & Eveterm OЬ, All Rights Reserved
 *
 * Template: Dark
 **/


// Links: links page
function template_links() {
	global $settings, $lang, $links;
	
	template_header();
	echo '
      <div class="content_box_header">
        '. $lang['links'] .'
      </div>
      <div class="content_box">';
	foreach ($links as $link) {
		echo '
        <a href = "'. $link['url'] .'" onclick="return link_out('. $link['id'] .');">'. $link['title'] .'</a> - '. $link['description'] .'<br />';
	}
	echo '
		<br /><div align="center"><a href="'. $settings['siteurl'] .'/links.php?a=add"><b>'. $lang['add_link'] .'</b></a></div>
	  </div>';
	template_footer();
	
}

// Add Link: here we can add links
function template_add_link() {
	global $settings, $lang, $link;

	template_header();
	if (strlen($link['error'])) {
    	echo '
      <div class="error_box">
        '. $link['error'] .'
      </div>';
    }
	echo '
      <div class="content_box_header">
        '. $lang['links'] .'
      </div>
      <div class="content_box">
        <form action="" method="POST" name="form" onsubmit="return verify_link_add();">
		  <div class="content_text_left" style="width: 20%;">'. $lang['title'] .'</div>
		  <div class="content_text_right" style="width: 80%;"><input type="text" name="link_title" size="30" maxlength="255" /></div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">'. $lang['link_url'] .'</div>
		  <div class="content_text_right" style="width: 80%;"><input type="input" name="link_url" size="30" maxlength="255" value="http://" /></div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">'. $lang['description'] .'</div>
		  <div class="content_text_right" style="width: 80%;"><input type="input" name="link_description" size="30" maxlength="255" /></div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">'. $lang['link_email'] .'</div>
		  <div class="content_text_right" style="width: 80%;"><input type="input" name="link_email" size="30" maxlength="255" /></div>
		  <div style="clear: both;"></div>';
	if ($settings['image_verification'] == '1') {
	echo '
		  <div class="content_text_left" style="width: 22%;">
			'. $lang['image_verification'] .'
			<img src="'. $settings['siteurl'] .'/includes/verification_image.php" width="100" height="30" border="0" />
		  </div>
		  <div class="content_text_right" style="width: 78%;"><input type="input" name="verification_code" size="30" maxlength="4" /></div>
		  <div style="clear: both;"></div>';
	}
	echo '
		  <div align="center"><input type="submit" name="submit" value="'. $lang['submit'] .'" /></div>
		</form>
	  </div>';
	template_footer();

}

?>