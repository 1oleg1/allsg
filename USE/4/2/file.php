<?php
session_start();
require ('includes/config.php');
//require ('templates/'. $settings['template'] .'/file.template.php');
require ('languages/'. $settings['language'] .'/file.lang.php');

$file_id = (int) $_GET['f'];

if (!is_numeric($file_id) && strlen($file_id))
	die('Bad hacker!!!');

switch ($_GET['a']) {
// рейтинг файла    
//case 'rate':
//	if ($settings['rate'] == '1' || $settings['rate'] == '2' && $user['status'] == '1') {
//		$file_rating = $_GET['r'];
//		if (strlen($file_rating) == 1 && is_numeric($file_rating) && $file_rating >= 1 && $file_rating <= 5) {
//			$session_file = $_SESSION['rate_file'];
//			// Let's try to stop people from voting more than once
//			if ($session_file != $file_id) {
//				// And let's rate
//				$update_rating_query = mysql_query("UPDATE on_files 
//                SET totalvotes = totalvotes + 1, 
//                totalvotepoints = totalvotepoints + '". $file_rating ."', 
//                rating = totalvotepoints / totalvotes 
//                WHERE fileid = '". $file_id ."' LIMIT 1");
//				$_SESSION['rate_file'] = $file_id;
//			}
//		}
//		// Get the new value of rating
//		$file_rating_query = mysql_query("SELECT rating 
//                                          FROM on_files 
//                                          WHERE fileid = '". $file_id ."' && status = '1' LIMIT 1");
//		$file_rating_row = mysql_fetch_assoc($file_rating_query);
//				
//		echo stars($file_rating_row['rating']);
//	}
//	break;
//---------------------------------------------------------------------------
case 'report_broken':
	if ($settings['report_broken'] == '0' || empty($file_id)) {
		exit();
	}
	
	$report_comment = nohtml($_POST['comment']);
	if (strlen($report_comment) > '100') {
	    $report_comment = substr($report_comment , 0, 100);
    }
    
    // Use sessions make sure that report isn't submited more than once
    $session_file = $_SESSION['report_file'];
	if ($session_file != $file_id) {
		// Insert report to database
		$report_add_query = mysql_query("INSERT INTO on_report_broken 
                                         SET file_id = '". $file_id ."', comment = '". $report_comment ."', 
                                         ip = '". $user['ip'] ."', date_reported = '". time() ."'");
		$_SESSION['report_file'] = $file_id;
	}
    
	echo $lang['thanks_for_reporting'];
	break;
//---------------------------------------------------------------------------    
case 'tellafriend':
	if ($settings['tellfriend'] == '0') {
		die('Tell a friend offline!!!');
	}
	$file_query = mysql_query("SELECT title 
                               FROM on_files 
                               WHERE fileid = '". $file_id ."' LIMIT 1");
	
	if (mysql_num_rows($file_query) == 0)
		no_page();
	
	$file_row = mysql_fetch_assoc($file_query);

	$file = array (
		'id'	=>	$file_id,
		'title'	=>	$file_row['title']

	);
	
	// Send email to friend
	if (isset($_POST['submit'])) {
		$tf_yourname = $_POST['yourname'];
		$tf_youremail = $_POST['youremail'];
		$tf_friendemail = $_POST['friendemail'];
		$tf_verification_code = strtoupper($_POST['verification_code']);
		// check image verification code
		if ($settings['image_verification'] == '1') {
			$verification_check_query = mysql_query("SELECT verification_code 
                                                     FROM on_online 
                                                     WHERE ip = '". $user['ip'] ."' && 
                                                     verification_code = '". $tf_verification_code ."'");
			$verification_rows_number = mysql_num_rows($verification_check_query);
		}
		
		if ($verification_rows_number == '0' && $settings['image_verification'] == '1' || empty($tf_verification_code) && $settings['image_verification'] == '1') {
			$file['error'] = $lang['invalid_verification_code'];
		} else {
			if (strlen($tf_yourname) && strlen($tf_youremail) && strlen($tf_friendemail)) {
				if (preg_match(' /[\r\n,;\'"]/ ', $tf_youremail) || preg_match(' /[\r\n,;\'"]/ ', $tf_friendemail)) {
					$file['error'] = $lang['invalid_email'];
        		} else {
        			$session_friend_email = $_SESSION['session_friend_email'];
        			if ($session_friend_email != $tf_friendemail) {
        				$email_header = 'Return-Path: '. $tf_youremail .'
From: '. $tf_yourname .' <'. $tf_youremail .'>
MIME-Version: 1.0
Content-type: text/plain';

       					// Lets send email to friend
						include ('languages/'. $settings['language'] .'/email.lang.php');
						@mail($tf_friendemail, $lang['check_out'], $lang['tell_a_friend_message'], $email_header);
						$_SESSION['session_friend_email'] = $tf_friendemail;
					}
				
// Tell a Friend2: this is where your members can tell their friends about your site                    
					//template_tellafriend2();
	global $settings, $lang, $file, $user;	
	template_overall_header();
	echo '
    <body>
    <div class="popup_box">
    '. $lang['file_sent_friend'] .'<br />
    <a href="javascript:window.close()">'. $lang['close'] .'</a>
    </div>
    </body>
    </html>';                                       
					exit();
        		}
			} else {
				$file['error'] = $lang['all_fields_required'];
			}
		}
	}

	$page_title = $lang['tell_a_friend'];
	
	// Load template
// Tell a Friend: this is where your members can tell their friends about your site    
//	template_tellafriend();
	global $settings, $lang, $file, $user;	
	template_overall_header();
	echo '
<body>
<div class="popup_box">
'. $lang['fill_out_this_form'] .' <b>'. $file['title'] .'</b>.<br />';
	if (strlen($file['error'])) {
		echo '<b>'. $file['error'] .'</b>';
	}
	echo '
<br />
<form action="'. $settings['siteurl'] .'/file.php?f='. $file['id'] .'&a=tellafriend" method="post">
  '. $lang['your_name'] .'<br />
  <input name="yourname" type="text" value="'. $user['username'] .'" size="30" maxlength="50" /><br />
  '. $lang['your_email_address'] .'<br />
  <input name="youremail" type="text" value="'. $user['email'] .'" size="30" maxlength="50" /><br />
  '. $lang['your_friends_email_address'] .'<br />
  <input name="friendemail" type="text" size="30" maxlength="50" /><br />
  '. $lang['image_verification'] .'<br />
  <img src="'. $settings['siteurl'] .'/includes/verification_image.php" width="100" height="30" border="0" /><br />
  <input type="input" name="verification_code" size="30" maxlength="4" /><br />
  <input name="submit" type="submit" value="'. $lang['submit'] .'" />
</form>
</div>
</body>
</html>';
//-----------------------------------------------------------------------

	break;
//---------------------------------------------------------------------------    
case 'comments':
	if ($settings['comments'] == '0') {
		exit();
	}
	
	// Comments page number
	$comments_page = $_GET['p'];
	if (empty($comments_page) || !is_numeric($comments_page) || $comments_page == 0) {
		$comments_page = 1;
	}
	$comments_number_query = mysql_query("SELECT count(*) FROM on_comments 
                                          WHERE fileid = '". $file_id ."' && status = '1'");
	$comments_number_row = mysql_fetch_assoc($comments_number_query);
	$pages_count = ceil($comments_number_row['count(fileid)'] / $settings['max_comments']);
	$navigation = NULL;
	for ($pagen = 1; $pagen <= $pages_count; $pagen++) {
		if ($pagen == $comments_page) {
			$navigation .= ' <b>'. $pagen .'</b>';
        } else {
			$navigation .= ' <a href="" onclick="display_comments('. $file_id .', '. $pagen .'); return false;">'. $pagen .'</a>';

	    } 
    }
	$start_here = ($comments_page - 1) * $settings['max_comments'];
	
	$comments_query = mysql_query("SELECT * FROM on_comments 
                                   WHERE fileid = '". $file_id ."' && status = '1' 
                                   ORDER BY commentid DESC 
                                   LIMIT ". $start_here .", ". $settings['max_comments']);
	
	while ($comments_row = mysql_fetch_assoc($comments_query)) {
		if ($comments_row['userid'] == '0' || empty($comments_row['username'])) {
			$poster_username = $lang['guest'];
		} else {
			$poster_username = '<a href="'. profileurl($comments_row['userid'], $comments_row['username']) .'">'. $comments_row['username'] .'</a>';
		}
			
		$comments[] = array (
			'comment'	=>	word_filter(bbcode(nl2br(nohtml($comments_row['comment'])))),
			'date'		=>	mod_date($comments_row['dateadded']),
			'user'		=>	$poster_username
		);
	}
	
	// Load template
//	template_display_comments();


	global $comments, $navigation;
	
	if (is_array($comments)) {
		echo '
	      <div class="pagination">
			'. $navigation .'
	      </div>';
		foreach ($comments as $comment) {
			echo '
	      <div class="comment_box">
			<b>'. $comment['user'] .'</b> ('. $comment['date'] .'):<br />
			'. $comment['comment'] .'
	      </div>';
		}
		echo '
	      <div class="pagination">
			'. $navigation .'
	      </div>';
	}






	break;
//---------------------------------------------------------------------------    
case 'make_favourite':
	// Do nothing if user not logged in
	if ($user['status'] != '1') {
		exit();
	}
		
	$user_favourites = NULL;
	if (strlen($user_row['favourites'])) {
		$user_favourites = unserialize($user_row['favourites']);
		
		$user_favourites[$file_id] = $file_id;
		$update_favourite_query = mysql_query("UPDATE on_users 
                        SET favourites = '". serialize($user_favourites) ."' 
                        WHERE userid = '". $user['id'] ."' LIMIT 1");
	} else {
		$user_favourites[$file_id] = $file_id;
		$update_favourite_query = mysql_query("UPDATE on_users 
        SET favourites = '". serialize($user_favourites) ."' 
        WHERE userid = '". $user['id'] ."' LIMIT 1");
	}
	
	echo $lang['file_added_favourites'];
	break;
//---------------------------------------------------------------------------    
case 'remove_favourite':
	// Do nothing if user not logged in
	if ($user['status'] != '1') {
		exit();
	}
		
	$user_favourites = NULL;
	if (strlen($user_row['favourites'])) {
		$user_favourites = unserialize($user_row['favourites']);
		
		unset($user_favourites[$file_id]);
			
		$update_favourite_query = mysql_query("UPDATE on_users 
        SET favourites = '". serialize($user_favourites) ."' 
        WHERE userid = '". $user['id'] ."' LIMIT 1");
	}
	
	echo $lang['file_removed_favourites'];
	break;
//---------------------------------------------------------------------------    
case 'popup':
	if ($user['plays_left'] <= 0 && $user['status'] == 0 && $settings['guestcredits'] == 1) {
		$blank_page = array (
			'title'		=>	$settings['sitename'],
			'content'	=>	$lang['you_no_more_plays_left']
		);
		
		$page_title = $lang['you_no_more_plays_left'];
    
    	// Load template		
    	template_blank_page($blank_page);
		exit();
	}
	
	$file_query = mysql_query("
		SELECT
			file.title, file.file, file.filelocation, file.filetype, file.width, 
            file.height, cat.permissions, cat.status
		FROM
			on_files AS file
			LEFT JOIN on_categories AS cat ON (cat.catid = file.category)
		WHERE
			file.fileid = '". $file_id ."' && file.status = '1' LIMIT 1");
	
	$file_row = mysql_fetch_assoc($file_query);
	
	if (empty($file_row))
		no_page();
	
	// Some category related stuff
	if ($file_row['status'] == 0)
		no_page();
		
	if ($file_row['permissions'] == 2 && $user['status'] != '1')
		please_log_in();
	
	// Direct URL to file
	if ($file_row['filelocation'] == '1') {
		$file_url = $settings['siteurl'] .'/files/'. $settings['filesdir'] .'/'. $file_row['file'];
    } else {
	    $file_url = $file_row['file'];
    }
    
    // Load player for file
    $play_file = file_get_contents('includes/file_type/'. $file_row['filetype'] .'.php');
    
    // Replace variables
    $play_file = str_replace('{$width}', $file_row['width'], $play_file);
    $play_file = str_replace('{$height}', $file_row['height'], $play_file);
    $play_file = str_replace('{$file_url}', $file_url, $play_file);
    $play_file = str_replace('{$siteurl}', $settings['siteurl'], $play_file);

	$file = array (
		'play_file'	=>	$play_file
	);

	$page_title = $file_row['title'];
	
	// Load template
// File In New Window: this is the pop-up window users see when they open file in new window    
//	template_new_window();
	global $file;
	template_overall_header();
	echo '
<body>
'. $file['play_file'] .'
</body>
</html>';    
    
    
	break;
//-- добавляем комментарий -------------------------------------------------------------------------    
case 'submit_comment':
	if ($user['plays_left'] <= 0 && $user['status'] == 0 && $settings['guestcredits'] == 1 || $settings['comments'] == '0') {
		exit();
	}
	$file_query = mysql_query("SELECT fileid FROM on_files 
                               WHERE fileid = '". $file_id ."' 
                               && status = '1' LIMIT 1");
	if (mysql_num_rows($file_query) == 0) {
		exit();
	}
	
	// Add comment
	if ($settings['comments_who'] == '1' || $settings['comments_who'] == '2' && $user['status'] == '1') {
//		$comment_text = preg_replace('#%u([0-9A-F]{4})#se',
//                  'iconv("UTF-16BE","UTF-8",pack("H4","$1"))',($_POST['message']));
		$comment_text = $_POST['message'];
		if (empty($comment_text)) {
			echo 'Комментарий не должен быть пустым';
		} else {
			$banned_ips = explode(' ', $settings['comments_banned_ip']);
			if (in_array($user['ip'], $banned_ips)) {
					echo $lang['ip_has_been_banned'];
			} else {
				$last_comment_sql = mysql_query("SELECT dateadded FROM on_comments WHERE ip = '". $user['ip'] ."' ORDER BY commentid DESC LIMIT 1");
				$last_comment_row = mysql_fetch_assoc($last_comment_sql);
				$categoryname = $last_comment_row['dateadded'];
		
				// Flood protection
//				if (time() - $last_comment_row['dateadded'] > $settings['comments_flood_time']) {
					if ($settings['comments_approval'] == '0' || $settings['comments_approval'] == '1' && $user['status'] == '1') {
						$comment_error = $lang['comment_added'];
						$comment_query = mysql_query("INSERT INTO on_comments SET fileid = '". $file_id ."', userid = '". $user['id'] ."', username = '". $user['username'] ."', comment = '". $comment_text ."', ip = '". $user['ip'] ."', dateadded = '". time() ."', status = '1'");
					} else {
						$comment_error = $lang['comment_awaiting_approval'];
						$comment_query = mysql_query("INSERT INTO on_comments SET fileid = '". $file_id ."', userid = '". $user['id'] ."', username = '". $user['username'] ."', comment = '". $comment_text ."', ip = '". $user['ip'] ."', dateadded = '". time() ."', status = '0'");
					}
					// Update comments
					if ($user['status'] == '1') {
						$user['comments'] = $user['comments'] + 1;
						$update_user_comments = mysql_query("UPDATE on_users SET comments = '". $user['comments'] ."' WHERE userid = '". $user['id'] ."'");
					}
					echo ($comment_error);
//				} else {
//					echo ($lang['comment_flood_text']);
//				}
			}
		}
	}
	break;
//---------------------------------------------------------------------------    
default:
	if ($user['plays_left'] <= 0 && $user['status'] == 0 && $settings['guestcredits'] == '1') {
		$blank_page = array(
			'title'		=>	$settings['sitename'],
			'content'	=>	$lang['you_no_more_plays_left']
		);
		
		$page_title = $lang['you_no_more_plays_left'];
    
    	// Load template		
    	template_blank_page($blank_page);
		exit();
	}
	
	// Show ad before file
	if (($settings['before_file_ad'] == '1' || ($settings['before_file_ad'] == '2' && $user['status'] == 0)) && $_SESSION['ad_before'] != TRUE) {
		$ad_query = mysql_query("SELECT ad_code FROM on_ads 
                                 WHERE status = '1' && ad_zone = '4' 
                                 ORDER BY rand() LIMIT 1");
		$ad = mysql_fetch_assoc($ad_query);
		
		$blank_page = array(
			'title'		=>	$lang['sponsor'],
			'content'	=>	$ad['ad_code'] .'
<br /><br /><a href="" onclick="window.location.reload(true);">Click here if you do not wish to wait...</a>
<script type=text/javascript>
setTimeout("window.location.reload(true);", 10000);
</script>'
		);
		
		// Make it so that ad isn't showed too often
		$_SESSION['ad_before'] = TRUE;
    
    	// Load template		
    	template_blank_page($blank_page);
		exit();
	}
	
	// Get file information from database
	$file_sql = "
		SELECT
			file.*, cat.name AS category_name, cat.permissions, cat.status AS category_status";
	if ($settings['added_by'] == '1')
		$file_sql .= ", ad.username AS adder_username";
	if ($settings['sponsor'] == '1')
		$file_sql .= ", sponsor.sponsor_title, sponsor.sponsor_url";
	$file_sql .= "
		FROM
			on_files AS file
			LEFT JOIN on_categories AS cat ON (cat.catid = file.category)";
	if ($settings['added_by'] == '1')
		$file_sql .= " LEFT JOIN on_users AS ad ON (ad.userid = file.added_by)";
	if ($settings['sponsor'] == '1')
		$file_sql .= " LEFT JOIN on_sponsors AS sponsor ON (sponsor.file_id = file.fileid)";
	$file_sql .= "
		WHERE file.fileid = '". $file_id ."' && file.status = '1'
		LIMIT 1";

	$file_query = mysql_query($file_sql);
	$file_row = mysql_fetch_assoc($file_query);

	// Show 404 if no file
	if (empty($file_row))
		no_page();

	// Show 404 if category disabled
	if ($file_row['category_status'] == 0)
		no_page();

	// Show login if guest now allowed
	if ($file_row['permissions'] == 2 && $user['status'] != '1')
		please_log_in();
	
	// So the user is adult...
	if ($_GET['a'] == 'adult') {
		$update_adult_query = mysql_query("UPDATE on_online SET adult = '1' WHERE ip = '". $user['ip'] ."' && isonline = '1'");
		$_SESSION['adult'] = '1';
	}
	
	// Adult verification for adult games
	if ($file_row['adult'] == '1' && $_SESSION['adult'] != '1') {
		$adult_verification_query = mysql_query("SELECT adult FROM on_online WHERE ip = '". $user['ip'] ."' && isonline = '1' && adult = '1' LIMIT 1");
		$adult_verification_row = mysql_fetch_assoc($adult_verification_query);
		
		if ($adult_verification_row['adult'] == '1') {
			// So the user is adult, there is no need to ask him the question again
			$_SESSION['adult'] = '1';
		} else {
			$lang['warning_adult_content'] = str_replace('{$file_id}', $file_id, $lang['warning_adult_content']);
			$blank_page = array (
				'title'		=>	$settings['sitename'],
				'content'	=>	$lang['warning_adult_content']
			);
    			
    		$page_title = $lang['adult_verification'];
    
    		// Load template		
    		template_blank_page($blank_page);
			exit();
		}
	}
	
	// Update statistics
	$times_played = $file_row['timesplayed'] + 1;
	$stats['played_today'] = $stats['played_today'] + 1;
	$stats['total_played'] = $stats['total_played'] + 1;
	
	if ($user['status'] == '1') {
		$user['played'] = $user['played'] + 1;
		$update_played_query = mysql_query("
			UPDATE
				on_files, on_statistics, on_users
			SET
				on_files.timesplayed = '". $times_played ."', on_statistics.played_today = '". $stats['played_today'] ."', on_statistics.total_played = '". $stats['total_played'] ."', on_users.played = '". $user['played'] ."'
			WHERE
				on_files.fileid = '". $file_row['fileid'] ."' && on_statistics.stats_id = '". $stats['id'] ."' && on_users.userid = '". $user['id'] ."'
		");
    } elseif ($user['status'] == '0' && $settings['guestcredits'] == '1') {
    	$update_played_query = mysql_query("
			UPDATE
				on_files, on_statistics, on_online
			SET
				on_files.timesplayed = '". $times_played ."', on_statistics.played_today = '". $stats['played_today'] ."', on_statistics.total_played = '". $stats['total_played'] ."', on_online.played = on_online.played + 1
			WHERE
				on_files.fileid = '". $file_row['fileid'] ."' && on_statistics.stats_id = '". $stats['id'] ."' && on_online.ip = '". $user['ip'] ."'
		");
    } else {
    	$update_played_query = mysql_query("
			UPDATE
				on_files, on_statistics
			SET
				on_files.timesplayed = '". $times_played ."', on_statistics.played_today = '". $stats['played_today'] ."', on_statistics.total_played = '". $stats['total_played'] ."'
			WHERE
				on_files.fileid = '". $file_row['fileid'] ."' && on_statistics.stats_id = '". $stats['id'] ."'
		");
    }
    
    // Get adder
    if ($settings['added_by'] == '1') {
		if (!empty($file_row['added_by'])) {
			$added_by_username = '<a href="'. profileurl($file_row['added_by'], nohtml($file_row['adder_username'])) .'">'. nohtml($file_row['adder_username']) .'</a>';
		}
		
	}
	
	// Check if file is favourite
	if (isset($user_row['favourites']) && strlen($user_row['favourites'])) {
		$user_favourites = unserialize($user_row['favourites']);
		
		if (in_array($file_row['fileid'], $user_favourites)) {
			$is_favourite = TRUE;
		} else {
			$is_favourite = FALSE;
		}
	} else {
		$is_favourite = FALSE;
	}
	
	// If file is framed then lets frame it
    if ($file_row['filelocation'] == '3') {
    	$file = array (
			'id'				=>	$file_row['fileid'],
			'title'				=>	$file_row['title'],
			'title_m'			=>	$file_row['title_m'],
			'title_en'			=>	$file_row['title_en'],            
			'description_m'		=>	$file_row['description_m'],            
			'description'		=>	$file_row['description'],
			'icon'		        =>	$file_row['icon'],            
			'file'				=>	$file_row['file'],
			'played'			=>	number_format($times_played),
			'rating'			=>	$file_row['rating'],
			'favourite'			=>	$is_favourite
		);
    	
//    	$settings['sitedescription'] = $file['description'];

if (!empty($file['icon'])) $settings['icon'] = '/files/image/'. $file['icon'];

    if (empty($file['description_m']))
            $settings['sitedescription'] = "Онлайн флеш игра " . $file['title']. 
            " без регистрации. Играть бесплатно в мини игру " . $file['title_en'].".".$file['description'];         
	       //$settings['sitedescription'] = $file['description'];
    else $settings['sitedescription'] = $file['description_m'];                    
        
		if (strlen($file_row['keywords'])) {
			$settings['sitekeywords'] = $settings['sitekeywords'] .', '. $file_row['keywords'];
		}
        
    if (empty($file['title_m']))    
        $page_title = $file['title']. " флеш игра. Бесплатно играть в " . $file['title_en'] ." онлайн.";
    else $page_title = $file['title_m']; 
    	
    	// Load template
    	//template_frame();
// Frame: here we frame some cool games        
	global $settings, $lang, $file, $user;
	
	template_overall_header();
	echo '
<body>
  <div>
    <div class="frame_text" style="float: left;">
  	  <b>'. $file['title'] .'</b><br />
		<b>'. $lang['file_description'] .'</b> '. $file['description'] .'<br />
		<b>'. $lang['file_played'] .'</b> '. $file['played'] .'<br />
		<a href="'. $file['file'] .'">'. $lang['remove_frame'] .'</a><br />
		<a href="'. $settings['siteurl'] .'/">'. $lang['back_to_website'] .' '. $settings['sitename'] .'</a>
    </div>
    <div class="frame_text" style="float: right;">
      <b>'. $lang['file_rating'] .'</b> <img src="'. $settings['siteurl'] .'/images/stars'. stars($file['rating']) .'.gif" border="0" width="67" height="15" alt="'. $file['rating'] .'" title="'. $file['rating'] .'" id="file_rating" />';
	if ($settings['rate'] == '2' && $user['status'] == '1' || $settings['rate'] == '1') {
        echo ' <span id="rate_file">[<a href="javascript: rate_file_form(\''. $file['id'] .'\')"><b>'. $lang['file_rate'] .'</b></a>]</span>';
    }
	echo '<br />';
	if ($user['status'] == '1' && $file['favourite'] != TRUE) {
        echo '
        	  <div id="make_favourite">
			    <a href="javascript:make_favourite(\''. $file['id'] .'\', \'make\');">'. $lang['make_favourite'] .'</a><br />
			  </div>';
    } else {
    	echo '
    		  <div id="make_favourite">
			    <a href="javascript:make_favourite(\''. $file['id'] .'\', \'remove\');">'. $lang['remove_favourite'] .'</a><br />
			  </div>';
    }
	if ($settings['tellfriend'] == '1' || $settings['tellfriend'] == '2' && $user['status'] == '1') {
        echo '
			  <a href="" onclick="window.open(\''. $settings['siteurl'] .'/file.php?f='. $file['id'] .'&a=tellafriend\', \'\', \'width=220,height=275,menubar=0,resizable=1,scrollbars=1,status=0,titlebar=0,toolbar=0\'); return false;">'. $lang['tell_a_friend'] .'</a><br />';
    }
	if ($settings['report_broken'] == '1') {
    	echo '
    		  <div id="report_broken">
    		    <a href="javascript:report_broken(\''. $file['id'] .'\', \''. $lang['what_wrong_file'] .'\');">'. $lang['report_broken_file'] .'</a>
			  </div>';
    }
    echo '
    </div>
    <div style="clear: both;"></div>
  </div>
  <iframe src="'. $file['file'] .'" width="100%" height="730" frameborder="0" scrolling="auto"></iframe>
</body>
</html>';        
        
    	exit();
    }
	
	// Find best score
	if ($file_row['scores'] == '1') {
		$best_score_query = mysql_query("SELECT user_id, username, score 
                                         FROM on_scores 
                                         WHERE is_high = '1' && file_id = '". $file_row['fileid'] ."'");
	
		if (mysql_num_rows($best_score_query) == 0) {
			$best_score_user = $lang['no_one'];
			$best_score = '0';
		} else {
			$best_score_row = mysql_fetch_assoc($best_score_query);
			
			$best_score_user = '<a href="'. profileurl($best_score_row['user_id'], $best_score_row['username']) .'">'. $best_score_row['username'] .'</a>';
			$best_score = number_format($best_score_row['score'], 1);
		}
		// Replace some variables
		$lang['is_champion_with_score'] = str_replace('{$best_score_user}', $best_score_user, $lang['is_champion_with_score']);
		$lang['is_champion_with_score'] = str_replace('{$file_title}', $file_row['title'], $lang['is_champion_with_score']);
		$lang['is_champion_with_score'] = str_replace('{$best_score}', $best_score, $lang['is_champion_with_score']);
	}
    
    // Direct URL to file
	if ($file_row['filelocation'] == '1') {
		$file_url = $settings['siteurl'] .'/files/'. $settings['filesdir'] .'/'. $file_row['file'];
    } else {
	    $file_url = $file_row['file'];
    }
    
    // Direct URL to image
    if ($file_row['iconlocation'] == '1') {
		$image_url = $settings['siteurl'] .'/files/image/'. $file_row['icon'];
    } else {
	    $image_url = $file_row['icon'];
    }
    
    // Add to your website text
    $add_your_website = '<img src = "'. $image_url .'" border="0" alt="'. $file_row['title'] .'" title="'. $file_row['title'] .'" /><br /><a href="'. fileurl($file_row['fileid'],$file_row['title']) .'">'. $file_row['title'] .'</a>';
    
    // Get the file displaying code
    if ($file_row['width'] > $settings['max_file_width'] && $settings['auto_resize'] == '0' || 
        $file_row['height'] > $settings['max_file_height'] && $settings['auto_resize'] == '0') {
            
    	$play_file = '<a href="" onclick="window.open(\''. $settings['siteurl'] .'/file.php?f='. $file_row['fileid'] .
        '&a=popup\', \'\', \'width='. $file_row['width'] .',height='. $file_row['height'] .
        ',menubar=0,resizable=0,scrollbars=0,status=0,titlebar=0,toolbar=0\'); return false;">'. 
        $lang['open_file_in_new_window'] .'</a>';
        
    } elseif ($file_row['filetype'] == 'code') {
		$play_file = $file_row['customcode'];
	} else {
    	// Resize if file is too big
		if ($file_row['width'] > $settings['max_file_width']) {
		    $size_change = $file_row['width'] / $settings['max_file_width'];
			$file_row['width'] = $settings['max_file_width'];
			$file_row['height'] = round($file_row['height'] / $size_change);    
		}
		if ($file_row['height'] > $settings['max_file_height']) {
		    $size_change = ($file_row['height'] / $settings['max_file_height']);
			$file_row['height'] = $settings['max_file_height'];
			$file_row['width'] = round($file_row['width'] / $size_change);    
		}
    	// Load player for file
    	$play_file = file_get_contents('includes/file_type/'. $file_row['filetype'] .'.php');
    	
    	// Replace variables
    	$play_file = str_replace('{$width}', $file_row['width'], $play_file);
    	$play_file = str_replace('{$height}', $file_row['height'], $play_file);
    	$play_file = str_replace('{$file_url}', $file_url, $play_file);
    	$play_file = str_replace('{$siteurl}', $settings['siteurl'], $play_file);
    }
    
    // Set session for v3 games
    $_SESSION['file_id'] = $file_row['fileid'];
    
	$file = array (
		'id'				=>	$file_row['fileid'],
		'title'				=>	$file_row['title'],
		'title_m'			=>	$file_row['title_m'],
		'title_en'			=>	$file_row['title_en'],                
		'description'		=>	$file_row['description'],
		'description_m'		=>	$file_row['description_m'],
		'icon'		        =>	$file_row['icon'],                 
		'played'			=>	number_format($times_played),
		'added'				=>	mod_date($file_row['dateadded']),
		'rating'			=>	$file_row['rating'],
		'added_by'			=>	$added_by_username,
		'play_file'			=>	$play_file,
		'scores'			=>	$file_row['scores'],
		'add_your_website'	=>	$add_your_website,
		'cat_title'			=>	$file_row['category_name'],
		'cat_url'			=>	categoryurl($file_row['category'], $file_row['category_name'], 1),
		'favourite'			=>	$is_favourite,
		'sponsor'			=>	(strlen($file_row['sponsor_title']) ? '<a href="'. $file_row['sponsor_url'] .'" target="_blank">'. $file_row['sponsor_title'] .'</a>' : '<a href="'. $settings['siteurl'] .'/sponsor.php?f='. $file_row['fileid'] .'">'. $lang['your_link_here'] .'</a>'),
		'comment_error'		=>	$comment_error
	);
	
	if ($settings['comments'] == '1') {
		// Build navigation menu
		$comments_number_query = mysql_query("SELECT count(*) FROM on_comments WHERE fileid = '". $file_id ."' && status = '1'");
		$comments_number_row = mysql_fetch_assoc($comments_number_query);
		$pages_count = ceil($comments_number_row['count(fileid)'] / $settings['max_comments']);
		$navigation = NULL;
		for ($pagen = 1; $pagen <= $pages_count; $pagen++) {
			if ($pagen == 1) {
				$navigation .= ' <b>'. $pagen .'</b>';
        	} else {
				$navigation .= ' <a href="" onclick="display_comments('. $file_id .', '. $pagen .'); return false;">'. $pagen .'</a>';
	    	} 
    	}
    	// Get comments
		$comments_query = mysql_query("SELECT * FROM on_comments WHERE fileid = '". $file_id ."' && status = '1' ORDER BY commentid DESC LIMIT 0, ". $settings['max_comments']);
		
		while ($comments_row = mysql_fetch_assoc($comments_query)) {
			if ($comments_row['userid'] == '0' || empty($comments_row['username'])) {
				$poster_username = $lang['guest'];
			} else {
				$poster_username = '<a href="'. profileurl($comments_row['userid'], $comments_row['username']) .'">'. $comments_row['username'] .'</a>';
			}
			
			$comments[] = array (
				'comment'	=>	word_filter(bbcode(nl2br(nohtml($comments_row['comment'])))),
				'date'		=>	mod_date($comments_row['dateadded']),
				'user'		=>	$poster_username
			);
	
		}
	}
	
	if ($settings['related_files'] == '1') {
		$related_query = mysql_query("SELECT fileid, title, description,  title_m, title_en, description_m, icon, 
                                      iconlocation, timesplayed FROM on_files 
                                      WHERE category = '". $file_row['category'] ."' && 
                                      status ='1' && 
                                      fileid != '". $file_row['fileid'] ."' 
                                      ORDER BY RAND() 
                                      LIMIT ". $settings['max_related_files']);
		while ($related_row = mysql_fetch_assoc($related_query)) {
			if ($related_row['iconlocation'] == '1') {
				//$image_url = $settings['siteurl'] .'/files/image/'. $related_row['icon'];
                $image_url = '/files/image/'. $related_row['icon'];                
			} else {
				$image_url = $related_row['icon'];
			}
			$related_files[] = array (
				'title'			=>	$related_row['title'],
				'title_m'		=>	$related_row['title_m'],
				'title_en'		=>	$related_row['title_en'],                                
				'url'			=>	fileurl($related_row['fileid'],$related_row['title'],1),
				'description'	=>	$related_row['description'],
				'description_m'	=>	$related_row['description_m'],                
				'image'			=>	$image_url
			);	
		
		}
	}
	
	// Get ad
	if ($settings['file_ad'] == '1') {
		$ad_query = mysql_query("SELECT ad_code FROM on_ads 
                                 WHERE status = '1' && ad_zone = '3' 
                                 ORDER BY rand() LIMIT 1");
		$ad = mysql_fetch_assoc($ad_query);
		$ads['file'] = $ad['ad_code'];
	}
	
//	$settings['sitedescription'] = $file['description'];
//	if (strlen($file_row['keywords'])) {
//		$settings['sitekeywords'] = $settings['sitekeywords'] .', '. $file_row['keywords'];
//	}
//	$page_title = $file['title'];
//   	$settings['sitedescription'] = $file['description'];


//print_r ($file);

if (!empty($file['icon'])) $settings['icon'] = '/files/image/'. $file['icon'];

    if (empty($file['description_m']))   
	    $settings['sitedescription'] = "Онлайн флеш игра " . $file['title']. 
        " без регистрации. Играть бесплатно в мини игру " . $file['title'].".".$file['description'];        
    else 
    	$settings['sitedescription'] = $file['description_m'];
                         
		if (strlen($file_row['keywords'])) {
			$settings['sitekeywords'] = $settings['sitekeywords'] .', '. $file_row['keywords'];
		}
        
    if (empty($file['title_m']))    
        $page_title = $file['title']. " флеш игра. Бесплатно играть в " . $file['title_en'] ." онлайн.";
    else $page_title = $file['title_m'];
          
	// Load template
	//template_file();
// вывод отдельной странички игры
	global $settings, $lang, $file, $user, $comments, $related_files;
    $objImageGd = new Vi_Image_Gd();	
	template_header();
	echo '
	  <div class="content_box" id="favourite_message" style="'. ($file['favourite'] == TRUE ? 'display: block;' : 'display: none;') .'">
	    <center>'. $lang['file_favourite'] .'</center>
	  </div>';
	if ($file['scores'] == '1') {
		if ($user['status'] != '1') {
    		echo '
	  <div class="content_box">
	    <center>'. $lang['please_log_in_save_score'] .'</center>
	  </div>';
		}
    	echo '
	  <div id="champion_box">
	    '. $lang['is_champion_with_score'] .'<br />
	    <a href="'. scoresurl($file['id']) .'">'. $lang['all_scores'] .'</a>
	  </div>';
	}
	if ($settings['sponsor'] == '1') {
		echo '
	  <div id="sponsor">
	    '. $lang['sponsor'] .': '. $file['sponsor'] .'
	  </div>';
    }

?>	
      <div class="content_box_header">
        <a href="<?= $settings['siteurl'] ?>/">Главная</a> > 
        <a href="<?= $file['cat_url'] ?>"><?= $file['cat_title'] ?></a> > <?=$file['title'] ?> 
      </div>
      <div class="content_box" id="placegame">
	    <center>
	      <?= $file['play_file'] ?>   
	    </center>

<div class="btn-group center hjghkg1">
<!-- <input type="button" value="Развернуть на весь экран" onclick="javascript:resize()"> -->
<button class="btn btn-success" onclick="javascript:resize(<?=$file_row['width']?>,<?=$file_row['height']?>)">Как и было</button>
<button class="btn btn-primary" onclick="javascript:resize(240,200)">240x200</button>
<button class="btn btn-primary" onclick="javascript:resize(450,300)">450x300</button>
<button class="btn btn-primary" onclick="javascript:resize(450,300)">650x500</button>
<button class="btn btn-primary" onclick="javascript:resize(800,700)">800x700</button>
<button class="btn btn-primary" onclick="javascript:resize(800,700)">900x800</button>
<button class="btn btn-primary" onclick="javascript:resize(1000,850)">1000x850</button>        
<button class="btn btn-primary" onclick="javascript:resize(1200,1000)">1200x1000</button>

    <a class="fullsizebutton btn btn-danger" href="/fullsize.php?url=<?= $file_url?>">
    <span class="fullsize">На весь экран</span></a>

</div>
	  </div>
<?
// File Ad: this is the ad that is showed on files page      
// вывод коментов
	//template_file_ad();   
	global $settings, $ads;
	// Display file ad
	if ($settings['file_ad'] == '1') {
		echo '
	  <div class="ad_box">
	    '. $ads['file'] .'
	  </div>';
	}    
    
	echo '
	  <div class="content_box_header">
        '. $lang['file_info'] . ($user['group'] == '2' ? ' [<a href="'. $settings['siteurl'] .'/admin/content.php?a=edit_file&f='. $file['id'] .'" class="contentheaderlink">'. $lang['edit_file'] .'</a>]' : '') .'
      </div>
      <div class="content_box">
	    <div style="float: left; width: 64.5%;">
		  <b>'. $lang['file_title'] .'</b> '. $file['title'] .'<br />
		  <b>'. $lang['file_description'] .'</b> '. $file['description'] .'<br />
		  <b>'. $lang['file_played'] .'</b> '. $file['played'] .'<br />
		  <b>'. $lang['file_added'] .'</b> '. $file['added'] .'<br />
		  '. ($settings['added_by'] == '1' && strlen($file['added_by']) ? '<b>'. $lang['file_added_by'] .'</b> '. $file['added_by'] : '') .'
	    </div>
	    <div style="float: right; width: 34.5%;">';
        
/*		  <b>'. $lang['file_rating'] .'</b> <img src="'. $settings['siteurl'] .'/images/stars'. 
          stars($file['rating']) .'.gif" border="0" width="67" height="15" alt="'. $file['rating'] 
          .'" title="'. $file['rating'] .'" id="file_rating" />'. 
          ($settings['rate'] == '2' && $user['status'] == '1' || $settings['rate'] == '1' ? 
          ' <span id="rate_file">[<a href="javascript: rate_file_form(\''. $file['id'] .'\')">
          <b>'. $lang['file_rate'] .'</b></a>]</span>' : '') .'<br />';
*/
?>
<!-- блок голосования -->                 
<div class="box1">
<div class="cd212">Оцените игру:</div>
<div class='up'><a href="" class="vote" id="<?= $file['id']; ?>" name="up">
    <img src="/images/Gmark.png" width="24" height="24" /></a>
</div>
<div class='boxx'><?= $file['rating'] ?></div> 
<div class='down'><a href="" class="vote" id="<?= $file['id']; ?>" name="down">
    <img src="/images/Bmark.png" width="24" height="24" /></a></a></div>
</div>
<div style="clear: both;"></div>                    
<!-- блок голосования -->                              
<?php          
	if ($user['status'] == '1' && $file['favourite'] == FALSE) {
        echo '
		  <div id="make_favourite">
			<a href="javascript:make_favourite(\''. $file['id'] .'\', \'make\');">'. $lang['make_favourite'] .'</a><br />
		  </div>';
    } elseif ($user['status'] == '1' && $file['favourite'] == TRUE) {
    	echo '
		  <div id="make_favourite">
			<a href="javascript:make_favourite(\''. $file['id'] .'\', \'remove\');">'. $lang['remove_favourite'] .'</a><br />
		  </div>';
    }
	if ($settings['tellfriend'] == '1' || $settings['tellfriend'] == '2' && $user['status'] == '1') {
        echo '
		  <a href="" onclick="window.open(\''. $settings['siteurl'] .'/file.php?f='. $file['id'] .'&a=tellafriend\', \'\', \'width=250,height=275,menubar=0,resizable=1,scrollbars=1,status=0,titlebar=0,toolbar=0\'); return false;">'. $lang['tell_a_friend'] .'</a>';
    }
	if ($settings['report_broken'] == '1') {
    	echo '
		  <div id="report_broken">
    		<a href="javascript:report_broken(\''. $file['id'] .'\', \''. $lang['what_wrong_file'] .'\');">Сообщить об ошибке</a>
		  </div>';
    }
 ?>
	    </div>
<div style="clear: both;"></div> 
<div class="key23">
<div class="share42init" data-url="<?=fileurl($file_row['fileid'],$file_row['title'])?>" data-title="<?=$file['title']?>" data-image="http://game2ok.com<?=$image_url?>" data-description="<?=$file['description']?>"></div>
</div>
<script type="text/javascript" src="/jscripts/share42.js"></script> 
        
		<div style="clear: both;"></div>
	  </div>
<?      
	if ($settings['comments'] == '1') {
		echo '
      <div class="content_box_header">
        '. $lang['comments'] . ($user['group'] == '2' ? ' [<a href="'. $settings['siteurl'] .'/admin/content.php?a=file_comments&f='. $file['id'] .'" class="contentheaderlink">'. $lang['edit_comments'] .'</a>]' : '') .'
      </div>
      <div class="content_box">
	    <span id="file_comments">';
		//template_display_comments();
        
	global $comments, $navigation;
	
	if (is_array($comments)) {
		echo '
	      <div class="pagination">
			'. $navigation .'
	      </div>';
		foreach ($comments as $comment) {
			echo '
	      <div class="comment_box">
			<b>'. $comment['user'] .'</b> ('. $comment['date'] .'):<br />
			'. $comment['comment'] .'
	      </div>';
		}
		echo '
	      <div class="pagination">
			'. $navigation .'
	      </div>';
	}        
        
        
		echo '
	    </span>';
		if ($settings['comments_who'] == '1' || $settings['comments_who'] == '2' && $user['status'] == '1') {
			echo '
		  '. $lang['leave_a_comment'] .'<br />
	    <div style="color: red;" id="comment_error"></div>
	    <div style="float: left; width: 50%;">
	        <textarea name="message" rows="10" cols="70" id="comment_message"></textarea><br /><br />
	        <input type="submit" class="btn btn-success btn-lg" name="submit_comment" value="'. $lang['file_add_comment'] .'" onclick="submit_comment('. $file['id'] .')" />
	    </div>
	    <div style="float: right; width: 50%;">
<!--		  <a href="javascript:addsmilie(\' :) \')"><img src="'. $settings['siteurl'] .'/images/happy.gif" border="0" title="'. $lang['bb_happy'] .'" alt="'. $lang['bb_happy'] .'" /></a> -->
		  <a href="javascript:addsmilie(\' [opeka] \')"><img src="'. $settings['siteurl'] .'/images/chat/opeka.png" border="0" /></a>
		  <a href="javascript:addsmilie(\' [ocry] \')"><img src="'. $settings['siteurl'] .'/images/chat/ocry.png" border="0" /></a>                                                  
		  <a href="javascript:addsmilie(\' [manul] \')"><img src="'. $settings['siteurl'] .'/images/chat/manul.png" border="0" /></a>                              
		  <a href="javascript:addsmilie(\' [mee] \')"><img src="'. $settings['siteurl'] .'/images/chat/mee.png" border="0" /></a>           
		  <a href="javascript:addsmilie(\' [mhu] \')"><img src="'. $settings['siteurl'] .'/images/chat/mhu.png" border="0" /></a>                                                  
		  <a href="javascript:addsmilie(\' [neponi] \')"><img src="'. $settings['siteurl'] .'/images/chat/neponi.png" border="0" /></a>
		  <a href="javascript:addsmilie(\' [nocomments] \')"><img src="'. $settings['siteurl'] .'/images/chat/nocomments.png" border="0" /></a>
		  <a href="javascript:addsmilie(\' [notch] \')"><img src="'. $settings['siteurl'] .'/images/chat/notch.png" border="0" /></a>                              

		  <a href="javascript:addsmilie(\' [mad] \')"><img src="'. $settings['siteurl'] .'/images/chat/mad.png" border="0" /></a>
		  <a href="javascript:addsmilie(\' [ii] \')"><img src="'. $settings['siteurl'] .'/images/chat/ii.png" border="0" /></a>           
		  <a href="javascript:addsmilie(\' [huh] \')"><img src="'. $settings['siteurl'] .'/images/chat/huh.png" border="0" /></a>                              
		  <a href="javascript:addsmilie(\' [harupeka] \')"><img src="'. $settings['siteurl'] .'/images/chat/harupeka.png" border="0" /></a>
		  <a href="javascript:addsmilie(\' [happycry] \')"><img src="'. $settings['siteurl'] .'/images/chat/happycry.png" border="0" /></a>                                                  
		  <a href="javascript:addsmilie(\' [cry] \')"><img src="'. $settings['siteurl'] .'/images/chat/cry.png" border="0" /></a>                              
		  <a href="javascript:addsmilie(\' [crazy] \')"><img src="'. $settings['siteurl'] .'/images/chat/crazy.png" border="0" /></a>
		  <a href="javascript:addsmilie(\' [butth] \')"><img src="'. $settings['siteurl'] .'/images/chat/butth.png" border="0" /></a>                              
		  <a href="javascript:addsmilie(\' [awesome] \')"><img src="'. $settings['siteurl'] .'/images/chat/awesome.png" border="0" /></a>
		  <a href="javascript:addsmilie(\' [aangry] \')"><img src="'. $settings['siteurl'] .'/images/chat/aangry.png" border="0" /></a>
		  <a href="javascript:addsmilie(\' [a] \')"><img src="'. $settings['siteurl'] .'/images/chat/a.png" border="0" /></a>
		  <a href="javascript:addsmilie(\' [2] \')"><img src="'. $settings['siteurl'] .'/images/chat/2.png" border="0" /></a>                                                  
		  <a href="javascript:addsmilie(\' [sad] \')"><img src="'. $settings['siteurl'] .'/images/chat/sad.png" border="0"  /></a>
		  <a href="javascript:addsmilie(\' [ra] \')"><img src="'. $settings['siteurl'] .'/images/chat/ra.png" border="0"  /></a>
		  <a href="javascript:addsmilie(\' [epeka] \')"><img src="'. $settings['siteurl'] .'/images/chat/epeka.png" border="0"  /></a>
		  <a href="javascript:addsmilie(\' [kawai] \')"><img src="'. $settings['siteurl'] .'/images/chat/kawai.png" border="0"  /></a>
		  <a href="javascript:addsmilie(\' [omg] \')"><img src="'. $settings['siteurl'] .'/images/chat/omg.png" border="0"  /></a>
		  <a href="javascript:addsmilie(\' [mini-happy] \')"><img src="'. $settings['siteurl'] .'/images/chat/mini-happy.png" border="0" /></a>
		  <a href="javascript:addsmilie(\' [glory] \')"><img src="'. $settings['siteurl'] .'/images/chat/glory.png" border="0"  /></a>
		  <a href="javascript:addsmilie(\' [facepalm] \')"><img src="'. $settings['siteurl'] .'/images/chat/facepalm.png" border="0" /></a>                   
		  <a href="javascript:addsmilie(\' [alone] \')"><img src="'. $settings['siteurl'] .'/images/chat/alone.png" border="0"  /></a>          
		  <a href="javascript:addsmilie(\' [dobre] \')"><img src="'. $settings['siteurl'] .'/images/chat/dobre.png" border="0"  /></a>          
		  <a href="javascript:addsmilie(\' [fuuuu] \')"><img src="'. $settings['siteurl'] .'/images/chat/fuuuu.png" border="0"  /></a>
		  <a href="javascript:addsmilie(\' [fyeah] \')"><img src="'. $settings['siteurl'] .'/images/chat/fyeah.png" border="0"  /></a>
		  <a href="javascript:addsmilie(\' [grumpy] \')"><img src="'. $settings['siteurl'] .'/images/chat/grumpy.png" border="0" /></a>
		  <a href="javascript:addsmilie(\' [megusta] \')"><img src="'. $settings['siteurl'] .'/images/chat/megusta.png" border="0"  /></a>
		  <a href="javascript:addsmilie(\' [mvp] \')"><img src="'. $settings['siteurl'] .'/images/chat/mvp.png" border="0" /></a>
		  <a href="javascript:addsmilie(\' [no-okay] \')"><img src="'. $settings['siteurl'] .'/images/chat/no-okay.png" border="0" /></a>
		  <a href="javascript:addsmilie(\' [notbad] \')"><img src="'. $settings['siteurl'] .'/images/chat/notbad.png" border="0" /></a>
		  <a href="javascript:addsmilie(\' [okay] \')"><img src="'. $settings['siteurl'] .'/images/chat/okay.png" border="0"  /></a>
		  <a href="javascript:addsmilie(\' [slowpoke] \')"><img src="'. $settings['siteurl'] .'/images/chat/slowpoke.png" border="0" /></a>
		  <a href="javascript:addsmilie(\' [why] \')"><img src="'. $settings['siteurl'] .'/images/chat/why.png" border="0" /></a>                                                                                                              
		  <a href="javascript:addsmilie(\' [apochai] \')"><img src="'. $settings['siteurl'] .'/images/chat/apochai.png" border="0" /></a>                              
		  <a href="javascript:addsmilie(\' [bin] \')"><img src="'. $settings['siteurl'] .'/images/chat/bin.png" border="0" /></a>                                                  
		  <a href="javascript:addsmilie(\' [bm] \')"><img src="'. $settings['siteurl'] .'/images/chat/bm.png" border="0" /></a>
		  <a href="javascript:addsmilie(\' [bobr] \')"><img src="'. $settings['siteurl'] .'/images/chat/bobr.png" border="0" /></a>
		  <a href="javascript:addsmilie(\' [cap] \')"><img src="'. $settings['siteurl'] .'/images/chat/cap.png" border="0" /></a>           
		  <a href="javascript:addsmilie(\' [chan] \')"><img src="'. $settings['siteurl'] .'/images/chat/chan.png" border="0" /></a>                                                  
		  <a href="javascript:addsmilie(\' [cow] \')"><img src="'. $settings['siteurl'] .'/images/chat/cow.png" border="0" /></a>
		  <a href="javascript:addsmilie(\' [daaa] \')"><img src="'. $settings['siteurl'] .'/images/chat/daaa.png" border="0" /></a>           
		  <a href="javascript:addsmilie(\' [daladno] \')"><img src="'. $settings['siteurl'] .'/images/chat/daladno.png" border="0" /></a>                                                  
		  <a href="javascript:addsmilie(\' [ejik] \')"><img src="'. $settings['siteurl'] .'/images/chat/ejik.png" border="0" /></a>
		  <a href="javascript:addsmilie(\' [ewbte] \')"><img src="'. $settings['siteurl'] .'/images/chat/ewbte.png" border="0" /></a>
		  <a href="javascript:addsmilie(\' [fire] \')"><img src="'. $settings['siteurl'] .'/images/chat/fire.png" border="0" /></a>                                         

		  <a href="javascript:addsmilie(\' [heart] \')"><img src="'. $settings['siteurl'] .'/images/chat/heart.png" border="0" /></a>

		  <a href="javascript:addsmilie(\' [ilied] \')"><img src="'. $settings['siteurl'] .'/images/chat/ilied.png" border="0" /></a>                                                  

		  <a href="javascript:addsmilie(\' [kid] \')"><img src="'. $settings['siteurl'] .'/images/chat/kid.png" border="0" /></a>
		  <a href="javascript:addsmilie(\' [kot] \')"><img src="'. $settings['siteurl'] .'/images/chat/kot.png" border="0" /></a>                              
		  <a href="javascript:addsmilie(\' [leefacepalm] \')"><img src="'. $settings['siteurl'] .'/images/chat/leefacepalm.png" border="0" /></a>                     
		  <a href="javascript:addsmilie(\' [lol] \')"><img src="'. $settings['siteurl'] .'/images/chat/lol.png" border="0" /></a>                                                  
		  <a href="javascript:addsmilie(\' [loool] \')"><img src="'. $settings['siteurl'] .'/images/chat/loool.png" border="0" /></a>
		  <a href="javascript:addsmilie(\' [num1] \')"><img src="'. $settings['siteurl'] .'/images/chat/num1.png" border="0" /></a> 

		  <a href="javascript:addsmilie(\' [omsk] \')"><img src="'. $settings['siteurl'] .'/images/chat/omsk.png" border="0" /></a>

		  <a href="javascript:addsmilie(\' [pekaking] \')"><img src="'. $settings['siteurl'] .'/images/chat/pekaking.png" border="0" /></a>                              
		  <a href="javascript:addsmilie(\' [poker] \')"><img src="'. $settings['siteurl'] .'/images/chat/poker.png" border="0" /></a>
          
		  <a href="javascript:addsmilie(\' [really] \')"><img src="'. $settings['siteurl'] .'/images/chat/really.png" border="0" /></a>
		  <a href="javascript:addsmilie(\' [sex] \')"><img src="'. $settings['siteurl'] .'/images/chat/sex.png" border="0" /></a>

		  <a href="javascript:addsmilie(\' [smith] \')"><img src="'. $settings['siteurl'] .'/images/chat/smith.png" border="0" /></a>
		  <a href="javascript:addsmilie(\' [spok] \')"><img src="'. $settings['siteurl'] .'/images/chat/spok.png" border="0" /></a>
		  <a href="javascript:addsmilie(\' [sunl] \')"><img src="'. $settings['siteurl'] .'/images/chat/sunl.png" border="0" /></a>
		  <a href="javascript:addsmilie(\' [tort] \')"><img src="'. $settings['siteurl'] .'/images/chat/tort.png" border="0" /></a>
		  <a href="javascript:addsmilie(\' [trash] \')"><img src="'. $settings['siteurl'] .'/images/chat/trash.png" border="0" /></a>
		  <a href="javascript:addsmilie(\' [trollface] \')"><img src="'. $settings['siteurl'] .'/images/chat/trollface.png" border="0" /></a>                                                                                           
		  <a href="javascript:addsmilie(\' [vaganych] \')"><img src="'. $settings['siteurl'] .'/images/chat/vaganych.png" border="0" /></a>
		  <a href="javascript:addsmilie(\' [wtf] \')"><img src="'. $settings['siteurl'] .'/images/chat/wtf.png" border="0" /></a>
		  <a href="javascript:addsmilie(\' [yao] \')"><img src="'. $settings['siteurl'] .'/images/chat/yao.png" border="0" /></a>
          
<!--		  <a href="javascript:addsmilie(\' ;) \')"><img src="'. $settings['siteurl'] .'/images/wink.gif" border="0" title="'. $lang['bb_wink'] .'" alt="'. $lang['bb_wink'] .'" /></a><br />
		  <a href="javascript:addsmilie(\' :@: \')"><img src="'. $settings['siteurl'] .'/images/@.gif" border="0" title="@" alt="@" /></a>&nbsp;
		  <a href="javascript:addsmilie(\' :8 \')"><img src="'. $settings['siteurl'] .'/images/cool.gif" border="0" title="'. $lang['bb_cool'] .'" alt="'. $lang['bb_cool'] .'" /></a>
		  <a href="javascript:addsmilie(\' :wave: \')"><img src="'. $settings['siteurl'] .'/images/wave.gif" border="0" title="'. $lang['bb_wave'] .'" alt="'. $lang['bb_wave'] .'" /></a><br />
		  <a href="javascript:addsmilie(\' :think: \')"><img src="'. $settings['siteurl'] .'/images/think.gif" border="0" title="'. $lang['bb_hmm'] .'" alt="'. $lang['bb_hmm'] .'" /></a>
		  <a href="javascript:addsmilie(\' :clap: \')"><img src="'. $settings['siteurl'] .'/images/clap.gif" border="0" title="'. $lang['bb_clap_hands'] .'" alt="'. $lang['bb_clap_hands'] .'" /></a> -->
	    </div>
	    <div style="clear: both;"></div>';
		} else {
			echo '
	    <b>'. $lang['login_to_comment'] .'</b>';
		}
		echo '
	  </div>';
	}
// конец вывода коментов    
	if ($settings['add_to_website'] == '1') {
		echo '
	  <div class="content_box_header">
        '. $lang['add_to_your_website'] .'
      </div>
      <div class="content_box">
	    <textarea name="add_game_to_your_website" rows="3" cols="50">'. $file['add_your_website'] .'</textarea>
	  </div>';
	}
	if ($settings['related_files'] == '1' && is_array($related_files)) {
		echo '
	  <div class="content_box_header">
        '. $lang['related_files'] .'
      </div>
      <div class="content_box">';
		foreach ($related_files as $related) {
			echo '
        <div class="allbox">   
		<div class="box11">
		  <a href="'. $related['url'] .'"><img src="'. $objImageGd->getImage($related['image'],120,100,0) .'" width="100" height="90" title="'. $related['title'] .'" alt="'. $related['title'] .'" border="1" /></a>
		</div>
		<div class="box12">
		  <a href="'. $related['url'] .'" class="file_title">'. $related['title'] .'</a><br />
		  '. $related['description'] .'
		</div>
		</div>        
		<div style="clear: both; height: 4px;"></div>';
		}
		echo '
	  </div>';
	}
	template_footer();
    
    
    
}

?>