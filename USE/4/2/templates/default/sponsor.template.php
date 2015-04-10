<?php
/**
 * onArcade 2.1.0
 * Copyright © 2006-2007 Hans Mдesalu & Eveterm OЬ, All Rights Reserved
 *
 * Template: onArcade
 **/


// Browse: here are all the files displayed
function template_sponsor() {
	global $settings, $lang, $files;

	template_header();
	echo '
      <div class="content_box_header">
        ', $lang['sponsor'] ,'
      </div>
      <div class="content_box">
	    ', $lang['sponsor_text'] ,'
	    <form action="https://www.paypal.com/cgi-bin/webscr" method="post" onsubmit="return verify_sponsor();">
	      <input type="hidden" name="cmd" value="_xclick" />
	      <input type="hidden" name="business" value="', $settings['paypal_email'] ,'" />
	      <input type="hidden" name="item_name" value="', $lang['sponsor'] ,'" />
	      <div class="content_text_left" style="width: 15%;">', $lang['file'] ,'</div>
	      <div class="content_text_right" style="width: 85%;">
	        ', $files ,'
	      </div>
	      <div style="clear: both;"></div>
	      <input type="hidden" name="amount" value="', $settings['sponsor_price'] ,'" />
	      <input type="hidden" name="no_shipping" value="1" />
	      <input type="hidden" name="return" value="', $settings['siteurl'] ,'/" />
	      <input type="hidden" name="notify_url" value="', $settings['siteurl'] ,'/sponsor.php?a=callback" />
	      <input type="hidden" name="no_note" value="1" />
	      <input type="hidden" name="currency_code" value="USD" />
	      <input type="hidden" name="lc" value="US" />
	      <input type="hidden" name="bn" value="PP-BuyNowBF" />
	      <div class="content_text_left" style="width: 15%;">', $lang['price'] ,'</div>
	      <div class="content_text_right" style="width: 85%;">$', $settings['sponsor_price'] ,'</div>
	      <div style="clear: both;"></div>
	      <div class="content_text_left" style="width: 15%;"><input type="hidden" name="on0" value="Link text" />', $lang['link_text'] ,'</div>
	      <div class="content_text_right" style="width: 85%;"><input type="text" name="os0" id="os0" maxlength="60" /></div>
	      <div style="clear: both;"></div>
	      <div class="content_text_left" style="width: 15%;"><input type="hidden" name="on1" value="URL" />', $lang['url'] ,'</div>
	      <div class="content_text_right" style="width: 85%;"><input type="text" name="os1" id="os1" maxlength="100" value="http://" /></div>
	      <div style="clear: both;"></div>
	      <div style="text-align: center;"><input type="image" src="https://www.paypal.com/en_US/i/btn/x-click-but01.gif" border="0" name="submit" alt="Make payments with PayPal - it\'s fast, free and secure!" /></div>
	    </form>
	  </div>';
	template_footer();

}

?>