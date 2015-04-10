<?php
/**
 * onArcade 2.1.0
 * Copyright © 2006-2007 Hans Mдesalu & Eveterm OЬ, All Rights Reserved
 **/

session_start();

require ('../includes/adminconfig.php');

// Redirect non-admins
if ($user['status'] != '1' || $user['group'] != '2') {
	header ('Location: login.php');
	exit();
}

function update_setting() {
	global $settings;
	// Write new settings file
	$new_settings = '<?php
/**
 * onArcade 2.1.0
 * Copyright © 2006-2007 Hans Mдesalu & Eveterm OЬ, All Rights Reserved
 **/
	$settings[\'siteonline\'] = \''. $settings['siteonline'] .'\';
	$settings[\'siteurl\'] = \''. $settings['siteurl'] .'\';
	$settings[\'sitename\'] = \''. $settings['sitename'] .'\';
	$settings[\'sitedescription\'] = \''. $settings['sitedescription'] .'\';
	$settings[\'sitekeywords\'] = \''. $settings['sitekeywords'] .'\';
	$settings[\'sitecontactemail\'] = \''. $settings['sitecontactemail'] .'\';
	$settings[\'filesdir\'] = \''. $settings['filesdir'] .'\';
	$settings[\'template\'] = \''. $settings['template'] .'\';
	$settings[\'language\'] = \''. $settings['language'] .'\';
	$settings[\'sefriendly\'] = \''. $settings['sefriendly'] .'\';
	$settings[\'banned_ip\'] = \''. $settings['banned_ip'] .'\';
	$settings[\'categoryurl\'] = \''. $settings['categoryurl'] .'\';
	$settings[\'fileurl\'] = \''. $settings['fileurl'] .'\';
	$settings[\'profileurl\'] = \''. $settings['profileurl'] .'\';
	$settings[\'scoresurl\'] = \''. $settings['scoresurl'] .'\';
	$settings[\'image_verification\'] = \''. $settings['image_verification'] .'\';
	$settings[\'memberlogin\'] = \''. $settings['memberlogin'] .'\';
	$settings[\'email_confirmation\'] = \''. $settings['email_confirmation'] .'\';
	$settings[\'guestcredits\'] = \''. $settings['guestcredits'] .'\';
	$settings[\'maxguestplays\'] = \''. $settings['maxguestplays'] .'\';
	$settings[\'failed_login_quota\'] = \''. $settings['failed_login_quota'] .'\';
	$settings[\'max_files_index\'] = \''. $settings['max_files_index'] .'\';
	$settings[\'stars_index\'] = \''. $settings['stars_index'] .'\';
	$settings[\'image_width\'] = \''. $settings['image_width'] .'\';
	$settings[\'image_height\'] = \''. $settings['image_height'] .'\';
	$settings[\'news\'] = \''. $settings['news'] .'\';
	$settings[\'news_index\'] = \''. $settings['news_index'] .'\';
	$settings[\'max_file_width\'] = \''. $settings['max_file_width'] .'\';
	$settings[\'max_file_height\'] = \''. $settings['max_file_height'] .'\';
	$settings[\'auto_resize\'] = \''. $settings['auto_resize'] .'\';
	$settings[\'tellfriend\'] = \''. $settings['tellfriend'] .'\';
	$settings[\'report_broken\'] = \''. $settings['report_broken'] .'\';
	$settings[\'comments\'] = \''. $settings['comments'] .'\';
	$settings[\'comments_who\'] = \''. $settings['comments_who'] .'\';
	$settings[\'comments_approval\'] = \''. $settings['comments_approval'] .'\';
	$settings[\'max_comments\'] = \''. $settings['max_comments'] .'\';
	$settings[\'comments_flood_time\'] = \''. $settings['comments_flood_time'] .'\';
	$settings[\'comments_banned_ip\'] = \''. $settings['comments_banned_ip'] .'\';
	$settings[\'bad_word_filter\'] = \''. $settings['bad_word_filter'] .'\';
	$settings[\'bad_words\'] = \''. $settings['bad_words'] .'\';
	$settings[\'added_by\'] = \''. $settings['added_by'] .'\';
	$settings[\'rate\'] = \''. $settings['rate'] .'\';
	$settings[\'add_to_website\'] = \''. $settings['add_to_website'] .'\';
	$settings[\'related_files\'] = \''. $settings['related_files'] .'\';
	$settings[\'max_related_files\'] = \''. $settings['max_related_files'] .'\';
	$settings[\'browse_per_page\'] = \''. $settings['browse_per_page'] .'\';
	$settings[\'remote_avatar\'] = \''. $settings['remote_avatar'] .'\';
	$settings[\'avatar_uploading\'] = \''. $settings['avatar_uploading'] .'\';
	$settings[\'avatar_size\'] = \''. $settings['avatar_size'] .'\';
	$settings[\'avatar_width\'] = \''. $settings['avatar_width'] .'\';
	$settings[\'avatar_height\'] = \''. $settings['avatar_height'] .'\';
	$settings[\'avatar_gallery\'] = \''. $settings['avatar_gallery'] .'\';
	$settings[\'submit\'] = \''. $settings['submit'] .'\';
	$settings[\'submit_file_size\'] = \''. $settings['submit_file_size'] .'\';
	$settings[\'submit_image_size\'] = \''. $settings['submit_image_size'] .'\';
	$settings[\'submit_valid_file\'] = \''. $settings['submit_valid_file'] .'\';
	$settings[\'submit_valid_image\'] = \''. $settings['submit_valid_image'] .'\';
	$settings[\'game_slave\'] = \''. $settings['game_slave'] .'\';
	$settings[\'game_slave_games\'] = \''. $settings['game_slave_games'] .'\';
	$settings[\'member_list_per_page\'] = \''. $settings['member_list_per_page'] .'\';
	$settings[\'most_popular_list\'] = \''. $settings['most_popular_list'] .'\';
	$settings[\'max_most_popular\'] = \''. $settings['max_most_popular'] .'\';
	$settings[\'newest_list\'] = \''. $settings['newest_list'] .'\';
	$settings[\'max_newest\'] = \''. $settings['max_newest'] .'\';
	$settings[\'top_players_list\'] = \''. $settings['top_players_list'] .'\';
	$settings[\'max_top_players\'] = \''. $settings['max_top_players'] .'\';
	$settings[\'links\'] = \''. $settings['links'] .'\';
	$settings[\'max_links\'] = \''. $settings['max_links'] .'\';
	$settings[\'header_ad\'] = \''. $settings['header_ad'] .'\';
	$settings[\'footer_ad\'] = \''. $settings['footer_ad'] .'\';
	$settings[\'file_ad\'] = \''. $settings['file_ad'] .'\';
	$settings[\'before_file_ad\'] = \''. $settings['before_file_ad'] .'\';
	$settings[\'cheater_protection\'] = \''. $settings['cheater_protection'] .'\';
	$settings[\'cache\'] = \''. $settings['cache'] .'\';
	$settings[\'cache_expire\'] = \''. $settings['cache_expire'] .'\';
	$settings[\'sponsor\'] = \''. $settings['sponsor'] .'\';
	$settings[\'sponsor_price\'] = \''. $settings['sponsor_price'] .'\';
	$settings[\'paypal_email\'] = \''. $settings['paypal_email'] .'\';
	$settings[\'copy\'] = \''. $settings['copy'] .'\';
?>';
	
	$open_settings_file = fopen('../includes/settings.php', 'w');
	if ($open_settings_file) {
		fwrite($open_settings_file, $new_settings);
	    fclose($open_settings_file);
	    
	    return true;
	} else {
		return false;
	}
}

$admincp_action = $_GET['a'];

if ($admincp_action == 'file_settings') {
	if (isset($_POST['submit_settings'])) {
		// Update settings
		$settings['filesdir'] = $_POST['files_dir'];
		$settings['fileurl'] = $_POST['file_url'];
		$settings['scoresurl'] = $_POST['scores_url'];
		$settings['categoryurl'] = $_POST['category_url'];
		$settings['max_files_index'] = (int) $_POST['max_files_index'];
		$settings['stars_index'] = (int) $_POST['stars_index'];
		$settings['image_width'] = (int) $_POST['image_width'];
		$settings['image_height'] = (int) $_POST['image_height'];
		$settings['max_file_width'] = (int) $_POST['max_file_width'];
		$settings['max_file_height'] = (int) $_POST['max_file_height'];
		$settings['auto_resize'] = (int) $_POST['auto_resize'];
		$settings['tellfriend'] = (int) $_POST['tell_friend'];
		$settings['report_broken'] = (int) $_POST['report_broken'];
		$settings['comments'] = (int) $_POST['comments'];
		$settings['comments_who'] = (int) $_POST['comment_who'];
		$settings['comments_approval'] = (int) $_POST['comment_approval'];
		$settings['max_comments'] = (int) $_POST['max_comments'];
		$settings['comments_flood_time'] = (int) $_POST['comments_flood_time'];
		$settings['comments_banned_ip'] = $_POST['comments_banned_ip'];
		$settings['bad_word_filter'] = (int) $_POST['bad_word_filter'];
		$settings['bad_words'] = $_POST['bad_words'];
		$settings['added_by'] = (int) $_POST['added_by'];
		$settings['rate'] = (int) $_POST['rate'];
		$settings['add_to_website'] = (int) $_POST['add_to_website'];
		$settings['related_files'] = (int) $_POST['related_files'];
		$settings['max_related_files'] = (int) $_POST['max_related_files'];
		$settings['browse_per_page'] = (int) $_POST['browse_per_page'];
		$settings['submit'] = (int) $_POST['submit_file'];
		$settings['submit_file_size'] = (int) $_POST['submit_file_size'];
		$settings['submit_image_size'] = (int) $_POST['submit_image_size'];
		$settings['submit_valid_file'] = $_POST['submit_valid_file'];
		$settings['submit_valid_image'] = $_POST['submit_valid_image'];
		$settings['game_slave'] = (int) $_POST['game_slave'];
		$settings['game_slave_games'] = (int) $_POST['game_slave_games'];
		$settings['most_popular_list'] = (int) $_POST['most_popular_list'];
		$settings['max_most_popular'] = (int) $_POST['max_most_popular'];
		$settings['newest_list'] = (int) $_POST['newest_list'];
		$settings['max_newest'] = (int) $_POST['max_newest'];
		$settings['file_ad'] = (int) $_POST['file_ad'];
		$settings['before_file_ad'] = (int) $_POST['before_file_ad'];
		$settings['cheater_protection'] = (int) $_POST['cheater_protection'];
		$settings['sponsor'] = (int) $_POST['sponsor'];
		$settings['sponsor_price'] = $_POST['sponsor_price'];
		$settings['paypal_email'] = $_POST['paypal_email'];
		
		if (update_setting() == TRUE) {
			// Redirect
			redirect_page($settings['siteurl'] .'/admin/configurations.php?a=file_settings', $lang['settings_updated']);
		} else {
			$settings_error = $lang['couldnt_update'];
		}
	}
	
	// Cannot write settings file
	if (!is_writable('../includes/settings.php')) {
		$settings_error = $lang['cannot_settings'];
	}
	
	$page_title = $lang['file_settings'];
	
	// Load template
	template_file_settings();
} elseif ($admincp_action == 'member_settings') {
	if (isset($_POST['submit_settings'])) {
		// Update settings
		$settings['memberlogin'] = (int) $_POST['member_login'];
		$settings['email_confirmation'] = (int) $_POST['email_confirmation'];
		$settings['guestcredits'] = (int) $_POST['guest_credits'];
		$settings['maxguestplays'] = (int) $_POST['max_guest_plays'];
		$settings['failed_login_quota'] = (int) $_POST['failed_login_quota'];
		$settings['banned_ip'] = $_POST['banned_ip'];
		$settings['profileurl'] = $_POST['profile_url'];
		$settings['remote_avatar'] = (int) $_POST['remote_avatar'];
		$settings['avatar_uploading'] = (int) $_POST['avatar_uploading'];
		$settings['avatar_size'] = (int) $_POST['avatar_size'];
		$settings['avatar_width'] = (int) $_POST['avatar_width'];
		$settings['avatar_height'] = (int) $_POST['avatar_height'];
		$settings['avatar_gallery'] = (int) $_POST['avatar_gallery'];
		$settings['member_list_per_page'] = (int) $_POST['member_list_per_page'];
		$settings['top_players_list'] = (int) $_POST['top_players_list'];
		$settings['max_top_players'] = (int) $_POST['max_top_players'];
		
		if (update_setting() == TRUE) {
			// Redirect
			redirect_page($settings['siteurl'] .'/admin/configurations.php?a=member_settings', $lang['settings_updated']);
		} else {
			$settings_error = $lang['couldnt_update'];
		}
	}
	
	// Cannot write settings file
	if (!is_writable('../includes/settings.php')) {
		$settings_error = $lang['cannot_settings'];
	}
	
	$page_title = $lang['member_settings'];
	
	// Load template
	template_member_settings();
} elseif ($admincp_action == 'templates') {
	if (isset($_GET['default_template']) && strlen($_GET['default_template']) && file_exists('../templates/'. $_GET['default_template'])) {
		// Update settings
		$settings['template'] = $_GET['default_template'];
		
		if (update_setting() == TRUE) {
			// Redirect
			redirect_page($settings['siteurl'] .'/admin/configurations.php?a=templates', $lang['settings_updated']);
		} else {
			$settings_error = $lang['couldnt_update'];
		}
	}

	// Cannot write settings file
	if (!is_writable('../includes/settings.php')) {
		$settings_error = $lang['cannot_settings'];
	}
	
	// Get templates
	if ($templates_directory = opendir('../templates/')) {
		while ($template_folders = readdir($templates_directory)) {
			if ($template_folders != '.' && $template_folders != '..' && $template_folders != 'index.html') {
				$templates[] = array (
					'folder'	=>	$template_folders,
					'title'		=>	ucwords(str_replace('_', ' ', $template_folders))
				);
			}
		}
		closedir($templates_directory);
	}
	
	$page_title = $lang['templates'];
	
	// Load template
	template_templates();
} elseif ($admincp_action == 'template_files') {
	// Get template files
	if ($template_directory = opendir('../templates/'. $_GET['t'] .'/')) {
		while ($template_files = readdir($template_directory)) {
			if (strstr($template_files, '.php')) {
				$files[] = array (
					'file'		=>	$template_files,
					'location'	=>	$_GET['t'] .'/'. $template_files,
					'write'		=>	(is_writable('../templates/'. $_GET['t'] .'/'. $template_files) ? TRUE : FALSE),
					'type'		=> 'php'
				);
			} elseif (strstr($template_files, '.css')) {
				$files[] = array (
					'file'		=>	$template_files,
					'location'	=>	$_GET['t'] .'/'. $template_files,
					'write'		=>	(is_writable('../templates/'. $_GET['t'] .'/'. $template_files) ? TRUE : FALSE),
					'type'		=> 'css'
				);
			}
		}
		closedir($template_directory);
	}
	
	$page_title = $lang['templates'];
	
	// Load template
	template_template_files();
} elseif ($admincp_action == 'edit_template_file') {
	if (isset($_POST['submit_save'])) {
		// Get new template file content
		$new_file_content = '';
		foreach ($_REQUEST['template_file_content'] as $template_part) {
			$new_file_content .= stripslashes($template_part);
		}
		
		$open_template_file = fopen('../templates/'. $_GET['f'], 'w');
		if ($open_template_file) {
			fwrite($open_template_file, $new_file_content);
	    	fclose($open_template_file);
	    
	    	// Redirect
			redirect_page($settings['siteurl'] .'/admin/configurations.php?a=edit_template_file&f='. $_GET['f'], $lang['file_updated']);
		} else {
			$settings_error = $lang['couldnt_update'];
		}
	}
	// Get file contents
	$file_content = nohtml(file('../templates/'. $_GET['f']));
	
	$j = 1;
	for ($i = 0, $n = count($file_content); $i < $n; $i++) {
		if (isset($file_content[$i + 1]) && substr($file_content[$i + 1], 0, 9) == 'function ') {
			$j++;
			$edit_template[$j]['header'] = trim($file_content[$i]);
		}
		$edit_template[$j]['code'] .= $file_content[$i];
	}
	
	$page_title = $lang['templates'];
	
	// Load template
	template_edit_template_file();
} else {
	if (isset($_POST['submit_settings'])) {
		// Update settings
		$settings['sitename'] = $_POST['site_title'];
		$settings['siteurl'] = $_POST['site_url'];
		$settings['sitedescription'] = $_POST['site_description'];
		$settings['sitekeywords'] = $_POST['site_keywords'];
		$settings['sitecontactemail'] = $_POST['contact_email'];
		$settings['siteonline'] = (int) $_POST['site_status'];
		$settings['template'] = $_POST['template'];
		$settings['language'] = $_POST['language'];
		$settings['sefriendly'] = (int) $_POST['se_friendly_urls'];
		$settings['categoryurl'] = $_POST['category_url'];
		$settings['fileurl'] = $_POST['file_url'];
		$settings['profileurl'] = $_POST['profile_url'];
		$settings['scoresurl'] = $_POST['scores_url'];
		$settings['image_verification'] = (int) $_POST['image_verification'];
		$settings['news'] = (int) $_POST['news'];
		$settings['news_index'] = (int) $_POST['news_index'];
		$settings['links'] = (int) $_POST['links'];
		$settings['max_links'] = (int) $_POST['max_links'];
		$settings['header_ad'] = (int) $_POST['header_ad'];
		$settings['footer_ad'] = (int) $_POST['footer_ad'];
		$settings['file_ad'] = (int) $_POST['file_ad'];
		$settings['before_file_ad'] = (int) $_POST['before_file_ad'];
		$settings['cache'] = (int) $_POST['cache'];
		$settings['cache_expire'] = (int) $_POST['cache_expire'];
		$settings['copy'] = (int) $_POST['copy'];
		
		if (update_setting() == TRUE) {
			// Redirect
			redirect_page($settings['siteurl'] .'/admin/configurations.php', $lang['settings_updated']);
		} else {
			$general_setting['error'] = $lang['couldnt_update'];
		}
	}
	
	// Create template selector
	$general_setting['template_selector'] = '
<select name="template">';
	if ($templates_directory = opendir('../templates/')) {
		while ($templates = readdir($templates_directory)) {
			if ($templates != '.' && $templates != '..' && $templates != 'index.html') {
				if($templates == $settings['template']){
					$general_setting['template_selector'] .= '
  <option value="'. $templates .'" selected>'. ucwords(str_replace('_', ' ', $templates)) .'</option>';
				} else {
					$general_setting['template_selector'] .= '
  <option value="'. $templates .'">'. ucwords(str_replace('_', ' ', $templates)) .'</option>';
				}
			}
		}
	closedir($templates_directory);
	}
	$general_setting['template_selector'] .= '
</select>';

	// Create language selector
	$general_setting['language_selector'] = '
<select name="language">';
	if ($languages_directory = opendir('../languages/')) {
		while ($languages = readdir($languages_directory)) {
			if ($languages != '.' && $languages != '..' && $languages != 'index.html') {
				if($languages == $settings['language']){
					$general_setting['language_selector'] .= '
  <option value="'. $languages .'" selected>'. ucwords($languages) .'</option>';
				} else {
					$general_setting['language_selector'] .= '
  <option value="'. $languages .'">'. ucwords($languages) .'</option>';
				}
			}
		}
	closedir($languages_directory);
	}
	$general_setting['language_selector'] .= '
</select>';

	// Cannot write settings file
	if (!is_writable('../includes/settings.php'))
		$general_setting['error'] = $lang['cannot_settings'];
	
	$page_title = $lang['general_settings'];
	
	// Load template
	template_general_settings();
}

?>