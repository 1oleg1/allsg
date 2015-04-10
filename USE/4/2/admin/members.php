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

$admincp_action = $_GET['a'];

if ($admincp_action == 'mass_email') {
	if (isset($_POST['preview'])) {
		// So lets preview the message
		$mass_email['preview'] = TRUE;
		$mass_email['subject'] = nohtml($_POST['subject']);
		$mass_email['preview_message'] = bbcode(nl2br(nohtml(stripslashes($_REQUEST['message']))));
		$mass_email['message'] = nohtml(stripslashes($_REQUEST['message']));
		if ($_POST['delivery'] == 'normal_email')
			$mass_email['normal_email_selected'] = 'selected';
		else
			$mass_email['private_message_selected'] = 'selected';
	} elseif (isset($_POST['send'])) {
		// Time to send the message
		if (empty($_POST['subject']) || empty($_POST['message']) || empty($_POST['delivery'])) {
			if ($_POST['delivery'] == 'normal_email')
				$mass_email['normal_email_selected'] = 'selected';
			else
				$mass_email['private_message_selected'] = 'selected';
			$mass_email['subject'] = nohtml($_POST['subject']);
			$mass_email['message'] = nohtml(stripslashes($_REQUEST['message']));
			$mass_email['error'] = $lang['all_fields_required'];
		} else {
			if ($_POST['delivery'] == 'normal_email') {
				// So you want to send email?
				$email_message = $_REQUEST['message'];
				$members_query = mysql_query("SELECT username, email FROM ". $tbl_prefix ."users WHERE status = '1' && receiveemails = '1'"); // we do not want to send email to members who do not want it or who haven't verified their emails
				// Email header
				$email_header = 'Return-Path: '. $settings['sitecontactemail'] .'
From: '. $settings['sitename'] .' <'. $settings['sitecontactemail'] .'>
MIME-Version: 1.0
Content-type: text/plain';
				while ($members_row = mysql_fetch_assoc($members_query)) {
					$email_message_send = str_replace('{username}', $members_row['username'], $email_message);
					// Send
					@mail($members_row['email'], $_POST['subject'], $email_message_send, $email_header);
				}
				
				// Redirect
				redirect_page($settings['siteurl'] .'/admin/members.php?a=mass_email', $lang['mass_email_sent']);
			} else {
				// PM is also cool
				$pm_message = $_POST['message'];
				$members_query = mysql_query("SELECT userid, username, email, email_pm, newpm FROM ". $tbl_prefix ."users WHERE status = '1' && receiveemails = '1'");  // we do not want to send PM to members who do not want it or who haven't verified their emails
				$user_notified = array();
				while ($members_row = mysql_fetch_assoc($members_query)) {
					$pm_message_send = str_replace('{username}', $members_row['username'], $pm_message);
					// Send
					$insert_pm_query = mysql_query("INSERT INTO ". $tbl_prefix ."privatemessages SET userid = '". $members_row['userid'] ."', touser = '". $members_row['userid'] ."', fromuser = '". $user['id'] ."', folder = '1', subject = '". $_POST['subject'] ."', message = '". $pm_message_send ."', date_sent = '". time() ."', status = '0'");
					$user_notified[] = $members_row['userid'];
				}
				// Update new PM status
				$update_new_pm_query = mysql_query("UPDATE ". $tbl_prefix ."users SET newpm = '1' WHERE userid IN (". implode(', ', $user_notified) .")");
				
				// Redirect
				redirect_page($settings['siteurl'] .'/admin/members.php?a=mass_email', $lang['mass_pm_sent']);
			}
		}
	}

	$page_title = $lang['mass_email'];

	// Load template
	template_mass_email();
} elseif ($admincp_action == 'add_member') {
	if (isset($_POST['submit_member'])) {
		if (strlen($_POST['username']) && strlen($_POST['password']) && strlen($_POST['email'])) {
			$username_check_query = mysql_query("SELECT count(userid) FROM ". $tbl_prefix ."users WHERE username = '". $_POST['username'] ."'");
    		$username_check_row = mysql_fetch_assoc($username_check_query);
    		if ($username_check_row['count(userid)'] == '0') {
    			$email_check_query = mysql_query("SELECT count(userid) FROM ". $tbl_prefix ."users WHERE email = '". $_POST['email'] ."'");
    			$email_check_row = mysql_fetch_assoc($email_check_query);
    			if ($email_check_row['count(userid)'] == '0') {
    				// Add to database
    				$add_member_query = mysql_query("INSERT INTO ". $tbl_prefix ."users SET username = '". $_POST['username'] ."', password = '". md5($_POST['password']) ."', email = '". $_POST['email'] ."', status = '". $_POST['user_status'] ."', joined = ". time() .", usergroup = '". $_POST['user_group'] ."'");
    			
    				if ($_POST['user_status'] == '1') {
    					// Update statistics table
    					$update_files_query = mysql_query("UPDATE ". $tbl_prefix ."statistics SET total_members = total_members + 1 WHERE stats_id = '". $stats['id'] ."' LIMIT 1");
    				}
    			
    				// Redirect
					redirect_page($settings['siteurl'] .'/admin/members.php?a=members', $lang['user_added']);
				} else {
					$add_member['error'] = $lang['member_same_email'];
				}
    		} else {
    			$add_member['error'] = $lang['member_same_username'];
    		}
    	} else {
    		$add_member['error'] = $lang['all_fields_required'];
    	}
    	// Some variables
    	$add_member['username'] = nohtml($_POST['username']);
    	$add_member['email'] = nohtml($_POST['email']);
	}

	$page_title = $lang['add_member'];

	// Load template
	template_add_member();
} elseif ($admincp_action == 'edit_member') {
	$member_id = (int) $_GET['m'];
	if (isset($_POST['submit_member'])) {
		// Update member information
		if (strlen($_POST['username']) && strlen($_POST['email'])) {
			$username_check_query = mysql_query("SELECT count(userid) FROM ". $tbl_prefix ."users WHERE username = '". $_POST['username'] ."' && userid != '". $member_id ."'");
    		$username_check_row = mysql_fetch_assoc($username_check_query);
    		if ($username_check_row['count(userid)'] == '0') {
    			$email_check_query = mysql_query("SELECT count(userid) FROM ". $tbl_prefix ."users WHERE email = '". $_POST['email'] ."' && userid != '". $member_id ."'");
    			$email_check_row = mysql_fetch_assoc($email_check_query);
    			if ($email_check_row['count(userid)'] == '0') {
    				// Edit database
    				$edit_member_query = mysql_query("UPDATE ". $tbl_prefix ."users SET username = '". $_POST['username'] ."', email = '". $_POST['email'] ."', status = '". $_POST['user_status'] ."', usergroup = '". $_POST['user_group'] ."', avatar ='". $_POST['avatar'] ."', avatar ='". nohtml($_POST['avatar']) ."', location ='". nohtml($_POST['location']) ."', website ='". nohtml($_POST['website']) ."', gender ='". $_POST['gender'] ."', msn ='". nohtml($_POST['msn']) ."', aim ='". nohtml($_POST['aim']) ."', skype ='". nohtml($_POST['skype']) ."', yahoo ='". nohtml($_POST['yahoo']) ."', icq ='". nohtml($_POST['icq']) ."', google_talk ='". nohtml($_POST['google_talk']) ."' WHERE userid = '". $member_id ."' LIMIT 1");
    			
    				// Recount users
    				$recount_members_query = mysql_query("SELECT count(userid) AS members_count FROM ". $tbl_prefix ."users WHERE status = '1'");
					$recount_members_row = mysql_fetch_assoc($recount_members_query);
	
					// Update statistics table
					$update_stats_query = mysql_query("UPDATE ". $tbl_prefix ."statistics SET total_members = '". $recount_members_row['members_count'] ."' WHERE stats_id = '". $stats['id'] ."' LIMIT 1");
    			
    				// Redirect
					redirect_page($settings['siteurl'] .'/admin/members.php?a=edit_member&m='. $member_id, $lang['member_updated']);
				} else {
					$edit_member_error = $lang['member_same_email'];
				}
    		} else {
    			$edit_member_error = $lang['member_same_username'];
    		}
    	} else {
    		$edit_member_error = $lang['username_email_empty'];
    	}
	}
	// Member information
	$user_query = mysql_query("SELECT * FROM ". $tbl_prefix ."users WHERE userid = '". $member_id ."' LIMIT 1");
	$user_row = mysql_fetch_assoc($user_query);
	
	$edit_member = array (
		'id'			=>	$user_row['userid'],
		'username'		=>	nohtml($user_row['username']),
		'email'			=>	nohtml($user_row['email']),
		'avatar'		=>	nohtml($user_row['avatar']),
		'location'		=>	$user_row['location'],
		'website'		=>	$user_row['website'],
		'status'		=>	$user_row['status'],
		'user_group'	=>	$user_row['usergroup'],
		'gender'		=>	$user_row['gender'],
		'msn'			=>	$user_row['msn'],
		'aim'			=>	$user_row['aim'],
		'skype'			=>	$user_row['skype'],
		'yahoo'			=>	$user_row['yahoo'],
		'icq'			=>	$user_row['icq'],
		'google_talk'	=>	$user_row['google_talk']
	);

	$page_title = $lang['edit_member'];

	// Load template
	template_edit_member();
} elseif ($admincp_action == 'member_comments') {
	$member_id = (int) $_GET['m'];
	if (isset($_POST['submit_comments'])) {
		if (isset($_POST['comment_id'])) {
			if ($_POST['comments_action'] == 'delete_comment') {
				// Delete selected comments
				foreach ($_POST['comment_id'] as $key => $val) {
					$delete_comments[] = (int) $key;
				}
				if (is_array($delete_comments)) {
					// Delete comments
					$delete_comments_query = mysql_query("DELETE FROM ". $tbl_prefix ."comments WHERE commentid IN (". implode(', ', $delete_comments) .")");
				}
				
				// Redirect
				redirect_page($settings['siteurl'] .'/admin/members.php?a=member_comments&m='. $member_id, $lang['comment_deleted']);
			} elseif ($_POST['comments_action'] == 'approve') {
				// Approve selected comments
				foreach ($_POST['comment_id'] as $key => $val) {
					$approve_comments[] = (int) $key;
				}
				if (is_array($approve_comments)) {
					// Approve comments
					$approve_comments_query = mysql_query("UPDATE ". $tbl_prefix ."comments SET status = '1' WHERE commentid IN (". implode(', ', $approve_comments) .")");
				}
				
				// Redirect
				redirect_page($settings['siteurl'] .'/admin/members.php?a=file_comments&m='. $member_id, $lang['comment_approved']);
			}
		}
	}
	
	// Some variables
	$comment_status_value = array (
		'0'	=>	'<font color="#FF3300">'. $lang['awaiting_approval'] .'</font>',
		'1'	=>	'<font color="#66CC00">'. $lang['approved'] .'</font>'
	);
	
	$comments = array ();
	// Get categories
	$comments_query = mysql_query("
		SELECT
			c.*, f.title
		FROM
			". $tbl_prefix ."comments AS c
			LEFT JOIN ". $tbl_prefix ."files AS f ON (f.fileid = c.fileid)
		WHERE c.userid = '". $member_id ."'");
	while ($comments_row = mysql_fetch_assoc($comments_query)) {
		$comments[] = array (
			'id'				=>	$comments_row['commentid'],
			'comment'			=>	word_filter(bbcode(nl2br(nohtml($comments_row['comment'])))),
			'poster_id'			=>	$comments_row['userid'],
			'file_id'			=>	$comments_row['fileid'],
			'file'				=>	$comments_row['title'],
			'ip'				=>	$comments_row['ip'],
			'date'				=>	mod_date($comments_row['dateadded']),
			'status'			=>	$comment_status_value[$comments_row['status']]

		);
	}
	
	$page_title = $lang['comments'];

	// Load template
	template_member_comments();
} else {
	$page = $_GET['p'];
	if (empty($page) || !is_numeric($page)) {
		$page = '1';
	}
	
	if (isset($_POST['submit_members'])) {
		if (isset($_POST['member_id'])) {
			if ($_POST['members_action'] == 'delete') {
			 	// Delete selected members
				foreach ($_POST['member_id'] as $key => $val) {
					$delete_members[] = (int) $key;
				}
				if (is_array($delete_members)) {
					// Delete members
					$delete_members_query = mysql_query("DELETE FROM ". $tbl_prefix ."users WHERE userid IN (". implode(', ', $delete_members) .")");
				
					// Recount members
					$recount_members_query = mysql_query("SELECT count(userid) AS members_count FROM ". $tbl_prefix ."users WHERE status = '1'");
					$recount_members_row = mysql_fetch_array($recount_members_query);
		
					// Update statistics table
					$update_stats_query = mysql_query("UPDATE ". $tbl_prefix ."statistics SET total_members = '". $recount_members_row['members_count'] ."' WHERE stats_id = '". $stats['id'] ."'");
				}
				
				// Redirect
				redirect_page($_SERVER['HTTP_REFERER'], $lang['member_deleted']);
			} elseif ($_POST['members_action'] == 'active') {
				// Mak selected members active
				foreach ($_POST['member_id'] as $key => $val) {
					$update_members[] = (int) $key;
				}
				if (is_array($update_members)) {
					$update_members_status_query = mysql_query("UPDATE ". $tbl_prefix ."users SET status = '1' WHERE userid IN (". implode(', ', $update_members) .")");
				
					// Recount members
					$recount_members_query = mysql_query("SELECT count(userid) AS members_count FROM ". $tbl_prefix ."users WHERE status = '1'");
					$recount_members_row = mysql_fetch_array($recount_members_query);
		
					// Update statistics table
					$update_stats_query = mysql_query("UPDATE ". $tbl_prefix ."statistics SET total_members = '". $recount_members_row['members_count'] ."' WHERE stats_id = '". $stats['id'] ."'");
				}
				
				// Redirect
				redirect_page($_SERVER['HTTP_REFERER'], $lang['member_marked_active']);
			} elseif ($_POST['members_action'] == 'ban') {
				// Ban selected members
				foreach ($_POST['member_id'] as $key => $val) {
					$update_members[] = (int) $key;
				}
				if (is_array($update_members)) {
					$update_members_status_query = mysql_query("UPDATE ". $tbl_prefix ."users SET status = '2' WHERE userid IN (". implode(', ', $update_members) .")");
				
					// Recount members
					$recount_members_query = mysql_query("SELECT count(userid) AS members_count FROM ". $tbl_prefix ."users WHERE status = '1'");
					$recount_members_row = mysql_fetch_assoc($recount_members_query);
		
					// Update statistics table
					$update_stats_query = mysql_query("UPDATE ". $tbl_prefix ."statistics SET total_members = '". $recount_members_row['members_count'] ."' WHERE stats_id = '". $stats['id'] ."'");
				}
				
				// Redirect
				redirect_page($_SERVER['HTTP_REFERER'], $lang['member_banned']);
			}
		}
	} elseif (isset($_POST['submit_remove'])) {
		if (!empty($_POST['unconfirmed_days']) && is_numeric($_POST['unconfirmed_days'])) {
			 // Remove members
			 $delete_members_query = mysql_query("DELETE FROM ". $tbl_prefix ."users WHERE status = '0' && joined <= '". (time() - (86400 *  $_POST['unconfirmed_days'])) ."'");
		
			// Redirect
			redirect_page($_SERVER['HTTP_REFERER'], $lang['member_deleted']);
		}
	}
	
	// Some variables
	$group_value = array (
		'1'	=>	'<font color="#0066CC">'. $lang['member'] .'</font>',
		'2'	=>	'<font color="#660099">'. $lang['administrator'] .'</font>'
	);
	$member_status_value = array (
		'0'	=>	'<font color="#996600">'. $lang['unconfirmed'] .'</font>',
		'1'	=>	'<font color="#66CC00">'. $lang['active'] .'</font>',
		'2'	=>	'<font color="#FF3300">'. $lang['banned'].'</font>'
	);
	$search_term = nohtml(escape_string($_REQUEST['t']));


	// Count the number of members and pages
	if (strlen($search_term))
		$members_number_query = mysql_query("SELECT COUNT(*) AS count FROM ". $tbl_prefix ."users WHERE username LIKE '%". $search_term ."%'");
	else
		$members_number_query = mysql_query("SELECT COUNT(*) AS count FROM ". $tbl_prefix ."users");
	
	$members_number = mysql_fetch_assoc($members_number_query);
	$start_here = ($page - 1) * 30;
	$pages_count = ceil($members_number['count'] / 30);
	
	// Build navigation menu
	$navigation = NULL;
	if ($page > 1) {
		$page_number  = $page - 1;
		$navigation .= '<a href="members.php?p='. $page_number .'&t='. $search_term .'">&lt;</a>';
	}
	for ($page_number = 1; $page_number <= $pages_count; $page_number++) {
		if ($page_number == $page) {
			$navigation .= ' <b>'. $page_number .'</b>';
    	} else {
	    	$navigation .= ' <a href="members.php?p='. $page_number .'&t='. $search_term .'">'. $page_number .'</a>';
		} 
	}
	if ($page < $pages_count) {
		$page_number  = $page + 1;
		$navigation .= ' <a href="members.php?p='. $page_number .'&t='. $search_term .'">&gt;</a>';
	}
	
	// Get members
	if (strlen($search_term))
		$members_query = mysql_query("SELECT userid, username, joined, status, usergroup FROM ". $tbl_prefix ."users WHERE username LIKE '%". $search_term ."%' ORDER BY username LIMIT ". $start_here .", 30");
	else
		$members_query = mysql_query("SELECT userid, username, joined, status, usergroup FROM ". $tbl_prefix ."users ORDER BY username LIMIT ". $start_here .", 30");
	$members = array ();
	while($members_row = mysql_fetch_assoc($members_query)) {
		$members[] = array (
			'id'			=>	$members_row['userid'],
			'username'		=>	nohtml($members_row['username']),
			'joined'		=>	mod_date($members_row['joined']),
			'group'			=>	$group_value[$members_row['usergroup']],
			'status'		=>	$member_status_value[$members_row['status']]
		);
	}
	
	// Replace variables
	$lang['delete_members_days'] = str_replace('{$days_number}', '<input type="text" name="unconfirmed_days" size="2" style="text-align: center;" />', $lang['delete_members_days']);

	$page_title = $lang['members'];

	// Load template
	template_members();
}

?>