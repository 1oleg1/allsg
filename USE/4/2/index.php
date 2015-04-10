<?php

session_start();

require ('includes/config.php');

// IBPro Games Scores support by Ram85 (www.thinkarcade.com)
if ($_GET['act'] == 'Arcade' && $_GET['do'] == 'newscore') {
	define ('SCORE', 'simple');

	// Get the information from game
	$file_name = (!empty($_POST['game_name'])) ? trim($_POST['game_name']) : trim($_POST['gname']);
	$file_name = $file_name .'.swf';
	$file_score = (!empty($_POST['score'])) ? doubleval($_POST['score']) : doubleval($_POST['gscore']);

	// And lets submit score
	include ('includes/submitscore.php');
} elseif ($_GET['autocom'] == 'arcade') {
	if ($_GET['do'] == 'verifyscore') {
		// Some stuff for game
		$randchar = rand(1, 200);
		$randchar2 = rand(1, 200);

		$_SESSION['verify_score'] = array($randchar, $randchar2, time());

		echo '&randchar=', $randchar ,'&randchar2=', $randchar2 ,'&savescore=1&blah=OK';
	} elseif ($_GET['do'] == 'savescore') {
		// Lets submit score
		define ('SCORE', 'v3');
		include ('includes/submitscore.php');
	}
	exit();
}

$main = get_cache('main_'. $user['status']);
if (!$main) {
	if ($user['status'] == '1') {
		$categories_query = mysql_query("SELECT * FROM on_categories
                                        WHERE status = '1' &&
                                        parentcategory = '0' ORDER BY catorder, name");
	} else {
		$categories_query = mysql_query("SELECT * FROM on_categories
                                         WHERE status = '1' &&
                                         permissions = '1' && parentcategory = '0' ORDER BY catorder, name");
	}

	$main['categories'] = array(); $main['files'] = array();

	while($category_row = mysql_fetch_assoc($categories_query)) {
		// Get files in this category
		$files_query = mysql_query("SELECT title, video,
                                    icon, iconlocation, fileid, description, timesplayed, rating
                                    FROM on_files WHERE category  = '". $category_row['catid'] ."' &&
                                    status = '1' || category
                                    IN (SELECT catid FROM on_categories
                                    WHERE status = '1' && parentcategory = '". $category_row['catid'] ."') &&
                                    status = '1' ORDER BY dateadded DESC,
                                    fileid DESC LIMIT ". $settings['max_files_index']);
    	if (mysql_num_rows($files_query)) {
    		while($file = mysql_fetch_assoc($files_query)) {
	    		if ($file['iconlocation'] == '1') {
//	        		$imageurl = $settings['siteurl'] .'/files/image/'. $file['icon'];
	        		$imageurl = '/files/image/'. $file['icon'];
        		} else {
	        		$imageurl = $file['icon'];
        		}
	    		$file_description = $file['description'];
        		$file_rating = $file['rating'];
        		//if ($file_rating == '0.00') {
				//	$file_rating = $lang['not_yet_rated'];
				//}
        		$main['files'][$category_row['catid']][] = array (
					'title'			=>	$file['title'],
					'video'			=>	$file['video'],
					'id'			=>	$file['fileid'],
					'image'			=>	$imageurl,
					'played'		=>	number_format($file['timesplayed']),
					'description'	=>	$file_description,
					'rating'		=>	$file_rating,
					'stars'			=>	stars($file['rating'])
				);
	    	}
	    	$main['categories'][] = array (
				'title'	=>	$category_row['name'],
				'id'	=>	$category_row['catid']
			);
		}
	}

	// Write cache file
	write_cache('main_'. $user['status'], $main);
}

//template_index();
	global $settings, $lang;

	// Include header
	template_header();

	echo '
      <div class="content_box_header m1p">
        <h1>GAME2OK - ЛУЧШИЕ ИГРЫ ОНЛАЙН БЕСПЛАТНО</h1>
      </div>';
//      <div class="content_box">
//	    '. $lang['index_box_text'] .'
//	  </div>';
	if ($settings['news'] == '1') {
		echo '
      <div class="content_box_header">
        '. $lang['news'] .'
      </div>
      <div class="content_box">';
    	template_news_index();
		echo '
	  </div>';
	}
// Files: these files are displayed on your index page
//	template_files_index();
	global $settings, $lang, $main;
	$a = '1';
	$objImageGd = new Vi_Image_Gd();
	// Display all categories
	foreach ($main['categories'] as $category) {
		if ($a == '1') {
			echo '
	  <div style="float: left; width: 49.6%;">';
			$a = '2';
    	} else {
    		echo '
	  <div style="float: right; width: 49.6%;">';
		$a = '1';
		}
		echo '
		<div class="content_box_header m1p">
        <a href="'. categoryurl($category['id'], $category['title']) .'".>
          <h2> НОВИНКИ В РАЗДЕЛЕ ' . $category['title'] .'</h2>
        </a>
		</div>
        <div class="content_box">';
		foreach ($main['files'][$category['id']] as $file) {
			echo '
         <div class="browse_file_box1">';

if ($file['video']) {
?>
<div class="g_list_s" style="background:url('<?= $objImageGd->getImage($file['image'],170,170,0) ?>') 0 0 no-repeat ;">

<a class="screenLink" href="<?= fileurl($file['id'],$file['title']) ?>">
<img style="display: block;" id="imprev" class="g_list_s_i" src="<?= $objImageGd->getImage($file['image'],200,190,0) ?>" width="170" height="170" title="<?= $file['title'] ?>" alt="<?= $file['title'] ?>" border="1" />
</a>
<input type="hidden" value="<?= $file['video'] ?>" name="gamePreview">
<input type="hidden" value="http://<?= $_SERVER['HTTP_HOST'] ?><?= $objImageGd->getImage($file['image'],170,170,0) ?>" name="imagePreview">

</div>
<? } else {
echo '<div class="g_list_s_no">
		    <a href="'. fileurl($file['id'],$file['title']) .'">
            <img src="'. $objImageGd->getImage($file['image'],200,190,0) .'" width="170" height="170" alt="'. $file['title'] .'" border="1" />
            </a>
		  </div>';
}

			echo '
		  <div style="" class="m1p">
            <h3><a href="'. fileurl($file['id'],$file['title']) .'" class="file_link">'. $file['title'] .'</a></h3><br />
			<span class="rating">Голосов : '.$file['rating'] .'</span><br />
            '. cut_text($file['description'],250) .'<br />
            <span class="played">('. $lang['played_times'] .' '. $file['played'] .')</span>
		  </div>
          <div style="clear: both;"></div>
         </div>
		<div style="clear: both; margin-bottom: 10px;"></div>';
		}
		echo '
          <div align="right">
              <a href="'. categoryurl($category['id'], $category['title']) .'">Перейти в раздел '. $category['title'] .' ></a>
          </div>
        </div>
	  </div>';
		if ($a == '1') {
			echo '
	  <div style="clear: both;"></div>';
    	}
	}

	// Include footer
	template_footer();

?>