<?php

session_start();

require ('includes/config.php');

//require ('templates/'. $settings['template'] .'/browse.template.php');

require ('languages/'. $settings['language'] .'/browse.lang.php');

$category_id = (int) $_GET['c'];
$page = $_GET['p'];

// Make sure nothing bad is submited
if (!is_numeric($category_id) && strlen($category_id)) {
	die('Bad hacker!!!');
}

if (empty($page) || !is_numeric($page)) {
	$page = '1';
}

// Load category information
$category_query = mysql_query("SELECT * FROM on_categories
			       WHERE catid = '". $category_id ."' && status = '1'
			       LIMIT 1");

$category_row = mysql_fetch_assoc($category_query);

// If no category found
if (empty($category_row))
	no_page();

// Lets check whether the user is logged in or not
if ($category_row['permissions'] == 2 && $user['status'] != '1')
	please_log_in();


// Category variables
$category = array (
	'id'		=>	$category_row['catid'],
	'title'		=>	$category_row['name']
);

// Count the number of files and pages
$files_number_query = mysql_query("SELECT count(fileid)
				   FROM on_files
				   WHERE category = '". $category_id ."' && status = '1'");
$files_number_row = mysql_fetch_assoc($files_number_query);

$start_here = ($page - 1) * $settings['browse_per_page'];
$pages_count = ceil($files_number_row['count(fileid)'] / $settings['browse_per_page']);

// Build navigation menu
$navigation = '<ul class="pagination pagination-centered">';
if ($pages_count > 1) {
	if ($page > 1) {
		$navigation .= '<li><a href="'. categoryurl($category_id, $category_row['name'], 1) .'">&lt;&lt;</a></li>
        <li><a href="'. categoryurl($category_id, $category_row['name'], ($page - 1)) .'">&lt;</a></li>';
	}
	for ($page_number = 1; $page_number <= $pages_count; $page_number++) {
		if ($page_number == $page) {
			$navigation .= '<li class="active"><span>'. $page_number .'</span></li>';
    	} else {
    		if ($page_number >= $page - 5 && $page_number <= $page + 5) {
				$navigation .= '<li><a href="'. categoryurl($category_id, $category_row['name'], $page_number) .'">'. $page_number .'</a></li>';
			}
		}
	}
	if ($page < $pages_count) {
		$navigation .= '<li><a href="'. categoryurl($category_id, $category_row['name'], ($page + 1)) .'">&gt;</a></li>
        <li><a href="'. categoryurl($category_id, $category_row['name'], $pages_count) .'">&gt;&gt;</a></li>';
	}
}
$navigation .= '</ul>';

//$files_query = mysql_query("SELECT fileid, title, description, icon, iconlocation,
//                            timesplayed, scores FROM ". $tbl_prefix ."files
//                            WHERE
//                            category = '". $category_id ."' && status = '1'
//                            ORDER BY title ASC LIMIT ". $start_here .", ". $settings['browse_per_page']);


//echo "SELECT fileid, title, description, icon, iconlocation,
//                            timesplayed, scores FROM ". $tbl_prefix ."files
//                            WHERE
//                            (category IN (SELECT catid FROM on_categories WHERE parentcategory = ".$category_id." )
//                            && status = '1')
//                            OR (category = '". $category_id ."' && status = '1')
//                            ORDER BY title ASC LIMIT ". $start_here .", ". $settings['browse_per_page'];


$files_query = mysql_query("SELECT fileid, video, title, title_en, description, icon, iconlocation, rating,
                            timesplayed, scores FROM on_files
                            WHERE
                            (category IN (SELECT catid FROM on_categories WHERE parentcategory = ".$category_id." )
                            && status = '1')
                            OR (category = '". $category_id ."' && status = '1')
                            ORDER BY dateadded DESC LIMIT ". $start_here .", ". $settings['browse_per_page']);


$files = array ();
while ($files_row = mysql_fetch_assoc($files_query)) {
	if ($files_row['iconlocation'] == '1') {
//		$image_url = $settings['siteurl'] .'/files/image/'. $files_row['icon'];
        $image_url = '/files/image/'. $files_row['icon'];
	} else {
		$image_url = $files_row['icon'];
	}
	//if (strlen($files_row['description']) > '80') {
	//    $files_row['description'] = substr($files_row['description'], 0, 77) .'...';
    //}
	$files[] = array (
		'id'			=>	$files_row['fileid'],
		'title'			=>	$files_row['title'],
		'video'			=>	$files_row['video'],
		'title_en'		=>	$files_row['title_en'],
		'rating'		=>	$files_row['rating'],
		'url'			=>	fileurl($files_row['fileid'],$files_row['title']),
		'description'	=>	cut_text($files_row['description'],250),
		'image'			=>	$image_url,
		'played'		=>	number_format($files_row['timesplayed']),
		'scores'		=>	$files_row['scores']
	);

}

// Get subcategories
if ($user['status'] == '1') {
	$subcategories_query = mysql_query("SELECT catid, name
					    FROM on_categories
					    WHERE status = '1' && parentcategory = '". $category_id ."'
					    ORDER BY catorder, name");
} else {
	$subcategories_query = mysql_query("SELECT catid, name
					   FROM on_categories
					   WHERE status = '1' && permissions = '1' && parentcategory = '". $category_id ."'
					   ORDER BY catorder, name");
}

while ($subcategories_row = mysql_fetch_assoc($subcategories_query)) {
	$subcategories[] = array (
		'id'	=>	$subcategories_row['catid'],
		'name'	=>	$subcategories_row['name']
	);
}

// Some META tags
$settings['sitedescription'] = $category_row['description'];
if (strlen($category_row['keywords'])) {
	$settings['sitekeywords'] = $settings['sitekeywords'] .', '. $category_row['keywords'];
}
$page_title = $category_row['name'];

// Browse: here are all the files displayed
//template_browse();

	global $settings, $lang, $category, $files, $navigation, $subcategories;
	$a = '1';
    $objImageGd = new Vi_Image_Gd();
	template_header();
	if (is_array($subcategories)) {
		echo '
      <div class="content_box_header m1p">
        <h1>РАЗДЕЛ "'.$category['title'].'" </h1>
      </div>
      <div class="content_box tableclass">';
      $i = 0;
		echo "<div  class='categ-titl'>";
		foreach ($subcategories as $key => $sub) {
		  ++$i;
          //echo $i;
			echo '
        <div  class="content_box_sub m2p">
        <img src="'. $settings['siteurl'] .'/images/category.png" with="24" height="24"
        title="'. $sub['name'] .'" alt="'. $sub['name'] .'" border="0" /><h2>
        <a href="'. categoryurl($sub['id'], $sub['name']) .'">
        <span class="cat10">'. $sub['name'] .'</span></a></h2>
        </div>
        <br />';
          if ($i == 4) {
           $i = 0;
           echo "</div><div class='categ-titl'>";
          }
		}
		echo '</div>'; // end categ-titl
		echo '
	  </div>';
	}
	//echo '<div class="content_box_header m1p">';
        //echo '<h1>РАЗДЕЛ "'.$category['title'].'" </h1>';
        //echo '<a href="'. $settings['siteurl'] .'/">'. $settings['sitename'] .'</a> > '. $category['title'];
      //echo '</div>
      echo '<div class="content_box">
	    <div class="dw4354345">
		  <center>
          '. $navigation .'
          </center>
		</div>
        <div class="clearfix"></div>
        ';
	foreach ($files as $file) {
		if ($a == '1') {
			echo '
		<div style="float: left; width: 49.5%;">';
			$a = '2';
    	} else {
    		echo '
		<div style="float: right; width: 49.5%;">';
			$a = '1';
		}
?>
		  <div class="browse_file_box">

<? if ($file['video']) { ?>

<div class="g_list_s" style="background:url('<?= $objImageGd->getImage($file['image'],170,170,0) ?>') 0 0 no-repeat ;">
<a title="<?= $file['title'] ?>" class="screenLink" href="<?= fileurl($file['id'],$file['title']) ?>">
<img style="display: block;" id="imprev" class="g_list_s_i" src="<?= $objImageGd->getImage($file['image'],200,190,0) ?>" width="170" height="170" title="<?= $file['title'] ?>" alt="<?= $file['title'] ?>" border="1" />
</a>
<input type="hidden" value="<?= $file['video'] ?>" name="gamePreview">
<input type="hidden" value="http://<?= $_SERVER['HTTP_HOST'] ?><?= $objImageGd->getImage($file['image'],170,170,0) ?>" name="imagePreview">
</div>

<? } else { ?>
  	      <div class="g_list_s_no">
	      <a href="<?= $file['url'] ?>"><img src="<?= $objImageGd->getImage($file['image'],200,190,0) ?>"
              width="170" height="170" title="Онлайн игра <?= $file['title'] ?> - <?= $file['title_en'] ?>."
	      alt="Онлайн игра <?= $file['title'] ?> - <?= $file['title_en'] ?>." border="1" /></a>
	      <?= ($file['scores'] == '1' ? '<br /><a href="'. scoresurl($file['id']) .'">'. $lang['scores'] .'</a>' : '') ?>
	      </div>
<? } ?>
			<div  class="m1p" style="">
			  <h3><a href="<?= $file['url'] ?>" class="file_link"><?= $file['title'] ?></a></h3><br />
              <span class="rating">Голосов : <?= $file['rating'] ?></span><br />
			  <?= $file['description'] ?><br />
			  <span class="played">(<?= $lang['played_times'] .' '. $file['played'] ?>)</span>
			</div>
			<div style="clear: both;"></div>
		  </div>
<?
		echo '</div>';
		if ($a == '1') {
			echo '
		<div style="clear: both;"></div>';
    	}
	}
	echo ($a == '2' ? '<div style="clear: both;"></div>' : '');

if ($page == '1')
    echo '<div class="desgpage1">'.$category_row['description_page1'] . '</div>';
		echo '<div class="dw4354345">
		  <center>
          '. $navigation .'
          </center>
		</div>
        <div class="clearfix"></div>
		<div style="text-align: right;">
		  <form name="search" action="'. $settings['siteurl'] .'/search.php" method="POST">
		    <input type="text" name="t" maxlength="25" size="20" />
		    <input type="hidden" name="c" value="', $category['id'] ,'" />
		    <input type="submit" value="'. $lang['search'] .'" />
		  </form>
		</div>
	  </div>';
	template_footer();


?>