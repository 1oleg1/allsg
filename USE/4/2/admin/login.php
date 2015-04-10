<?php

session_start();

require ('../includes/adminconfig.php');

require ('../languages/'. $settings['language'] .'/login.lang.php');

// Redirect non-admins
if ($user['status'] == '1' && $user['group'] == '2') {
	header ('Location: index.php');
	exit();
}

if (isset($_POST['submit_log_in'])) {
	// Failed login quota
	if ($guest_information_row['login_attempts'] >= $settings['failed_login_quota'] && $guest_information_row['last_login'] + 900 > time()) {
	    $log_in_error = $lang['used_up_failed_login_quota'];
	} else {
		if (empty($_POST['username'])) {
	    	$log_in_error = $lang['username_not_left_blank'];
		} elseif (strlen($_POST['username']) > 25) {
			$log_in_error = $lang['username_longer_than_25'];
		} elseif (empty($_POST['password'])) {
	    	$log_in_error = $lang['password_not_left_blank'];
		} else {
			// Check if user exists
			$check_user_query = mysql_query("SELECT userid, status, usergroup 
            FROM ". $tbl_prefix ."users 
            WHERE username = '". $_POST['username'] ."' && password = '". md5($_POST['password']) ."'");
			if (mysql_num_rows($check_user_query) > 0) {	
	    		$check_user_row = mysql_fetch_array($check_user_query);
		
				if ($check_user_row['status'] == '1' && $check_user_row['usergroup'] == '2') {
					// It is time to create session
		    		$_SESSION['userid'] = $check_user_row['userid'];
		    		$_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT']; 
			
					// Redirect
					redirect_page('index.php', $lang['now_logged_in']);
				} else {
					$log_in_error = $lang['you_not_administrator'];
				}
			} else {
				// Update login attempts
				if ($guest_information_row['last_login'] + 900 < time()) {
					$update_login_attempts_query = mysql_query("UPDATE ". $tbl_prefix ."online 
                    SET login_attempts = '1', last_login = '". time() ."' 
                    WHERE ip = '". $user['ip'] ."' && status = '0'");
				} else {
					$update_login_attempts_query = mysql_query("UPDATE ". $tbl_prefix ."online 
                    SET login_attempts = login_attempts + 1, last_login = '". time() ."' 
                    WHERE ip = '". $user['ip'] ."' && status = '0'");
				}
				
				$log_in_error = $lang['entered_invalid_username_password'];
			}
		}
	}
}

$page_title = $lang['log_in'];

// Load template
//template_log_in();
	global $settings, $lang, $log_in_error;
	
	// Include overall header
	template_overall_header();
	
	echo '

<body>
<center>

<div class="log_in_box">
  <div class="log_in_box_text">';
	if (strlen($log_in_error)) {
		echo '
  <div class="error_box">
    '. $log_in_error .'
  </div>';
	}
	echo '
  <!--  Log In-->
  <form action="" method="post">
    '. $lang['username'] .'<br />
    <center><input class="form-control" type="text" name="username" /></center><br />
    '. $lang['password'] .'<br />
    <center><input class="form-control" type="password" name="password" /></center><br /><br />
    <input class="btn btn-primary btn-large" type="submit" name="submit_log_in" value="'. $lang['log_in'].'" style="border: 1px solid #336699;" />
  </form>
  <!-- Log In-->
  </div>
</div>


</center>
</body>
</html>';

?>