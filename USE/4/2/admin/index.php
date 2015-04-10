<?php

session_start();

require ('../includes/adminconfig.php');

// Redirect non-admins
if ($user['status'] != '1' || $user['group'] != '2') {
	header ('Location: '. $settings['siteurl'] .'/admin/login.php');
	exit();
}

switch (@$_GET['a']) {
	case 'statistics':
		// Get statistics
		$statistics_query = mysql_query("SELECT * FROM ". $tbl_prefix ."statistics ORDER BY stats_id DESC");
		while ($statistics_row = mysql_fetch_array($statistics_query)) {
			$statistics[] = array (
				'date'			=>	mod_date($statistics_row['stats_date']),
				'played'		=>	number_format($statistics_row['played_today']),
				'total_played'	=>	number_format($statistics_row['total_played']),
				'total_files'	=>	number_format($statistics_row['total_files']),
				'total_members'	=>	number_format($statistics_row['total_members'])
			);
		}
	
		$page_title = $lang['statistics'];
	
		// Load template
		template_statistics();
		break;
	case 'clear_stats':
		$clear_stats_query = mysql_query("DELETE FROM ". $tbl_prefix ."statistics WHERE stats_id != '". $stats['id'] ."'");
	
		// Redirect
		redirect_page($settings['siteurl'] .'/admin/index.php?a=statistics', $lang['stats_cleared']);
		break;
	case 'empty_stats':
		// Delete statistics
		$clear_stats_query = mysql_query("DELETE FROM ". $tbl_prefix ."statistics");
	
		// Count files
		$recount_files_query = mysql_query("SELECT count(fileid) AS files_count FROM ". $tbl_prefix ."files WHERE status = '1'");
		$recount_files_row = mysql_fetch_array($recount_files_query);
	
		// Count members
		$recount_members_query = mysql_query("SELECT count(userid) AS members_count FROM ". $tbl_prefix ."users WHERE status = '1'");
		$recount_members_row = mysql_fetch_array($recount_members_query);
	
		// onArcade 2
	
		// Insert into statistics table
		$insert_statistics_query = mysql_query("INSERT INTO ". $tbl_prefix ."statistics SET stats_date = '". time() ."', played_today = '0', total_played = '0', total_files = '". $recount_files_row['files_count'] ."', total_members = '". $recount_members_row['members_count'] ."'");
	
		// Redirect
		redirect_page($settings['siteurl'] .'/admin/index.php?a=statistics', $lang['stats_nulled']);
		break;
	case 'recount_files':
		$recount_files_query = mysql_query("SELECT count(fileid) AS files_count FROM ". $tbl_prefix ."files WHERE status = '1'");
		$recount_files_row = mysql_fetch_array($recount_files_query);
	
		// Update statistics table
		$update_stats_query = mysql_query("UPDATE ". $tbl_prefix ."statistics SET total_files = '". $recount_files_row['files_count'] ."' WHERE stats_id = '". $stats['id'] ."'");
	
		// Redirect
		redirect_page($settings['siteurl'] .'/admin/index.php', $lang['new_files_count'] .' '. $recount_files_row['files_count']);
		break;
	case 'recount_members':
		$recount_members_query = mysql_query("SELECT count(userid) AS members_count FROM ". $tbl_prefix ."users WHERE status = '1'");
		$recount_members_row = mysql_fetch_array($recount_members_query);
	
		// Update statistics table
		$update_stats_query = mysql_query("UPDATE ". $tbl_prefix ."statistics SET total_members = '". $recount_members_row['members_count'] ."' WHERE stats_id = '". $stats['id'] ."'");
	
		// Redirect
		redirect_page($settings['siteurl'] .'/admin/index.php', $lang['new_members_count'] .' '. $recount_members_row['members_count']);
		break;
	case 'clear_cache':
		if ($cache_directory = opendir('../cache/')) {
			while ($cache_file = readdir($cache_directory)) {
				if ($cache_file != '.' && $cache_file != '..' && $cache_file != 'index.html') {
					unlink('../cache/'. $cache_file);
				}
			}
			closedir($cache_directory);
		}
		
		// Redirect
		redirect_page($settings['siteurl'] .'/admin/index.php', $lang['cache_cleared']);
		break;
	default:
	
		// Get the number of unapproved files
		$unapproved_files_query = mysql_query("SELECT count(fileid) FROM ". $tbl_prefix ."files WHERE status = '0'");
		$unapproved_files_row = mysql_fetch_array($unapproved_files_query);
		if ($unapproved_files_row['count(fileid)'] > 0) {
			$lang['have_unapproved_files'] = str_replace('{$unapproved_files}', $unapproved_files_row['count(fileid)'], $lang['have_unapproved_files']);
			$text['unapproved_files'] = TRUE;
		}

		// Get the number of unapproved links
		$unapproved_links_query = mysql_query("SELECT count(linkid) FROM ". $tbl_prefix ."links WHERE status = '0'");
		$unapproved_links_row = mysql_fetch_array($unapproved_links_query); 
		if ($unapproved_links_row['count(linkid)'] > 0) {
			$lang['have_unapproved_links'] = str_replace('{$unapproved_links}', $unapproved_links_row['count(linkid)'], $lang['have_unapproved_links']);
			$text['unapproved_links'] = TRUE;
		}

		// Get the number of unapproved comments
		$unapproved_comments_query = mysql_query("SELECT count(commentid) FROM ". $tbl_prefix ."comments WHERE status = '0'");
		$unapproved_comments_row = mysql_fetch_array($unapproved_comments_query);
		if ($unapproved_comments_row['count(commentid)'] > 0) {
			$lang['have_unapproved_comments'] = str_replace('{$unapproved_comments}', $unapproved_comments_row['count(commentid)'], $lang['have_unapproved_comments']);
			$text['unapproved_comments'] = TRUE;
		}
	
		// Get the number of broken file reports
		$broken_files_query = mysql_query("SELECT count(report_id) FROM ". $tbl_prefix ."report_broken");
		$broken_files_row = mysql_fetch_array($broken_files_query);
		if ($broken_files_row['count(report_id)'] > 0) {
			$lang['have_broken_file_reports'] = str_replace('{$broken_files}', $broken_files_row['count(report_id)'], $lang['have_broken_file_reports']);
			$text['broken_files'] = TRUE;
		}

		$page_title = $lang['admincp_index'];

		// Load on Arcade template
		template_main();
}

?>