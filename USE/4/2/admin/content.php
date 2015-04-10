<?php

session_start();

require ('../includes/adminconfig.php');

// Redirect non-admins
if ($user['status'] != '1' || $user['group'] != '2') {
	header ('Location: '. $settings['siteurl'] .'/admin/login.php');
	exit();
}

$admincp_action = @$_GET['a'];

if ($admincp_action == 'tags') {
	if (isset($_POST['submit_tags'])) {

		if (isset($_POST['tags_id'])) {
			if ($_POST['tags_action'] == 'delete') {
				// Delete marked categories
				foreach ($_POST['tags_id'] as $key => $val) {
					$delete_tags[] = (int) $key;
				}
				if (is_array($delete_tags)) {
					// Delete categories
					$delete_tags_query = mysql_query("DELETE FROM on_search_tags 
                                                      WHERE id IN (". implode(', ', $delete_tags) .")");
					$delete_tags_query = mysql_query("DELETE FROM on_files_search_tags 
                                                      WHERE tag IN (". implode(', ', $delete_tags) .")");                                                      
				}
				// Redirect
				redirect_page($settings['siteurl'] .'/admin/content.php?a=tags', 'Тег удален');
			}
		}
	}

	$tags = array ();
	// Get categories
	$tags_query = mysql_query("
		SELECT * FROM on_search_tags ORDER BY tags");
	while ($tag_row = mysql_fetch_array($tags_query)) {
		$tags[] = array (
			'id'				=>	$tag_row['id'],
			'tags'				=>	$tag_row['tags'],
		);
	}

$page_title = 'Поисковые теги';
// Шаблон списка категорий
//template_categories();
	//global $settings, $lang, $tags;
	// Include header
	template_header();

	echo '
  <div class="admin_left">';
	template_content_menu();
	echo '
  </div>
  <div class="admin_right">
    <div class="content_box_2">
      <div class="content_box_2_header">Поисковые теги</div>
      <div class="box_text">
        <form name="form" action="" method="post">
        <div style="float: left; width: 5%; text-align: center;"><b>№</b></div>
        <div style="float: left; width: 25%; text-align: center;"><b>Теги</b></div>
        <div style="float: left; width: 25%; text-align: center;"><b>Редактировать</b></div>        
	 <div style="float: left; width: 5%; text-align: center;">
     <input type="checkbox" name="all" onclick="check_all();" /></div>
        <div class="line"></div>';
	foreach ($tags as $tag) {
		echo '
        <div style="float: left; width: 5%; text-align: center;">'. $tag['id'] .'</div>        
        <div style="float: left; width: 25%; text-align: left;">
	<a href="'. $settings['siteurl'].'/tag/'. $tag['id'].'" target="_blank">'. $tag['tags'] .'</a></div>
	 <div style="float: left; width: 25%; text-align: center;">
	 <a href="content.php?a=edit_tag&c='. $tag['id'] .'">Редактировать</a></div>	 

	 <div style="float: left; width: 5%; text-align: center;">
	 <input type="checkbox" name="tags_id['. $tag['id'] .']" value="ok" /></div>
        <div class="line"></div>';
	}
	echo '
		<div align="right">
			  <select name="tags_action">
				<option value="delete">Удалить</option>
			  </select>
			<input type="submit" name="submit_tags" value="Выполнить" />
		</div>
        </form>
  	  </div>
  	</div>
  </div>
  <div style="clear: both;"></div>
  ';

	// Include footer
	template_footer();
// -------------------- конец шаблона тегов ----------------


// -------------------- начало шаблона редатирования тега ----------------
} elseif ($admincp_action == 'edit_tag') {
	$t_id = (int) $_GET['c'];
	if (isset($_POST['submit_edit_tag'])) {
		// Update category information
		$edit_category_query = mysql_query("UPDATE on_search_tags
                    SET tags = '". $_POST['title'] ."'
                    WHERE id = '". $t_id ."'");

		// Redirect
		redirect_page($settings['siteurl'] .'/admin/content.php?a=edit_tag&c='. $t_id, 'Тег изменен');
	}

	$category_query = mysql_query("SELECT * FROM on_search_tags
				       WHERE id = '". $t_id ."' LIMIT 1");
	$category_row = mysql_fetch_assoc($category_query);

	$edit_category = array (
		'title'		=>	$category_row['tags'],
		'id'		=>	$category_row['id'],
	);
	$page_title = 'Редактирование тега';
	// Include header
	template_header();

	echo '
  <div class="admin_left">';
	template_content_menu();
	echo '
  </div>
  <div class="admin_right">
    <div class="content_box_2">
      <div class="content_box_2_header">
        Редактирование тега номер '.$edit_category['id'].'
      </div>
      <div class="box_text">
        <form action="" method="POST" name="form" onsubmit="return verify_add()">
		  <div class="content_text_left" style="width: 20%;">Название:</div>
		  <div class="content_text_right" style="width: 80%;">
          <input type="text" name="title" size="65" value="'. $edit_category['title'] .'" /></div>
		  <div style="clear: both;"></div>	  
		  <div align="center">
		    <input type="submit" name="submit_edit_tag" value="Сохранить" />
		  </div>
		</form>
  	  </div>
  	</div>
  </div>
  <div style="clear: both;"></div>
  ';
	// Include footer
	template_footer();
// конец шаблона редактирования тега

// -------------------- начало шаблона добавления тегов ----------------
} elseif ($admincp_action == 'add_tags') {
	if (isset($_POST['submit_tag'])) {
		if (empty($_POST['tag'])) {
			$add_tag['error'] = 'Не задан поисковый тег';
		} else {
			// Check if category is already in the database
    		$tag_check_query = mysql_query("SELECT count(id)
                FROM on_search_tags
                WHERE tags = '". $_POST['tag'] ."'");
    		$tag_check_row = mysql_fetch_assoc($tag_check_query);
    		if ($tag_check_row['count(id)'] > '0') {
				$add_tag['error'] = 'Этот тег уже есть';
    		} else {
    			// Inser category into database
    			$add_tag_query = mysql_query("INSERT INTO on_search_tags
                SET tags = '". $_POST['tag'] ."'");
				// Redirect
				redirect_page($settings['siteurl'] .'/admin/content.php?a=tags', 'Тег добавлен');
    		}
    	}
	}

	$page_title = "Создать поисковый тег";

	template_header();

	echo '<div class="admin_left">';
	template_content_menu();
	echo '</div><div class="admin_right">';
	if (strlen(@$add_tag['error'])) {
		echo '<div class="error_box">'. $add_tag['error'] .'</div>';
	}
	echo '
    <div class="content_box_2">
      <div class="content_box_2_header">
        Создать категорию
      </div>
      <div class="box_text">
        <form action="" method="POST" name="form" onsubmit="return verify_add()">
		  <div class="content_text_left" style="width: 20%;">Название тега:</div>
		  <div class="content_text_right" style="width: 80%;"><input type="text" name="tag" size="35" /></div>
		  <div style="clear: both;"></div>
		  <div align="center">
		    <input type="submit" name="submit_tag" value="Добавить" />
		  </div>
		</form>
  	  </div>
  	</div>
  </div>
  <div style="clear: both;"></div>
  ';

	// Include footer
	template_footer();
// -------------------- конец шаблона добавления тегов ----------------
} elseif ($admincp_action == 'categories') {
	if (isset($_POST['submit_categories'])) {
		// Update categories order
		if (isset($_POST['cat_order'])) {
			foreach ($_POST['cat_order'] as $key => $val) {
				$update_categories_query = mysql_query("UPDATE on_categories SET catorder = '". (int) $val ."' WHERE catid = '". (int) $key ."'");
			}
		}

		if (isset($_POST['category_id'])) {
			if ($_POST['categories_action'] == 'delete') {
				// Delete marked categories
				foreach ($_POST['category_id'] as $key => $val) {
					$delete_categories[] = (int) $key;
				}
				if (is_array($delete_categories)) {
					// Delete categories
					$delete_categories_query = mysql_query("DELETE FROM on_categories WHERE catid IN (". implode(', ', $delete_categories) .")");
					$delete_categories_query = mysql_query("DELETE FROM on_files_categories WHERE category IN (". implode(', ', $delete_categories) .")");                    
				}

				// Redirect
				redirect_page($settings['siteurl'] .'/admin/content.php?a=categories', $lang['categories_deleted']);
			} elseif ($_POST['categories_action'] == 'active') {
				// Mark marked categories active
				foreach ($_POST['category_id'] as $key => $val) {
					$update_categories[] = (int) $key;
				}
				if (is_array($update_categories)) {
					$update_categories_status_query = mysql_query("UPDATE on_categories 
                                                                   SET status = '1' 
                                                                   WHERE catid 
                                                                   IN (". implode(', ', $update_categories) .")");
				}
				// Redirect
				redirect_page($settings['siteurl'] .'/admin/content.php?a=categories', $lang['categories_marked_active']);
			} elseif ($_POST['categories_action'] == 'inactive') {
				// Mark marked categories inactive
				foreach ($_POST['category_id'] as $key => $val) {
					$update_categories[] = (int) $key;
				}
				if (is_array($update_categories)) {
					$update_categories_status_query = mysql_query("UPDATE on_categories SET status = '0' WHERE catid IN (". implode(', ', $update_categories) .")");
				}

				// Redirect
				redirect_page($settings['siteurl'] .'/admin/content.php?a=categories', $lang['categories_marked_inactive']);
			}
		}
	}
	$category_status_value = array (
		'0'	=>	'<span class="label label-danger">'. $lang['inactive'] .'</span>',
		'1'	=>	'<span class="label label-success">'. $lang['active'] .'</span>'
	);
	$category_permissions_value = array (
		'1'	=>	$lang['everybody'],
		'2'	=>	$lang['members_only']
	);

	$categories = array ();
	// Get categories
	$categories_query = mysql_query("
		SELECT
			cat.*, parent.name AS parent_category
		FROM
			on_categories AS cat
			LEFT JOIN on_categories AS parent ON (parent.catid = cat.parentcategory)
		ORDER BY name");
	while ($categories_row = mysql_fetch_array($categories_query)) {
		$categories[] = array (
			'id'				=>	$categories_row['catid'],
			'title'				=>	$categories_row['name'],
			'permissions'		=>	$category_permissions_value[$categories_row['permissions']],
			'status'			=>	$category_status_value[$categories_row['status']],
			'order'				=>	$categories_row['catorder'],
			'parent_category'	=>	(strlen($categories_row['parent_category']) ? $categories_row['parent_category'] : $lang['none'])
		);
	}

	$page_title = $lang['categories'];

// Шаблон списка категорий
//template_categories();
	global $settings, $lang, $categories;

	// Include header
	template_header();

	echo '
  <div class="admin_left">';
	template_content_menu();
	echo '
  </div>
  <div class="admin_right">
    <div class="content_box_2">
      <div class="content_box_2_header">
        '. $lang['categories'] .'
      </div>
      <div class="box_text">
        <form name="form" action="" method="post">
        <div style="float: left; width: 25%; text-align: center;"><b>'. $lang['title'] .'</b></div>
	<div style="float: left; width: 15%; text-align: center;"><b>'. $lang['permissions'] .'</b></div>
	 <div style="float: left; width: 15%; text-align: center;"><b>'. $lang['status'].'</b></div>
	 <div style="float: left; width: 15%; text-align: center;"><b>'. $lang['category_order'] .'</b></div>
	 <div style="float: left; width: 15%; text-align: center;"><b>'. $lang['parent_category'] .'</b></div>
	 <div style="float: left; width: 10%; text-align: center;">&nbsp;</div>
	 <div style="float: left; width: 5%; text-align: center;"><input type="checkbox" name="all" onclick="check_all();" /></div>
        <div class="line"></div>';
	foreach ($categories as $category) {
		echo '
        <div style="float: left; width: 25%; text-align: left;">
	<a href="'. categoryurl($category['id'], $category['title']) .'" target="_blank">'. $category['title'] .'</a></div>
	 <div style="float: left; width: 15%; text-align: center;">'. $category['permissions'] .'</div>
	 <div style="float: left; width: 15%; text-align: center;">'. $category['status'] .'</div>
	 <div style="float: left; width: 15%; text-align: center;">
	 <input type="text" name="cat_order['. $category['id'] .']" size="1" value="'. $category['order'] .'" style="text-align: center;" /></div>
	 <div style="float: left; width: 15%; text-align: center;">'. $category['parent_category'] .'</div>
	 <div style="float: left; width: 10%; text-align: center;">
	 <a href="content.php?a=edit_category&c='. $category['id'] .'">'. $lang['edit'] .'</a></div>
	 <div style="float: left; width: 5%; text-align: center;">
	 <input type="checkbox" name="category_id['. $category['id'] .']" value="ok" /></div>
        <div class="line"></div>';
	}
	echo '
		<div align="right">
			  <select name="categories_action">
				<option value="delete">'. $lang['delete'] .'</option>
				<option value="active">'. $lang['mark_active'] .'</option>
				<option value="inactive">'. $lang['mark_inactive'] .'</option>
			  </select>
			<input type="submit" name="submit_categories" value="'. $lang['go'] .'" />
		</div>
        </form>
  	  </div>
  	</div>
  </div>
  <div style="clear: both;"></div>
  ';

	// Include footer
	template_footer();
// -------------------- конец шаблона категории ----------------
} elseif ($admincp_action == 'add_category') {
	if (isset($_POST['submit_category'])) {
		if (empty($_POST['title'])) {
			$add_category['error'] = $lang['title_not_blank'];
		} else {
			// Check if category is already in the database
    		$category_check_query = mysql_query("SELECT count(catid)
                FROM on_categories
                WHERE name = '". $_POST['title'] ."'");
    		$category_check_row = mysql_fetch_assoc($category_check_query);
    		if ($category_check_row['count(catid)'] > '0') {
				$add_category['error'] = $lang['category_exists'];
    		} else {
    			// Inser category into database
    			$add_category_query = mysql_query("INSERT INTO on_categories
                SET name = '". $_POST['title'] ."',
                description = '". $_POST['description'] ."',
		description_page1 = '". $_POST['description_page1'] ."',
                keywords = '". $_POST['keywords'] ."',
                permissions = '". $_POST['permissions'] ."',
                status = '". $_POST['category_status'] ."',
                catorder = '". $_POST['order'] ."',
                parentcategory = '". $_POST['parent_category'] ."'");

				// Redirect
				redirect_page($settings['siteurl'] .'/admin/content.php?a=categories', $lang['category_added']);
    		}
    	}
	}

	// Get categories selection
	$categories_query = mysql_query("SELECT catid, name
				FROM on_categories
                WHERE parentcategory = 0
				ORDER BY catorder, name");
	$add_category['categories'] = '<select name="parent_category">
  <option value="0">'. $lang['none'] .'</option>';
	while ($categories_row = mysql_fetch_assoc($categories_query)) {
   	$add_category['categories'] .= '<option value="'. $categories_row['catid'] .'"><b>'. $categories_row['name'] .'</b></option>';
	}
	$add_category['categories'] .= '</select>';

	$page_title = "Создать категорию";

// Load template
// начало шаблона добавления категории
	global $settings, $lang, $add_category;

	// Include header
	template_header();

	echo '
  <div class="admin_left">';
	template_content_menu();
	echo '
  </div>
  <div class="admin_right">';
	if (strlen($add_category['error'])) {
		echo '
    <div class="error_box">
      '. $add_category['error'] .'
    </div>';
	}
	echo '
    <div class="content_box_2">
      <div class="content_box_2_header">
        Создать категорию
      </div>
      <div class="box_text">
        <form action="" method="POST" name="form" onsubmit="return verify_add()">
		  <div class="content_text_left" style="width: 20%;">Название:</div>
		  <div class="content_text_right" style="width: 80%;"><input type="text" name="title" size="35" /></div>
		  <div class="content_text_left" style="width: 20%;">Путь к картинке:</div>
		  <div class="content_text_right" style="width: 80%;"><input type="text" name="filetitle" size="35" /></div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">Описание:</div>
		  <div class="content_text_right" style="width: 80%;"><textarea name="description" rows="1" cols="35"></textarea></div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">Ключевые слова:</div>
		  <div class="content_text_right" style="width: 80%;"><textarea name="keywords" rows="1" cols="35"></textarea></div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">Описание для первой страницы:</div>
		  <div class="content_text_right" style="width: 80%;"><textarea name="description_page1" rows="10" cols="65"></textarea></div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">Права на доступ:</div>
		  <div class="content_text_right" style="width: 80%;">
		    <select name="permissions">
			  <option value="1">Все</option>
			  <option value="2">Зарегистрированные</option>
			</select>
		  </div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">'. $lang['status'].'</div>
		  <div class="content_text_right" style="width: 80%;">
		    <select name="category_status">
			  <option value="1">Вкл</option>
			  <option value="0">Выкл</option>
			</select>
		  </div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">Сортировка (какая по счету)</div>
		  <div class="content_text_right" style="width: 80%;"><input type="text" name="order" size="1" value="0" /></div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">Родительская категория</div>
		  <div class="content_text_right" style="width: 80%;">
		    '. $add_category['categories'] .'
		  </div>
		  <div style="clear: both;"></div>
		  <div align="center">
		    <input type="submit" name="submit_category" value="'. $lang['submit'] .'" />
		  </div>
		</form>
  	  </div>
  	</div>
  </div>
  <div style="clear: both;"></div>
  ';

	// Include footer
	template_footer();
// конец шаблона добавления категории
//	template_add_category();
} elseif ($admincp_action == 'edit_category') {
	$category_id = (int) $_GET['c'];
	if (isset($_POST['submit_edit_category'])) {
		// Update category information
		$edit_category_query = mysql_query("UPDATE on_categories
                    SET name = '". $_POST['title'] ."',
		    filename = '". $_POST['filetitle'] ."',
                    description = '". $_POST['description'] ."',
                    description_page1 = '". $_POST['description_page1'] ."',
                    keywords = '". $_POST['keywords'] ."',
                    permissions = '". $_POST['permissions'] ."',
                    status = '". $_POST['category_status'] ."',
                    catorder = '". $_POST['order'] ."',
                    parentcategory = '". $_POST['parent_category'] ."'
                    WHERE catid = '". $category_id ."'");

		// Redirect
		redirect_page($settings['siteurl'] .'/admin/content.php?a=edit_category&c='. $category_id, $lang['category_edited']);
	}

	$category_query = mysql_query("SELECT * FROM on_categories
				       WHERE catid = '". $category_id ."' LIMIT 1");
	$category_row = mysql_fetch_assoc($category_query);

	$edit_category = array (
		'title'			=>	$category_row['name'],
		'filetitle'		=>	$category_row['filename'],
		'description'	=>	$category_row['description'],
		'description_page1'	=>	$category_row['description_page1'],
		'keywords'		=>	$category_row['keywords'],
		'permissions'	=>	$category_row['permissions'],
		'status'		=>	$category_row['status'],
		'order'			=>	$category_row['catorder']
	);



	// Get categories selection
	$categories_query = mysql_query("SELECT catid, name FROM on_categories ORDER BY catorder, name");
	$edit_category['categories'] = '
<select name="parent_category">
  <option value="0">'. $lang['none'] .'</option>';
	while ($categories_row = mysql_fetch_assoc($categories_query)) {
		if ($category_row['parentcategory'] == $categories_row['catid']) {
    		$edit_category['categories'] .= '
  <option value="'. $categories_row['catid'] .'" selected>'. $categories_row['name'] .'</option>';
		} else {
			$edit_category['categories'] .= '
  <option value="'. $categories_row['catid'] .'">'. $categories_row['name'] .'</option>';
		}
	}
	$edit_category['categories'] .= '
</select>';


	$page_title = $lang['edit_category'];

// Load on Arcade 2 template
//template_edit_category();
// Редактирование категории
	global $settings, $lang, $edit_category;

	// Include header
	template_header();

	echo '
  <div class="admin_left">';
	template_content_menu();
	echo '
  </div>
  <div class="admin_right">
    <div class="content_box_2">
      <div class="content_box_2_header">
        '. $lang['edit_category'] .'
      </div>
      <div class="box_text">
        <form action="" method="POST" name="form" onsubmit="return verify_add()">
		  <div class="content_text_left" style="width: 20%;">Название:</div>
		  <div class="content_text_right" style="width: 80%;"><input type="text" name="title" size="65" value="'. $edit_category['title'] .'" /></div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">Путь к картинке:</div>
		  <div class="content_text_right" style="width: 80%;"><input type="text" name="filetitle" size="65" value="'. $edit_category['filetitle'] .'" /></div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">Описание:</div>
		  <div class="content_text_right" style="width: 80%;"><textarea name="description" rows="5" cols="65">'. $edit_category['description'] .'</textarea></div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">Ключевые слова:</div>
		  <div class="content_text_right" style="width: 80%;"><textarea name="keywords" rows="5" cols="65">'. $edit_category['keywords'] .'</textarea></div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">Описание для первой страницы:</div>
		  <div class="content_text_right" style="width: 80%;"><textarea name="description_page1" rows="10" cols="65">'. $edit_category['description_page1'] .'</textarea></div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">Права на доступ</div>
		  <div class="content_text_right" style="width: 80%;">
		    <select name="permissions">
			  <option value="1" '. ($edit_category['permissions'] == '1' ? 'selected' : '') .'>Все</option>
			  <option value="2" '. ($edit_category['permissions'] == '2' ? 'selected' : '') .'>Зарегестрированные</option>
			</select>
		  </div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">Статус</div>
		  <div class="content_text_right" style="width: 80%;">
		    <select name="category_status">
			  <option value="1" '. ($edit_category['status'] == '1' ? 'selected' : '') .'>Активна</option>
			  <option value="0" '. ($edit_category['status'] == '0' ? 'selected' : '') .'>Отключена</option>
			</select>
		  </div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">Сортировка (какая по счету)</div>
		  <div class="content_text_right" style="width: 80%;"><input type="text" name="order" size="1" value="'. $edit_category['order'] .'" /></div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">Родительская категория</div>
		  <div class="content_text_right" style="width: 80%;">
		    '. $edit_category['categories'] .'
		  </div>
		  <div style="clear: both;"></div>
		  <div align="center">
		    <input type="submit" name="submit_edit_category" value="Сохранить" />
		  </div>
		</form>
  	  </div>
  	</div>
  </div>
  <div style="clear: both;"></div>
  ';
	// Include footer
	template_footer();
// конец шаблона редактирования категории
} elseif ($admincp_action == 'install_game_pack') {
	if (isset($_POST['submit_game_pack'])) {
		$sql_file_content = file_get_contents($_FILES['sql_file']['tmp_name']);
		$sql_file_content = str_replace('{date_added}', time(), $sql_file_content);
		$sql_file_content = str_replace('{status}', $_POST['file_status'], $sql_file_content);
		$sql_file_content = str_replace('{tbl_prefix}', $tbl_prefix, $sql_file_content);
		$sql_file_lines = explode('<break>', $sql_file_content);

		foreach ($sql_file_lines as $sql_file_line) {
			if (trim($sql_file_line) != '') {
				mysql_query($sql_file_line);
			}
		}
		// We should also recount files
		$recount_files_query = mysql_query("SELECT count(fileid) AS files_count FROM on_files WHERE status = '1'");
		$recount_files_row = mysql_fetch_assoc($recount_files_query);

		// Update statistics table
		$update_stats_query = mysql_query("UPDATE on_statistics SET total_files = '". $recount_files_row['files_count'] ."' WHERE stats_id = '". $stats['id'] ."'");

		// Redirect
		redirect_page($settings['siteurl'] .'/admin/content.php?a=install_game_pack', $lang['game_pack_installed']);
	}

	$page_title = $lang['install_game_pack'];

	// Load template
	template_install_game_pack();
} elseif ($admincp_action == 'broken_files') {
	if (isset($_POST['submit_broken_files'])) {
		if (isset($_POST['broken_id'])) {
			if ($_POST['broken_files_action'] == 'delete_report') {
				// Delete marked reports
				foreach ($_POST['broken_id'] as $key => $val) {
					$delete_broken_files[] = (int) $key;
				}
				if (is_array($delete_broken_files)) {
					// Delete broken reports
					$delete_broken_query = mysql_query("DELETE FROM on_report_broken WHERE report_id IN (". implode(', ', $delete_broken_files) .")");
				}

				// Redirect
				redirect_page($settings['siteurl'] .'/admin/content.php?a=broken_files', $lang['report_deleted']);
			} elseif ($_POST['broken_files_action'] == 'delete_file') {
				// Delete files hat don't work
			 	foreach ($_POST['broken_id'] as $key => $val) {
					$delete_broken_files[] = (int) $key;
				}

				if (is_array($delete_broken_files)) {
					// Get ids of files that need to be deleted
					$broken_files_query = mysql_query("SELECT file_id FROM on_report_broken WHERE report_id IN (". implode(', ', $delete_broken_files) .")");
					while ($broken_files_row = mysql_fetch_array($broken_files_query)) {
						$broken_files_ids[] = $broken_files_row['file_id'];
					}

					// Delete files
					$delete_files_query = mysql_query("DELETE FROM on_files
                                                                           WHERE fileid
                                                                           IN (". implode(', ', $broken_files_ids) .")");
					// Delete comments
					$delete_comments_query = mysql_query("DELETE FROM on_comments
                                                                              WHERE fileid
                                                                              IN (". implode(', ', $broken_files_ids) .")");
					// Delete scores
					$delete_scores_query = mysql_query("DELETE FROM on_scores WHERE file_id IN (". implode(', ', $broken_files_ids) .")");

					// Remove reports
					$delete_broken_query = mysql_query("DELETE FROM on_report_broken WHERE file_id IN (". implode(', ', $broken_files_ids) .")");

					// We should also recount files
					$recount_files_query = mysql_query("SELECT count(fileid) AS files_count FROM on_files WHERE status = '1'");
					$recount_files_row = mysql_fetch_assoc($recount_files_query);

					// Update statistics table
					$update_stats_query = mysql_query("UPDATE on_statistics
                                                        SET total_files = '". $recount_files_row['files_count'] ."'
                                                        WHERE stats_id = '". $stats['id'] ."'");
				}

				// Redirect
				redirect_page($settings['siteurl'] .'/admin/content.php?a=broken_files', $lang['file_deleted']);
			}
		}
	}

	$broken_files = array();
	// Get reports
	$broken_files_query = mysql_query("
		SELECT
			rb.*, file.title
		FROM
			on_report_broken AS rb
			LEFT JOIN on_files AS file ON (file.fileid = rb.file_id)");
	while ($broken_files_row = mysql_fetch_assoc($broken_files_query)) {
		$broken_files[] = array (
			'id'			=>	$broken_files_row['report_id'],
			'file_id'		=>	$broken_files_row['file_id'],
			'file_title'	=>	$broken_files_row['title'],
			'comment'		=>	$broken_files_row['comment'],
			'ip'			=>	$broken_files_row['ip'],
			'date'			=>	mod_date($broken_files_row['date_reported'])
		);
	}

	$page_title = $lang['broken_files'];

	// Load template
	template_broken_files();
// выводим форму добавления новых файлов 
} elseif ($admincp_action == 'add_file') { // если нажали добавить новый файл
	if (isset($_POST['submit_file'])) {
//---- загрузка через пост локально с компьюетра
        if(is_uploaded_file($_FILES["filenamepic"]["tmp_name"])) { // Проверяем загружен ли файл
        //$rash_name1 = substr($_FILES["filenamepic"]["name"], strpos($_FILES["filenamepic"]["name"], '.'), 999);        
        $rash_name1 = '.'.end(explode(".", $_FILES["filenamepic"]["name"]));
        $filepic_up = date('YmjHis').rand(0,99).$rash_name1;
        move_uploaded_file($_FILES["filenamepic"]["tmp_name"], $_SERVER['DOCUMENT_ROOT'] . "/files/image/". $filepic_up);         
        } else { 
        $filepic_up = $_POST['file_image'];               
        }

        if(is_uploaded_file($_FILES["filenamevideo"]["tmp_name"])) { // Проверяем загружен ли файл
        //$rash_name2 = substr($_FILES["filenamepic"]["name"], strpos($_FILES["filenamepic"]["name"], '.'), 999);
        $rash_name2 = '.'.end(explode(".", $_FILES["filenamevideo"]["name"]));        
        $filevideo_up = date('YmjHis').rand(0,99).$rash_name2;                
        move_uploaded_file($_FILES["filenamevideo"]["tmp_name"], $_SERVER['DOCUMENT_ROOT'] . "/files/video/".$filevideo_up);
        } else { 
        $filevideo_up = $_POST['file_video'];               
        }
                
        if(is_uploaded_file($_FILES["filenamegame"]["tmp_name"])) { // Проверяем загружен ли файл
        $rash_name3 = '.'.end(explode(".", $_FILES["filenamegame"]["name"]));
        $filegame_up = date('YmjHis').rand(0,99).$rash_name3;        
         move_uploaded_file($_FILES["filenamegame"]["tmp_name"], $_SERVER['DOCUMENT_ROOT'] . "/files/file/".$rash_name3);          // Если файл загружен успешно, перемещаем его из временной директории в конечную

        } else { 
        $filegame_up = $_POST['file_file'];
        }        	   
//---- загрузка через пост локально с компьютера конец
//---- загрузка категорий
$category = $_POST['category'];
if(empty($category)){
    echo('<p class="bg-danger">Не выбрана ни одна из категорий.</p>');
    die();
} else {
// находим максимальную запись и добавляем +1 и вставки в катенгории
$file_check_query = mysql_query("SELECT MAX(fileid) FROM on_files");
$fileid = mysql_fetch_array ($file_check_query);
//print_r ($fileid);
//echo $fileid[0];
$newfileid = (int)$fileid[0] + 1; // находим будущий $fileid
mysql_query("DELETE FROM `on_files_categories` WHERE `fileid`='".$newfileid."'"); //для удаления ранее всех на всякий случай
                                                 
    foreach($category as $key => $value){
        //if(isset($chb)) $sel=1;
    $add_file_query = mysql_query("INSERT INTO on_files_categories SET 
                                   fileid = '". $newfileid ."',
                                   category = '". $value ."'");        

//echo "INSERT INTO on_files_categories SET fileid = '". $newfileid ."',category = '". $value ."'";
    }
}
//---- загрузка категорий конец

//---- загрузка тегов
$tag = $_POST['tag'];
print_r($tag);
if(!empty($tag)){
// находим максимальную запись и добавляем +1 и вставки в катенгории
$file_check_query = mysql_query("SELECT MAX(fileid) FROM on_files");
$fileid = mysql_fetch_array ($file_check_query);
//print_r ($fileid);
//echo $fileid[0];
$newfileid = (int)$fileid[0] + 1; // находим будущий $fileid    
mysql_query("DELETE FROM `on_files_search_tags` WHERE `fileid`='".$newfileid."'"); //для удаления ранее всех на всякий случай                                         
    foreach($tag as $key => $value){
    $add_file_query = mysql_query("INSERT INTO on_files_search_tags SET 
                                   fileid = '". $newfileid ."',
                                   tag = '". $value ."'");        
    }
}
//---- загрузка тегов конец+


	   
		if (empty($_POST['title'])) {
			$add_file['error'] = $lang['title_not_blank'];
		} else {
			// Check if file is already in the database
    		$file_check_query = mysql_query("SELECT count(fileid)
                                             FROM on_files
                                             WHERE title = '". $_POST['title'] ."'");
    		$file_check_row = mysql_fetch_assoc($file_check_query);
    		if ($file_check_row['count(fileid)'] > '0') {
				$add_file['error'] = $lang['file_already_database'];
    		} else {
    			$file_width = $_POST['width'];
    			$file_height = $_POST['height'];
    			if (empty($file_width) || empty($file_height)) {
    				if ($_POST['file_location'] == '2') {
		    			$file_size = @getimagesize($_POST['file_file']);
		    			$file_width = $file_size[0];
						$file_height = $file_size[1];
					} elseif($_POST['file_location'] == '1') {
		    			$file_size = @getimagesize('../files/'. $settings['filesdir'] .'/'. $_POST['file_file']);
		    			$file_width = $file_size[0];
						$file_height = $file_size[1];
					}
    			}
    			if ($_POST['status'] == '1') {
    				$update_files_query = mysql_query("UPDATE on_statistics
                    SET total_files = total_files + 1
                    WHERE stats_id = '". $stats['id'] ."'");
    			}
    			// Добавить файл в базу
    			$add_file_query = mysql_query("INSERT INTO on_files
                SET title = '". $_POST['title'] ."',
                title_m = '". $_POST['title_m'] ."',
                title_en = '". $_POST['title_en'] ."',                
                description = '". $_POST['description'] ."',
                description_m = '". $_POST['description_m'] ."',
                keywords = '". $_POST['keywords'] ."',
                category = '". $_POST['category'] ."',
                width = '". $file_width ."',
                height = '". $file_height ."',
                filetype = '". $_POST['file_type'] ."',
                file = '". $filegame_up ."',
                video = '". $filevideo_up ."',                
                filelocation = '". $_POST['file_location'] ."',
                customcode ='". $_POST['custom_code'] ."',
                icon = '". $filepic_up ."',
                iconlocation = '". $_POST['image_location'] ."',
                status = '". $_POST['status'] ."',
                dateadded = '". time() ."',
                added_by = '". $user['id'] ."',
                adult = '". $_POST['adult'] ."',
                scores = '". $_POST['scores'] ."',
                score_type = '". $_POST['score_type'] ."'");

				// Redirect
                //exit();
				redirect_page($settings['siteurl'] .'/admin/content.php', $lang['file_added']);
    		}
		}
	}
/*    
	// Get categories selection
	$categories_query = mysql_query("SELECT catid, name , parentcategory
                                     FROM on_categories
                                     WHERE parentcategory = 0
                                     ORDER BY catorder, name");
	$add_file['categories'] = '<select name="category">';
	while ($categories_row = mysql_fetch_assoc($categories_query)) {
//    	if ($categories_row['parentcategory'] == '0'){   
           	$add_file['categories'].= '<option class="classbold" value="'.$categories_row['catid'].'">'.$categories_row['name'].'</option>';
	        $categories_query2 = mysql_query("SELECT catid, name , parentcategory
                                     FROM on_categories
                                     WHERE parentcategory = ".$categories_row['catid']."
                                     ORDER BY catorder, name");                                   
            while ($c_row2 = mysql_fetch_assoc($categories_query2)) {
//                    if ($c_row2['parentcategory'] == $categories_row['catid'])              
                  	$add_file['categories'].= '<option value="'.$c_row2['catid'].'">&nbsp;&nbsp;'.$c_row2['name'].'</option>';
            }    
//        }        
	}
	$add_file['categories'] .= '</select>';
*/

	// Get categories selection
	$categories_query = mysql_query("SELECT catid, name , parentcategory
                                     FROM on_categories
                                     WHERE parentcategory = 0
                                     ORDER BY catorder, name");
	$add_file['categories'] = '<div class="category_more">';
    $i=0;   
	while ($categories_row = mysql_fetch_assoc($categories_query)) {
//    	if ($categories_row['parentcategory'] == '0'){
            $add_file['categories'] .= '<div class="onerow">';    
           	$add_file['categories'] .= '<label class="classbold"><input type="checkbox" name="category[]" value="'.$categories_row['catid'].'">&nbsp;&nbsp;'.$categories_row['name'].'</label><br />';
	        $categories_query2 = mysql_query("SELECT catid, name , parentcategory
                                              FROM on_categories
                                              WHERE parentcategory = ".$categories_row['catid']."
                                              ORDER BY catorder, name");
            //if (count(mysql_fetch_assoc($categories_query2)) != 1 ) $add_file['categories'] .= '<hr>';
            //$add_file['categories'] .= count(mysql_fetch_assoc($categories_query2));
            //print_r (mysql_fetch_assoc($categories_query2));
            $ii = 0;                                                                                  
            while ($c_row2 = mysql_fetch_assoc($categories_query2)) {
                      $ii++; if ($ii == 1) $add_file['categories'] .= '<hr>';
//                    if ($c_row2['parentcategory'] == $categories_row['catid'])              
                  	$add_file['categories'].= '<label><input type="checkbox" name="category[]" value="'.$c_row2['catid'].'">&nbsp;&nbsp;'.$c_row2['name'].'</label><br />';
            }    
//        }
    $i++;        
    $add_file['categories'] .= '</div>';
    if ($i % 5 == 0) $add_file['categories'] .= '<div class="clearfix"></div>';
	}
	$add_file['categories'] .= '<div class="clearfix"></div></div>';



	// Get tags selection
	$tag_query = mysql_query("SELECT * FROM on_search_tags ORDER BY tags");
	$add_file['tags'] = '<div class="tag_more">';
    $i=0;   
            $add_file['tags'] .= '<div class="onerow">';
	while ($tag_row = mysql_fetch_assoc($tag_query)) {
           	$add_file['tags'] .= '<label><input type="checkbox" name="tag[]" 
            value="'.$tag_row['id'].'">&nbsp;&nbsp;'.$tag_row['tags'].'</label><br />';                                                                                
    $i++;        
//    $add_file['tags'] .= '</div>';
    if ($i % 10 == 0) $add_file['tags'] .= '</div><div class="onerow">';
	}
	$add_file['tags'] .= '</div><div class="clearfix"></div></div>';


	// Get file type selection
	$add_file['file_types'] = '
<select name="file_type" onchange="javascript:change_file_type()">';
	if ($file_type_directory = opendir('../includes/file_type/')) {
		while (false != ($file_type = readdir($file_type_directory))) {
			if ($file_type != '.' && $file_type != '..' && $file_type != 'index.html') {
				$file_type = str_replace('.php', '', $file_type);
				$add_file['file_types'] .= '
<option value="'. $file_type .'">'. $file_type .'</option>';
			}
		}
		closedir($file_type_directory);
	}
$add_file['file_types'] .= '
  <option value="code">'. $lang['custom_code'] .'</option>
</select>';

	$add_file['file'] = nohtml(@$_GET['f']);
	$add_file['image'] = nohtml(@$_GET['i']);

	$page_title = $lang['add_file'];

	// Load template
//	template_add_file();
// Форма добавления файлов

	global $settings, $lang, $add_file;

	// Include header
	template_header();

	echo '
  <div class="admin_left">';
	template_content_menu();
	echo '
  </div>
  <div class="admin_right">';
	if (@strlen($add_file['error'])) {
		echo '<div class="error_box">'. $add_file['error'] .'</div>';
	}
	echo '
    <div class="content_box_2">
      <div class="content_box_2_header">
        '. $lang['add_file'] .'
      </div>
      <div class="box_text">
        <form action="" method="POST" name="form" enctype="multipart/form-data" onsubmit="return verify_add()">
		  <div class="content_text_left" style="width: 20%;">Title</div>
		  <div class="content_text_right" style="width: 80%;"><input class="form-control" type="text" name="title" size="115" /></div>
		  <div style="clear: both;"></div>

		  <div class="content_text_left" style="width: 20%;">Title на английском</div>
		  <div class="content_text_right" style="width: 80%;"><input class="form-control" type="text" name="title_en" size="115" /></div>
		  <div style="clear: both;"></div>

		  <div class="content_text_left" style="width: 20%;">Title для SEO</div>
		  <div class="content_text_right" style="width: 80%;"><input class="form-control" type="text" name="title_m" size="115" /></div>
		  <div style="clear: both;"></div>

		  <div class="content_text_left" style="width: 20%;">Описание:</div>
		  <div class="content_text_right" style="width: 80%;"><textarea class="form-control" name="description" rows="10" cols="115"></textarea></div>
		  <div style="clear: both;"></div>

		  <div class="content_text_left" style="width: 20%;">Описание для SEO</div>
		  <div class="content_text_right" style="width: 80%;"><textarea class="form-control" name="description_m" rows="10" cols="115"></textarea></div>
		  <div style="clear: both;"></div>

		  <div class="content_text_left" style="width: 20%;">Ключевые слова:</div>
		  <div class="content_text_right" style="width: 80%;"><textarea class="form-control" name="keywords" rows="3" cols="115"></textarea></div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">Категория:</div>
		  <div class="content_text_right" style="width: 80%;">
		  '. $add_file['categories'] .'
		  </div>
		  <div style="clear: both;"></div>
          
		  <div class="content_text_left" style="width: 20%;">Поисковые теги:</div>
		  <div class="content_text_right" style="width: 80%;">
		  '. $add_file['tags'] .'
		  </div>
		  <div style="clear: both;"></div>          
          
		  <div class="content_text_left" style="width: 20%;">Размер (ширина x высота):</div>
		  <div class="content_text_right" style="width: 80%;"><input type="text" name="width" size="3" /> x <input type="text" name="height" size="3" /></div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">Расширение файла</div>
		  <div class="content_text_right" style="width: 80%;">
		    '. $add_file['file_types'] .'
		  </div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">Файл:</div>
		  <div id="enter_file" class="content_text_right" style="width: 80%;">
		    <input type="text" name="file_file" value="'. $add_file['file'] .'" size="35" />
			<select name="file_location">
			  <option value="1">Локально</option>
			  <option value="2">Ссылка на игру</option>
			  <option value="3">Frame</option>
			</select>
		  </div>

          <div style="clear: both;"></div>
          <div class="content_text_left" style="width: 20%;">Выбрать файл игры:</div>
          <div class="content_text_right" style="width: 80%;">
          <input type="file" name="filenamegame"><br>
          </div>          
          
		  <div id="enter_custom_code" class="content_text_right" style="width: 80%; display: none;">
		    <textarea name="custom_code" rows="3" cols="35"></textarea>
		  </div>                   
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">Картинка игры</div>
		  <div class="content_text_right" style="width: 80%;">
		    <input type="text" name="file_image" value="'. $add_file['image'] .'" size="35" />
			<select name="image_location">
			  <option value="1">Локально</option>
			  <option value="2">Ссылка</option>
			</select>
		  </div>

          <div style="clear: both;"></div>
          <div class="content_text_left" style="width: 20%;">Выбрать файл картинки:</div>
          <div class="content_text_right" style="width: 80%;">
          <input type="file" name="filenamepic"><br>
          </div>

                  
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">Файл видео:</div>
		  <div class="content_text_right" style="width: 80%;">
		    <input type="text" name="file_video" size="35" />
		  </div>
          
          <div style="clear: both;"></div>
          <div class="content_text_left" style="width: 20%;">Выбрать файл видео:</div>
          <div class="content_text_right" style="width: 80%;">
          <input type="file" name="filenamevideo"><br>
          </div>            
          
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">Статус:</div>
		  <div class="content_text_right" style="width: 80%;">
		    <select name="status">
			  <option value="1">Активна</option>
			  <option value="2">Не активна</option>
			  <option value="3">'. $lang['game_slave'] .'</option>
			</select>
		  </div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">Игра для взрослых:</div>
		  <div class="content_text_right" style="width: 80%;">
		    <select name="adult">
			  <option value="0">Нет</option>
			  <option value="1">Да</option>
			</select>
		  </div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">Подсчет отчков:</div>
		  <div class="content_text_right" style="width: 80%;">
		    <select name="scores" onchange="javascript:highscore_type_show()">
			  <option value="0">'. $lang['off'] .'</option>
			  <option value="1">'. $lang['on'] .'</option>
			</select>
		  </div>
		  <div style="clear: both;"></div>
		  <span id="highscore_type" style="display: none;">
		  <div class="content_text_left" style="width: 20%;">'. $lang['highscore_type'] .'</div>
		  <div class="content_text_right" style="width: 80%;">
		    <select name="score_type">
			  <option value="1">'. $lang['score_high'] .'</option>
			  <option value="2">'. $lang['score_low'] .'</option>
			</select>
		  </div>
		  <div style="clear: both;"></div>
		  </span>
		  <div align="center">
		    <input class = "myButton" type="submit" name="submit_file" value="'. $lang['submit'] .'" />
		  </div>
		</form>
  	  </div>
  	</div>
  </div>
  <div style="clear: both;"></div>
  ';
	template_footer();
// Форма добавления файлов конец
// Редактирование файла
} elseif ($admincp_action == 'edit_file') {
	$file_id = (int) $_GET['f'];
    
	if (isset($_POST['submit_file'])) {
//---- загрузка через пост локально с компьюетра
        if(is_uploaded_file($_FILES["filenamepic"]["tmp_name"])) { // Проверяем загружен ли файл
//      move_uploaded_file($_FILES["filenamepic"]["tmp_name"], $_SERVER['DOCUMENT_ROOT'] . "/files/image/".$_FILES["filenamepic"]["name"]);          // Если файл загружен успешно, перемещаем его из временной директории в конечную
        $rash_name1 = '.'.end(explode(".", $_FILES["filenamepic"]["name"]));
        $filepic_up = date('YmjHis').rand(0,99).$rash_name1;                
        move_uploaded_file($_FILES["filenamepic"]["tmp_name"], $_SERVER['DOCUMENT_ROOT'] . "/files/image/".$filepic_up);          
        } else { 
        $filepic_up = $_POST['file_image'];               
        }
        if(is_uploaded_file($_FILES["filenamevideo"]["tmp_name"])) { // Проверяем загружен ли файл
        $rash_name2 = '.'.end(explode(".", $_FILES["filenamevideo"]["name"]));
        $filevideo_up = date('YmjHis').rand(0,99).$rash_name2;                
        move_uploaded_file($_FILES["filenamevideo"]["tmp_name"], $_SERVER['DOCUMENT_ROOT'] . "/files/video/".$filevideo_up);      
        } else { 
        $filevideo_up = $_POST['file_video'];               
        }                
        if(is_uploaded_file($_FILES["filenamegame"]["tmp_name"])) { // Проверяем загружен ли файл
        $rash_name2 = '.'.end(explode(".", $_FILES["filenamegame"]["name"]));
        $filegame_up = date('YmjHis').rand(0,99).$rash_name2;                
        move_uploaded_file($_FILES["filenamegame"]["tmp_name"], $_SERVER['DOCUMENT_ROOT'] . "/files/file/".$filegame_up);          // Если файл загружен успешно, перемещаем его из временной директории в конечную
        } else { 
        $filegame_up = $_POST['file_file'];
        }        	   
//---- загрузка через пост локально с компьютера

//---- загрузка тегов
$tag = $_POST['tag'];
if(!empty($tag)){
mysql_query("DELETE FROM `on_files_search_tags` WHERE `fileid`='".$file_id."'"); //для удаления ранее всех на всякий случай                                         
    foreach($tag as $key => $value){
    $add_file_query = mysql_query("INSERT INTO on_files_search_tags SET 
                                   fileid = '". $file_id ."',
                                   tag = '". $value ."'");        
    }
}
//---- загрузка тегов конец+

//---- загрузка категорий
$category = $_POST['category'];
if(empty($category)){
    echo('<p class="bg-danger">Не выбрана ни одна из категорий.</p>');
    die();
} else {
// находим максимальную запись и добавляем +1 и вставки в катенгории
//$file_check_query = mysql_query("SELECT MAX(fileid) FROM on_files");
//$fileid = mysql_fetch_array ($file_check_query);
//print_r ($fileid);
//echo $fileid[0];
//$newfileid = (int)$fileid[0] + 1; // находим будущий $fileid
mysql_query("DELETE FROM `on_files_categories` WHERE `fileid`='".$file_id."'"); //для удаления ранее всех на всякий случай
                                                 
    foreach($category as $key => $value){
        //if(isset($chb)) $sel=1;
    $add_file_query = mysql_query("INSERT INTO on_files_categories SET 
                                   fileid = '". $file_id ."',
                                   category = '". $value ."'");        

//echo "INSERT INTO on_files_categories SET fileid = '". $newfileid ."',category = '". $value ."'";
    }
}
//---- загрузка категорий конец+



        if (isset($_POST['newdata'])) $newdata1 = " dateadded = '". time() ."',";
        else $newdata1 = "";
           
		$edit_file_query = mysql_query("UPDATE on_files
                    SET title = '". $_POST['title'] ."',
                    title_m = '". $_POST['title_m'] ."',
                    title_en = '". $_POST['title_en'] ."',                    
                    ". $newdata1 ."
                    description = '". $_POST['description'] ."',
                    description_m = '". $_POST['description_m'] ."',
                    keywords = '". $_POST['keywords'] ."',
                    category = '". $_POST['category'] ."',
                    width = '". $_POST['width'] ."',
                    height = '". $_POST['height'] ."',
                    filetype = '". $_POST['file_type'] ."',
                    file = '". $filegame_up ."',
                    video = '". $filevideo_up ."',                    
                    filelocation = '". $_POST['file_location'] ."',
                    customcode ='". $_POST['custom_code'] ."',
                    icon = '". $filepic_up ."',
                    iconlocation = '". $_POST['image_location'] ."',
                    status = '". $_POST['status'] ."',
                    adult = '". $_POST['adult'] ."',
                    scores = '". $_POST['scores'] ."',
                    score_type = '". $_POST['score_type'] ."'
                    WHERE fileid = '". $file_id ."'");

    	// Get the number of files
		$recount_files_query = mysql_query("SELECT count(fileid) AS files_count
                                            FROM on_files
                                            WHERE status = '1'");
		$recount_files_row = mysql_fetch_array($recount_files_query);

		// Update statistics table
		$update_stats_query = mysql_query("UPDATE on_statistics
                                           SET total_files = '". $recount_files_row['files_count'] ."'
                                           WHERE stats_id = '". $stats['id'] ."'");

		// Update sponsor (when needed)
		if (strlen($_POST['sponsor_text']) && strlen($_POST['sponsor_url'])) {
			// Insert or update sponsor
			$sponsor_query = mysql_query("INSERT INTO on_sponsors
                                          SET file_id = '". $file_id ."',
                                          sponsor_title = '". nohtml($_POST['sponsor_text']) ."',
                                          sponsor_url = '". nohtml($_POST['sponsor_url']) ."', date_added = UNIX_TIMESTAMP()
                                          ON DUPLICATE KEY UPDATE sponsor_title = '". nohtml($_POST['sponsor_text']) ."',
                                          sponsor_url = '". nohtml($_POST['sponsor_url']) ."'");
		}

		// Redirect
		redirect_page($settings['siteurl'] .'/admin/content.php?a=edit_file&f='. $file_id, $lang['file_updated']);
	}

	$file_query = mysql_query("
		SELECT
			f.*, IFNULL(s.file_id, 0) AS sponsor, s.sponsor_title, s.sponsor_url
		FROM
			on_files AS f
		LEFT JOIN on_sponsors AS s ON (s.file_id = f.fileid)
		WHERE fileid = '". $file_id ."' LIMIT 1");
	$file_row = mysql_fetch_assoc($file_query);

	$edit_file = array (
		'id'				=>	$file_id,
		'title'				=>	$file_row['title'],
		'description'		=>	$file_row['description'],
		'title_m'			=>	$file_row['title_m'],
		'title_en'			=>	$file_row['title_en'],        
		'description_m'		=>	$file_row['description_m'],
		'keywords'			=>	$file_row['keywords'],
		'width'				=>	$file_row['width'],
		'height'			=>	$file_row['height'],
		'file'				=>	$file_row['file'],
		'video'				=>	$file_row['video'],                
		'custom_code'		=>	$file_row['customcode'],
		'image'				=>	$file_row['icon'],
		'file_type'			=>	$file_row['filetype'],
		'file_location'		=>	$file_row['filelocation'],
		'image_location'	=>	$file_row['iconlocation'],
		'status'			=>	$file_row['status'],
		'adult'				=>	$file_row['adult'],
		'scores'			=>	$file_row['scores'],
		'score_type'		=>	$file_row['score_type'],
		'sponsor'			=>	$file_row['sponsor'],
		'sponsor_text'		=>	$file_row['sponsor_title'],
		'sponsor_url'		=>	$file_row['sponsor_url']
	);

/*
	// Get categories selection
	$categories_query = mysql_query("SELECT catid, name 
                                     FROM on_categories
                                     WHERE parentcategory = 0 
                                     ORDER BY catorder, name");
	$edit_file['categories'] = '<select name="category">';
	while ($categories_row = mysql_fetch_assoc($categories_query)) {
		if ($categories_row['catid'] == $file_row['category']) {
			$edit_file['categories'] .= '
  <option class="classbold" value="'. $categories_row['catid'] .'" selected>'. $categories_row['name'] .'</option>';
		} else {
    		$edit_file['categories'] .= '
  <option  class="classbold" value="'. $categories_row['catid'] .'">'. $categories_row['name'] .'</option>';
	$categories_query2 = mysql_query("SELECT catid, name 
                                     FROM on_categories
                                     WHERE parentcategory = ". $categories_row['catid'] ." 
                                     ORDER BY catorder, name");
	while ($categories_row2 = mysql_fetch_assoc($categories_query2)) {
		if ($categories_row2['catid'] == $file_row['category']) {
			$edit_file['categories'] .= '
  <option  value="'. $categories_row2['catid'] .'" selected>&nbsp;&nbsp;'. $categories_row2['name'] .'</option>';
		} else {
    		$edit_file['categories'] .= '
  <option value="'. $categories_row2['catid'] .'">&nbsp;&nbsp;'. $categories_row2['name'] .'</option>';
		}
	}
  
		}
	}
	$edit_file['categories'] .= '</select>';

*/


	// Get categories selection
	$categories_query = mysql_query("SELECT cat.catid, cat.name , cat.parentcategory, catf.id as catfid 
                                     FROM on_categories AS cat
                                     LEFT JOIN on_files_categories  AS catf ON catf.category = cat.catid 
                                     AND catf.fileid = '".$edit_file['id']."'
                                     WHERE parentcategory = 0
                                     ORDER BY cat.catorder, cat.name");
	$edit_file['categories'] = '<div class="category_more">';
    $i=0;   
	while ($categories_row = mysql_fetch_assoc($categories_query)) {
//    $query = mysql_query("SELECT id 
//                          FROM on_files_categories 
//                          WHERE category = '".$categories_row['catid']."'
//                          AND fileid = '".$edit_file['id']."'");	   
//    	if ($categories_row['parentcategory'] == '0'){
            $edit_file['categories'] .= '<div class="onerow">';    
           	$edit_file['categories'] .= '<label class="classbold">
               <input type="checkbox" name="category[]" value="'.$categories_row['catid'].'"';
            if (!empty($categories_row['catfid'])) $edit_file['categories'].= ' checked="checked" ';               
            $edit_file['categories'] .= '>&nbsp;&nbsp;'.$categories_row['name'].'</label><br />';
	        $categories_query2 = mysql_query("SELECT cat.catid, cat.name , cat.parentcategory , catf.id as catfid
                                              FROM on_categories AS cat
                                              LEFT JOIN on_files_categories  AS catf ON catf.category = cat.catid 
                                              AND catf.fileid = '".$edit_file['id']."'                                              
                                              WHERE parentcategory = '".$categories_row['catid']."'
                                              ORDER BY catorder, name");           
            $ii = 0;                                                                                  
            while ($c_row2 = mysql_fetch_assoc($categories_query2)) {
                      $ii++; if ($ii == 1) $edit_file['categories'] .= '<hr>';
//                    if ($c_row2['parentcategory'] == $categories_row['catid'])              
                  	$edit_file['categories'].= '<label><input type="checkbox" name="category[]" value="'.$c_row2['catid'].'"';
            if (!empty($c_row2['catfid'])) $edit_file['categories'].= ' checked="checked" ';
                  	$edit_file['categories'].='>&nbsp;&nbsp;'.$c_row2['name'].'</label><br />';                                   
            }    
//        }
    $i++;        
    $edit_file['categories'] .= '</div>';
    if ($i % 5 == 0) $edit_file['categories'] .= '<div class="clearfix"></div>';
	}
	$edit_file['categories'] .= '<div class="clearfix"></div></div>';



	// Get tags selection
    $tag_query = mysql_query("SELECT stag.id as sid, stag.tags , tagf.id as catfid
                                      FROM on_search_tags AS stag
                                      LEFT JOIN on_files_search_tags  AS tagf ON tagf.tag = stag.id 
                                      AND tagf.fileid = '".$edit_file['id']."'                                              
                                      ORDER BY stag.tags"); 
        
	$edit_file['tags'] = '<div class="tag_more">';
    $i=0;   
            $edit_file['tags'] .= '<div class="onerow">';
	while ($tag_row = mysql_fetch_assoc($tag_query)) {
//           	$edit_file['tags'].= '<label><input type="checkbox" name="tags[]" value="'.$tag_row['sid'].'">&nbsp;&nbsp;'.$tag_row['tags'].'</label><br />';               
           	$edit_file['tags'].= '<label><input type="checkbox" name="tag[]" value="'.$tag_row['sid'].'"';
            if (!empty($tag_row['catfid'])) $edit_file['tags'].= ' checked="checked" ';
           	$edit_file['tags'].='>&nbsp;&nbsp;'.$tag_row['tags'].'</label><br />';                                                                                                                                                            
    $i++;        
//    $add_file['tags'] .= '</div>';
    if ($i % 10 == 0) $edit_file['tags'] .= '</div><div class="onerow">';
	}
	$edit_file['tags'] .= '</div><div class="clearfix"></div></div>';





	// Get file type selection
	$edit_file['file_types'] = '
<select name="file_type" onchange="javascript:change_file_type()">';
	if ($file_type_directory = opendir('../includes/file_type/')) {
		while (false != ($file_type = readdir($file_type_directory))) {
			if ($file_type != '.' && $file_type != '..' && $file_type != 'index.html') {
				$file_type = str_replace('.php', '', $file_type);
				if ($file_type == $file_row['filetype']) {
					$edit_file['file_types'] .= '
<option value="'. $file_type .'" selected>'. $file_type .'</option>';
				} else {
					$edit_file['file_types'] .= '
<option value="'. $file_type .'">'. $file_type .'</option>';
				}
			}
		}
		closedir($file_type_directory);
	}
	if ($file_row['filetype'] == 'code') {
		$edit_file['file_types'] .= '
  <option value="code" selected>'. $lang['custom_code'] .'</option>
</select>';
	} else {
		$edit_file['file_types'] .= '
  <option value="code">'. $lang['custom_code'] .'</option>
</select>';
	}

	$page_title = $lang['edit_file'];

	// Load template
	//template_edit_file();
// Форма редактирования файла

	global $settings, $lang, $edit_file;

	// Include header
	template_header();

	echo '
  <div class="admin_left">';
	template_content_menu();
	echo '
  </div>
  <div class="admin_right">
    <div class="content_box_2">
      <div class="content_box_2_header">
        Редактирования файла
      </div>
      <div class="box_text">
        <form action="" method="POST" name="form" enctype="multipart/form-data" onsubmit="return verify_add_file()">
		  <div class="content_text_left" style="width: 20%;">Название:</div>
		  <div class="content_text_right" style="width: 80%;"><input class="form-control" type="text" name="title" size="115" value="'. $edit_file['title'] .'" /></div>
		  <div style="clear: both;"></div>

		  <div class="content_text_left" style="width: 20%;">Title на английском</div>
		  <div class="content_text_right" style="width: 80%;"><input class="form-control" type="text" name="title_en" size="115" value="'. $edit_file['title_en'] .'" /></div>
		  <div style="clear: both;"></div>

		  <div class="content_text_left" style="width: 20%;">Title для SEO</div>
		  <div class="content_text_right" style="width: 80%;"><input class="form-control" type="text" name="title_m"  size="115" value="'. $edit_file['title_m'] .'" /></div>
		  <div style="clear: both;"></div>

		  <div class="content_text_left" style="width: 20%;">Описание</div>
		  <div class="content_text_right" style="width: 80%;"><textarea class="form-control" name="description" rows="10" cols="115">'. $edit_file['description'] .'</textarea></div>
		  <div style="clear: both;"></div>

		  <div class="content_text_left" style="width: 20%;">Описание для SEO</div>
		  <div class="content_text_right" style="width: 80%;"><textarea name="description_m" class="form-control" rows="10" cols="115">'. $edit_file['description_m'] .'</textarea></div>
		  <div style="clear: both;"></div>

		  <div class="content_text_left" style="width: 20%;">Ключевые слова</div>
		  <div class="content_text_right" style="width: 80%;"><textarea name="keywords" class="form-control" rows="3" cols="115">'. $edit_file['keywords'] .'</textarea></div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">Категория</div>
		  <div class="content_text_right" style="width: 80%;">
		    '. $edit_file['categories'] .'
		  </div>
		  <div style="clear: both;"></div>

		  <div class="content_text_left" style="width: 20%;">Поисковые теги:</div>
		  <div class="content_text_right" style="width: 80%;">
		  '. $edit_file['tags'] .'
		  </div>
		  <div style="clear: both;"></div>           
          
		  <div class="content_text_left" style="width: 20%;">'. $lang['size_width_height'] .'</div>
		  <div class="content_text_right" style="width: 80%;">
		  <input type="text" name="width" size="3" value="'. $edit_file['width'] .'" /> x <input type="text" name="height" size="3" value="'. $edit_file['height'] .'" /></div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">Тип файла:</div>
		  <div class="content_text_right" style="width: 80%;">
		    '. $edit_file['file_types'] .'
		  </div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">Файл:</div>
		  <div id="enter_file" class="content_text_right" style="'. ($edit_file['file_type'] == 'code' ? 'width: 80%; display: none;' : 'width: 80%; display: block;') .'">
		    <input type="text" name="file_file" value="'. $edit_file['file'] .'" size="35" />
			<select name="file_location">
			  <option value="1" '. ($edit_file['file_location'] == '1' ? 'selected' : '') .'>Локально</option>
			  <option value="2" '. ($edit_file['file_location'] == '2' ? 'selected' : '') .'>Ссылка на игру</option>
			  <option value="3" '. ($edit_file['file_location'] == '3' ? 'selected' : '') .'>Frame</option>
			</select>
		  </div>
          
          <div style="clear: both;"></div>
          <div class="content_text_left" style="width: 20%;">Выбрать файл игры:</div>
          <div class="content_text_right" style="width: 80%;">
          <input type="file" name="filenamegame"><br>
          </div>
           
		  <div id="enter_custom_code" class="content_text_right" style="'. ($edit_file['file_type'] == 'code' ? 'width: 80%; display: block;' : 'width: 80%; display: none;') .'">
		    <textarea name="custom_code" rows="3" cols="35">'. $edit_file['custom_code'] .'</textarea>
		  </div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">Картинка к игре:</div>
		  <div class="content_text_right" style="width: 80%;">
		    <input type="text" name="file_image" value="'. $edit_file['image'] .'" size="35" />
			<select name="image_location">
			  <option value="1" '. ($edit_file['image_location'] == '1' ? 'selected' : '') .'>'. $lang['local'] .'</option>
			  <option value="2" '. ($edit_file['image_location'] == '2' ? 'selected' : '') .'>'. $lang['linked'] .'</option>
			</select>
		  </div>
          
          <div style="clear: both;"></div>
          <div class="content_text_left" style="width: 20%;">Выбрать файл картинки:</div>
          <div class="content_text_right" style="width: 80%;">
          <input type="file" name="filenamepic"><br>
          </div>          


		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">Файл видео:</div>
		  <div class="content_text_right" style="width: 80%;">
		    <input type="text" name="file_video" value="'. $edit_file['video'] .'" size="35" />
		  </div>
          <div style="clear: both;"></div>
          <div class="content_text_left" style="width: 20%;">Выбрать файл видео:</div>
          <div class="content_text_right" style="width: 80%;">
          <input type="file" name="filenamevideo"><br>
          </div>          
          
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">Статус:</div>
		  <div class="content_text_right" style="width: 80%;">
		    <select name="status">
			  <option value="1" '. ($edit_file['status'] == '1' ? 'selected' : '') .'>'. $lang['active'] .'</option>
			  <option value="2" '. ($edit_file['status'] == '2' ? 'selected' : '') .'>'. $lang['inactive'] .'</option>
			  <option value="3" '. ($edit_file['status'] == '3' ? 'selected' : '') .'>'. $lang['game_slave'] .'</option>
			</select>
		  </div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">Игра для взрослых:</div>
		  <div class="content_text_right" style="width: 80%;">
		    <select name="adult">
			  <option value="0" '. ($edit_file['adult'] == '0' ? 'selected' : '') .'>'. $lang['no'] .'</option>
			  <option value="1" '. ($edit_file['adult'] == '1' ? 'selected' : '') .'>'. $lang['yes'] .'</option>
			</select>
		  </div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">Подсчет отчков:</div>
		  <div class="content_text_right" style="width: 80%;">
		    <select name="scores" onchange="javascript:highscore_type_show()">
			  <option value="0" '. ($edit_file['scores'] == '0' ? 'selected' : '') .'>'. $lang['off'] .'</option>
			  <option value="1" '. ($edit_file['scores'] == '1' ? 'selected' : '') .'>'. $lang['on'] .'</option>
			</select>
		  </div>
		  <div style="clear: both;"></div>
		  <span id="highscore_type" style="'. ($edit_file['scores'] == '1' ? 'display: block;' : 'display: none;') .'">
		  <div class="content_text_left" style="width: 20%;">'. $lang['highscore_type'] .'</div>
		  <div class="content_text_right" style="width: 80%;">
		    <select name="score_type">
			  <option value="1" '. ($edit_file['score_type'] == '1' ? 'selected' : '') .'>'. $lang['score_high'] .'</option>
			  <option value="2" '. ($edit_file['score_type'] == '2' ? 'selected' : '') .'>'. $lang['score_low'] .'</option>
			</select>
		  </div>
		  <div style="clear: both;"></div>
		  </span>
		  <br />
		  <div class="content_text_left" style="width: 20%;">'. $lang['sponsor_text'] .'</div>
		  <div class="content_text_right" style="width: 80%;">
		    <input type="text" name="sponsor_text" size="85" value="'. $edit_file['sponsor_text'] .'" />';
	if ($edit_file['sponsor'] != 0)
		echo '
		    <a href="content.php?a=delete_sponsor&f='. $edit_file['id'] .'">'. $lang['delete_sponsor'] .'</a>';
	echo '
		  </div>
		  <div style="clear: both;"></div>
		  <div class="content_text_left" style="width: 20%;">'. $lang['sponsor_url'] .'</div>
		  <div class="content_text_right" style="width: 80%;"><input type="text" name="sponsor_url" size="85" value="'. $edit_file['sponsor_url'] .'" /></div>
		  <div style="clear: both;"></div>
          <div class="content_text_left" style="width: 20%;">Изменить дату на текущую : </div>
          <div class="content_text_right" style="width: 80%;"><input name="newdata" type="checkbox" value="1" /></div>
		  <div style="clear: both;"></div>
		  <div align="center">             
		    <input class = "myButton" type="submit" name="submit_file" value="'. $lang['submit'] .'" />
		  </div>
		</form>
  	  </div>
  	</div>
  </div>
  <div style="clear: both;"></div>';
	// Include footer
	template_footer();

// Форма редактирования файла конец
} elseif ($admincp_action == 'delete_sponsor') {
	if (is_numeric($_GET['f'])) {
		// Delete sponsor
		$delete_sponsor_query = mysql_query("DELETE FROM on_sponsors
        WHERE file_id = '". $_GET['f'] ."' LIMIT 1");

		// Redirect
		redirect_page($settings['siteurl'] .'/admin/content.php?a=edit_file&f='. $_GET['f'], $lang['sponsor_deleted']);
	}
} elseif ($admincp_action == 'file_comments') {
	$file_id = (int) $_GET['f'];
	if (isset($_POST['submit_comments'])) {
		if (isset($_POST['comment_id'])) {
			if ($_POST['comments_action'] == 'delete_comment') {
				// Delete comments
				foreach ($_POST['comment_id'] as $key => $val) {
					$delete_comments[] = (int) $key;
				}
				if (is_array($delete_comments)) {
					// Delete comments
					$delete_comments_query = mysql_query("DELETE FROM on_comments
                                    WHERE commentid IN (". implode(', ', $delete_comments) .")");
				}

				// Redirect
				redirect_page($settings['siteurl'] .'/admin/content.php?a=file_comments&f='. $file_id, $lang['comment_deleted']);
			} elseif ($_POST['comments_action'] == 'approve') {
				// Approve comments
				foreach ($_POST['comment_id'] as $key => $val) {
					$approve_comments[] = (int) $key;
				}
				if (is_array($approve_comments)) {
					// Approve comments
					$approve_comments_query = mysql_query("UPDATE on_comments
                    SET status = '1' WHERE commentid IN (". implode(', ', $approve_comments) .")");
				}

				// Redirect
				redirect_page($settings['siteurl'] .'/admin/content.php?a=file_comments&f='. $file_id, $lang['comment_approved']);
			}
		}
	}

	$comment_status_value = array (
		'0'	=>	'<span class="label label-danger">Не одобрено</span>',
		'1'	=>	'<span class="label label-success">Одобрено</span>'        
	);

	$comments = array();
	// Get categories
	$comments_query = mysql_query("SELECT * FROM on_comments WHERE fileid = '". $file_id ."'");
	while ($comments_row = mysql_fetch_assoc($comments_query)) {
		$comments[] = array (
			'id'				=>	$comments_row['commentid'],
			'comment'			=>	word_filter(bbcode(nl2br(nohtml($comments_row['comment'])))),
			'poster_id'			=>	$comments_row['userid'],
			'poster_username'	=>	(!empty($comments_row['userid'])) ? '<a href="'. profileurl($comments_row['userid'], $comments_row['username']) .'" target="_blank">'. $comments_row['username'] .'</a>' : 'Guest',
			'ip'				=>	$comments_row['ip'],
			'date'				=>	mod_date($comments_row['dateadded']),
			'status'			=>	$comment_status_value[$comments_row['status']]

		);
	}

	$page_title = $lang['comments'];

	// Load template
	template_file_comments();
} elseif ($admincp_action == 'edit_scores') {
	// Here we edit scores
	$file_id = $_GET['f'];

	// File information
	$file_query = mysql_query("SELECT fileid, title, score_type FROM on_files
                               WHERE fileid = '". $file_id ."' && scores = '1' LIMIT 1");
	$file = mysql_fetch_assoc($file_query);

	if (isset($_POST['submit_scores'])) {
		if (isset($_POST['score_id'])) {
			if ($_POST['scores_action'] == 'delete_scores') {
				// Delete scores
				foreach ($_POST['score_id'] as $key => $val) {
					$delete_scores[] = (int) $key;
				}
				if (is_array($delete_scores)) {
					// Delete scores
					$delete_scores_query = mysql_query("DELETE FROM on_scores
                                                        WHERE score_id IN (". implode(', ', $delete_scores) .")");
				}

				// Set high score
				mark_high_score($file_id, $file['score_type']);

				// Redirect
				redirect_page($settings['siteurl'] .'/admin/content.php?a=edit_scores&f='. $file_id, $lang['scores_deleted']);
			}
		}
	}
	// Get scores from database
	if ($file['score_type'] == '1')
		$scores_query = mysql_query("SELECT * FROM on_scores
                                    WHERE file_id = '". $file_id ."'
                                    ORDER BY score DESC,
                                    is_high DESC,
                                    date_score ASC");
	else
		$scores_query = mysql_query("SELECT * FROM on_scores
                                     WHERE file_id = '". $file_id ."'
                                     ORDER BY score ASC,
                                     is_high DESC,
                                     date_score ASC");

	$scores = array();
	$nr = 1;
	while ($scores_row = mysql_fetch_assoc($scores_query)) {
		$scores[] = array (
			'position'	=>	$nr,
			'id'		=>	$scores_row['score_id'],
			'user_id'	=>	$scores_row['user_id'],
			'username'	=>	$scores_row['username'],
			'score'		=>	number_format($scores_row['score'], 1),
			'ip'		=>	$scores_row['ip'],
			'comment'	=>	$scores_row['comment'],
			'date'		=>	mod_date($scores_row['date_score'])
		);
		$nr++;
	}

	$page_title = $lang['scores_title'];

	template_edit_scores();
} elseif ($admincp_action == 'select_file') {
    // педирект при добавлении новой игры
	if (isset($_POST['submit_file'])) {
   	 	// Redirect
		redirect_page($settings['siteurl'] .'/admin/content.php?a=add_file&f='. urlencode($_POST['f']) .'&i='. urlencode($_POST['i']), $lang['file_selected']);
   	}

   	$file_array['file'] = array(); $file_array['image'] = array();
	$files_query = mysql_query("SELECT file, icon FROM on_files");
	while ($files_row = mysql_fetch_assoc($files_query)) {
		$file_array['file'][] = $files_row['file'];
		$file_array['image'][] = $files_row['icon'];
	}

	// Get file selection
	$file_selection = '
<select name="f">';
	if ($files_directory = opendir('../files/'. $settings['filesdir'] .'/')) {
		while ($file = readdir($files_directory)) {
			if ($file != '.' && $file != '..' && $file != 'index.html' && !in_array($file ,$file_array['file'])) {
					$file_selection .= '
<option value="'. $file .'">'. $file .'</option>';
			}
		}
		closedir($files_directory);
	}
	$file_selection .= '
</select>';

	// Get image selection
	$image_selection = '
<select name="i">';
	if ($images_directory = opendir('../files/image/')) {
		while ($image = readdir($images_directory)) {
			if ($image != '.' && $image != '..' && $image != 'index.html' && !in_array($image ,$file_array['image'])) {
					$image_selection .= '
<option value="'. $image .'">'. $image .'</option>';
			}
		}
		closedir($images_directory);
	}
	$image_selection .= '
</select>';

	$page_title = $lang['select_file'];

	// Load template
	template_select_file();
} elseif ($admincp_action == 'upload_file') {
	if (isset($_POST['submit_file'])) {
		$redirect_url = $settings['siteurl'] .'/admin/content.php?a=add_file';

		// Upload file
		if (strlen($_FILES['upload_file']['name'])) {
			$file_directory = '../files/'. $settings['filesdir'] .'/';
			$file_name = date('YmjHis').rand(0,99).$_FILES['upload_file']['name'];
			$upload_file_name = $file_directory . $file_name;

	    	if (file_exists($upload_file_name)) {
				$file_name = substr(md5(uniqid(rand())), 0, 4 ) .'_'. $file_name;
				$upload_file_name = $file_directory . $file_name;
			}

	  		if (copy($_FILES['upload_file']['tmp_name'], $upload_file_name)) {
		    	$redirect_url .= '&f='. $file_name ;
	    	}
    	}

    	// Upload image
    	if (strlen($_FILES['upload_image']['name'])) {
    		$image_directory = '../files/image/';
			$image_name = date('YmjHis').rand(0,99).$_FILES['upload_image']['name'];
			$upload_image_name = $image_directory . $image_name;

	    	if (file_exists($upload_image_name)) {
				$image_name = substr(md5(uniqid(rand())), 0, 4 ) .'_'. $image_name;
				$upload_image_name = $image_directory . $image_name;
			}

	    	if (copy($_FILES['upload_image']['tmp_name'], $upload_image_name)) {
			    $redirect_url .= '&i='. $image_name;
	   	 	}
   	 	}
   	 	// Redirect
		redirect_page($redirect_url, $lang['file_uploaded']);
   	}

   	// Check files directory permissions
	if (!is_writable('../files/'. $settings['filesdir'] .'/') || !is_writable('../files/image/')) {
		$upload_error = $lang['cannot_write_files_directory'];
	}

	$page_title = $lang['upload_file'];

	// Load template
	template_upload_file();
} elseif ($admincp_action == 'grab_file') {
	if (isset($_POST['submit_file'])) {
	 	$redirect_url = $settings['siteurl'] .'/admin/content.php?a=add_file';

	 	// Grab file
		if (strlen($_POST['grab_file'])) {
	 		$file_directory = '../files/'. $settings['filesdir'] .'/';

			// Get file name
			$file_name = $_POST['grab_file'];
			while(strstr($file_name, '/')){
			 //$rash_name = substr($file_name, strpos($file_name, '.'), 999);
             $rash_name = '.'. end(explode(".", $file_name));             
			 $file_name = date('YmjHis').rand(0,99).$rash_name;
//				$file_name = date('YmjHis').rand(0,99).substr($file_name, strpos($file_name, '/') + 1, 999);
                
			}
			$grab_file_name = $file_directory . $file_name;

			// If file already exists then change it's name
			if (file_exists($grab_file_name)) {
				$file_name = substr(md5(uniqid(rand())), 0, 4 ) .'_'. $file_name;
				$grab_file_name = $file_directory . $file_name;
			}

			if (copy_file($_POST['grab_file'], $grab_file_name)) {
				$redirect_url .= '&f='. $file_name ;
        	}
		}

		// Grab image
		if (strlen($_POST['grab_image'])) {
	 		$image_directory = '../files/image/';

			// Get image name
			$image_name = $_POST['grab_image'];
			while(strstr($image_name, '/')){
			 	//$rash_name = substr($file_name, strpos($file_name, '.'), 999);
                $rash_name = '.'. end(explode(".", $image_name));                 
				$image_name = date('YmjHis').rand(0,99).$rash_name;                              
//				$image_name = date('YmjHis').rand(0,99).substr($image_name, strpos($image_name, '/') + 1, 999);
			}
			$grab_image_name = $image_directory . $image_name;

			// If file already exists then change it's name
			if (file_exists($grab_image_name)) {
				$image_name = substr(md5(uniqid(rand())), 0, 4 ) .'_'. $image_name;
				$grab_image_name = $image_directory . $image_name;
			}

			if (copy_file($_POST['grab_image'], $grab_image_name)) {
				$redirect_url .= '&i='. $image_name ;
        	}
		}

	 	// Redirect
		redirect_page($redirect_url, $lang['file_grabbed']);
   	}

   	// Check files directory permissions
	if (!is_writable('../files/'. $settings['filesdir'] .'/') || !is_writable('../files/image/')) {
		$grab_error = $lang['cannot_write_files_directory'];
	}

	$page_title = $lang['grab_file'];

	// Load on  Arcade template
	template_grab_file();
} elseif($admincp_action == 'download_youtube') {
	if (isset($_POST['submit_file'])) {
		if (ereg('^(http://|http://www.)youtube.com/watch\?v=', $_POST['url'])) {
			$youtube_source = @file_get_contents($_POST['url']);
			@preg_match('#/watch_fullscreen\?video_id=([a-z0-9-_]+)&l=([0-9]+)&t=([a-z0-9-_]+)#i', $youtube_source, $video_codes);
			if (ereg($video_codes[1], $_POST['url'])) {
				if (file_exists('../files/'. $settings['filesdir'] .'/'. $video_codes[1] .'.flv') || file_exists('../files/image/'. $video_codes[1] .'.jpg')) {
					$random_key = substr(md5(uniqid(rand())), 0, 4 );
					$file_name = $random_key .'_'. $video_codes[1] .'.flv';
					$image_name = $random_key .'_'. $video_codes[1] .'.jpg';
				} else {
					$file_name = $video_codes[1] .'.flv';
					$image_name = $video_codes[1] .'.jpg';
				}
				// Download file
				copy_file ('http://www.youtube.com/get_video?video_id='. $video_codes[1] .'&l='. $video_codes[2] .'&t='. $video_codes[3], '../files/'. $settings['filesdir'] .'/'. $file_name);
				// Download image
				copy_file ('http://img.youtube.com/vi/'. $video_codes[1] .'/default.jpg', '../files/image/'. $image_name);

				// Redirect
				redirect_page($settings['siteurl'] .'/admin/content.php?a=add_file&f='. $video_codes[1] .'.flv&i='. $video_codes[1] .'.jpg', $lang['file_downloaded']);
			} else {
				$youtube_error = $lang['invalid_url'];
			}
		} else {
			$youtube_error = $lang['invalid_url'];
		}
	}

	// Check files directory permissions
	if (!is_writable('../files/'. $settings['filesdir'] .'/') || !is_writable('../files/image/')) {
		$grab_error = $lang['cannot_write_files_directory'];
	}

	$page_title = $lang['downloaded_from_youtube'];

	// Load template
	template_download_youtube();
} elseif ($admincp_action == 'approve_files') {
	if (isset($_GET['approve']) && is_numeric($_GET['approve'])) {
		$update_file_status_query = mysql_query("UPDATE on_files SET status = '1' WHERE fileid = '". $_GET['approve'] ."'");

		// Update statistics table
		$update_stats_query = mysql_query("UPDATE on_statistics SET total_files = total_files + 1 WHERE stats_id = '". $stats['id'] ."' LIMIT 1");

		// Redirect
		redirect_page($settings['siteurl'] .'/admin/content.php?a=approve_files', $lang['file_marked_active']);
	} elseif (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
		$file_query = mysql_query("SELECT file, icon, filelocation, iconlocation FROM on_files WHERE fileid = '". $_GET['delete'] ."' && status = '0' LIMIT 1");
		$file_row = mysql_fetch_assoc($file_query);
		if (!empty($file_row)) {
			// o n A r c a d e 2 deletes files
			if ($file_row['filelocation'] == '1' && strlen($file_row['file'])) {
				unlink('../files/'. $settings['filesdir'] .'/'. $file_row['file']);
			}
			if ($file_row['iconlocation'] == '1' && strlen($file_row['icon'])) {
				unlink('../files/image/'. $file_row['icon']);
			}

			$delete_file_query = mysql_query("DELETE FROM on_files WHERE fileid = '". $_GET['delete'] ."'");

			// Redirect
			redirect_page($settings['siteurl'] .'/admin/content.php?a=approve_files', $lang['file_deleted']);
		}
	}

	$files = array ();
	// Get o2 files
	$files_query = mysql_query("
		SELECT
			file.*, cat.name AS category_title, user.username
		FROM
			on_files AS file
			LEFT JOIN on_categories AS cat ON (cat.catid = file.category)
			LEFT JOIN on_users AS user ON (user.userid = file.added_by)
		WHERE file.status = '0'");
	while ($files_row = mysql_fetch_assoc($files_query)) {
		$files[] = array (
			'id'			=>	$files_row['fileid'],
			'title'			=>	$files_row['title'],
			'file'			=>	$settings['siteurl'] .'/files/'. $settings['filesdir'] .'/'. $files_row['file'],
			'category'		=>	$files_row['category_title'],
			'file_type'		=>	$files_row['filetype'],
			'date_added'	=>	mod_date($files_row['dateadded']),
			'added_by'		=>	'<a href="'. profileurl($files_row['added_by'], nohtml($files_row['username'])) .'" target="_blank">'. nohtml($files_row['username']) .'</a>'
		);
	}

	$page_title = $lang['approve_files'];

	// Load template
	template_approve_files();
} elseif ($admincp_action == 'approve_comments') {
	if (isset($_POST['submit_comments'])) {
		if (isset($_POST['comment_id'])) {
			if ($_POST['comments_action'] == 'delete_comment') {
				// Delete comments
				foreach ($_POST['comment_id'] as $key => $val) {
					$delete_comments[] = (int) $key;
				}
				if (is_array($delete_comments)) {
					// Delete comments
					$delete_comments_query = mysql_query("DELETE FROM on_comments WHERE commentid IN (". implode(', ', $delete_comments) .")");
				}

				// Redirect
				redirect_page($settings['siteurl'] .'/admin/content.php?a=approve_comments', $lang['comment_deleted']);
			} elseif ($_POST['comments_action'] == 'approve') {
				// Approve comments
				foreach ($_POST['comment_id'] as $key => $val) {
					$approve_comments[] = (int) $key;
				}
				if (is_array($approve_comments)) {
					// Approve comments
					$approve_comments_query = mysql_query("UPDATE on_comments SET status = '1' WHERE commentid IN (". implode(', ', $approve_comments) .")");
				}

				// Redirect
				redirect_page($settings['siteurl'] .'/admin/content.php?a=approve_comments', $lang['comment_approved']);
			}
		}
	}

	$comments = array();
	// Get categories
	$comments_query = mysql_query("
		SELECT
			c.*, f.title
		FROM
			on_comments AS c
			LEFT JOIN on_files as f ON (f.fileid = c.fileid)
		WHERE c.status = '0'");
	while ($comments_row = mysql_fetch_assoc($comments_query)) {
		$comments[] = array (
			'id'				=>	$comments_row['commentid'],
			'comment'			=>	word_filter(bbcode(nl2br(nohtml($comments_row['comment'])))),
			'poster_id'			=>	$comments_row['userid'],
			'poster_username'	=>	(!empty($comments_row['userid'])) ? '<a href="'. profileurl($comments_row['userid'], $comments_row['username']) .'" target="_blank">'. $comments_row['username'] .'</a>' : $lang['guest'],
			'ip'				=>	$comments_row['ip'],
			'date'				=>	mod_date($comments_row['dateadded']),
			'file_id'			=>	$comments_row['fileid'],
			'file_title'		=>	$comments_row['title']
		);
	}

	$page_title = $lang['approve_comments'];

	// Load template
	template_approve_comments();
} elseif ($admincp_action == 'game_feed') {
	if (strlen($_GET['f'])) {
		if ($read_feed = file('http://www.onarcade.com/game_feeds/'. $_GET['f'])) {
			$feed_name = nohtml($_GET['f']);
			// Install selected game
			if (strlen($_GET['g'])) {
				$feed_line = explode('|', escape_string($read_feed[$_GET['g']]));
				// Check if file is already in the database
    			$file_check_query = mysql_query("SELECT count(fileid) FROM on_files WHERE title = '". $feed_line[0] ."'");
    			$file_check_row = mysql_fetch_array($file_check_query);
    			if ($file_check_row['count(fileid)'] > '0') {
					$feed_error = $lang['file_already_database'];
				} else {
	    			$update_played_query = mysql_query("UPDATE on_statistics SET total_files = total_files + 1 WHERE stats_id = '". $stats['id'] ."'");

	    			// Download files from server
					$file_directory = '../files/'. $settings['filesdir'] .'/';
					$file_name = $feed_line[6];
					$download_file_name = $file_directory . $file_name;

					// If file already exists then change it's name
					if (file_exists($download_file_name)) {
						$file_name = substr(md5(uniqid(rand())), 0, 4) .'_'. $file_name;
						$download_file_name = $file_directory . $file_name;
					}

					// Download file
					copy_file('http://www.onarcade.com/game_feeds/download_file.php?f='. $feed_line[6] .'&key='. urlencode($license_key), $download_file_name);

					$image_directory = '../files/image/';
					$image_name = $feed_line[7];
					$download_image_name = $image_directory . $image_name;

					// If file already exists then change it's name
					if (file_exists($download_image_name)) {
						$image_name = substr(md5(uniqid(rand())), 0, 4) .'_'. $image_name;
						$download_image_name = $image_directory . $image_name;
					}

					// Download image
					copy_file('http://www.onarcade.com/game_feeds/files/image/'. $feed_line[7], $download_image_name);

					// Add to database
					$add_file_query = mysql_query("INSERT INTO on_files
                    SET title = '". $feed_line[0] ."',
                    description = '". $feed_line[1] ."',
                    keywords = '". $feed_line[0] ."',
                    category = '". $feed_line[2] ."',
                    width = '". $feed_line[3] ."',
                    height = '". $feed_line[4] ."',
                    filetype = '". $feed_line[5] ."',
                    file = '". $file_name ."',
                    filelocation = '1',
                    icon = '". $image_name ."', iconlocation = '1',
                    status = '1',
                    dateadded = '". time() ."',
                    added_by = '". $user['id'] ."',
                    adult = '". $feed_line[8] ."',
                    scores = '". $feed_line[9] ."',
                    score_type = '". trim($feed_line[10]) ."'");

					// Redirect
					redirect_page($settings['siteurl'] .'/admin/content.php?a=game_feed&f='. $feed_name, $lang['file_added']);
    			}
			}

			// Get categories
			$categories_query = mysql_query("SELECT name, catid FROM on_categories");
			while($categories_row = mysql_fetch_array($categories_query)) {
				$category_value[$categories_row['catid']] = $categories_row['name'];
			}

			foreach($read_feed as $key => $feed_line) {
				$feed_line = trim($feed_line);
				$feed_line = explode('|', $feed_line);
				$feed[] = array (
					'id'			=>	$key,
					'title'			=>	$feed_line[0],
					'description'	=>	$feed_line[1],
					'category'		=>	$category_value[$feed_line[2]],
					'file_type'		=>	$feed_line[5],
					'scores'		=>	($feed_line[9] == '1') ? '; '. $lang['feed_scores'] : '',
					'image'			=>	'http://www.onarcade.com/game_feeds/files/image/'. $feed_line[7]
				);
			}
			$page_title = $lang['game_feed'];

			// Load template
			template_game_feed_games();
			exit();
		}
	}

	// Check files directory permissions
	if (!is_writable('../files/'. $settings['filesdir'] .'/') || !is_writable('../files/image/')) {
		$feed_error = $lang['cannot_write_files_directory'];
	}

	$read_feed = file('http://www.onarcade.com/game_feeds/game_feeds.txt');

	foreach($read_feed as $feed_line) {
		$feed_line = trim($feed_line);
		$feed_line = explode('|', $feed_line);
		$feed[] = array (
			'title'	=>	$feed_line[0],
			'file'	=>	$feed_line[1]
		);
	}

	$page_title = $lang['game_feed'];

	// Load template
	template_game_feed();
} elseif ($admincp_action == 'news') {
	if (isset($_POST['submit_news'])) {
		if (isset($_POST['news_id'])) {
			if ($_POST['news_action'] == 'delete') {
				// Delete news
				foreach ($_POST['news_id'] as $key => $val) {
					$delete_news[] = (int) $key;
				}
				if (is_array($delete_news)) {
					// Delete news
					$delete_news_query = mysql_query("DELETE FROM on_news WHERE newsid IN (". implode(', ', $delete_news) .")");
				}

				// Redirect
				redirect_page($settings['siteurl'] .'/admin/content.php?a=news', $lang['news_deleted']);
			} elseif ($_POST['news_action'] == 'active') {
				// Activate news
				foreach ($_POST['news_id'] as $key => $val) {
					$update_news[] = (int) $key;
				}
				if (is_array($update_news)) {
					$update_news_status_query = mysql_query("UPDATE on_news SET status = '1' WHERE newsid IN (". implode(', ', $update_news) .")");
				}

				// Redirect
				redirect_page($settings['siteurl'] .'/admin/content.php?a=news', $lang['news_marked_active']);
			} elseif ($_POST['news_action'] == 'inactive') {
				// Inactivate news
				foreach ($_POST['news_id'] as $key => $val) {
					$update_news[] = (int) $key;
				}
				if (is_array($update_news)) {
					$update_news_status_query = mysql_query("UPDATE on_news SET status = '0' WHERE newsid IN (". implode(', ', $update_news) .")");
				}

				// Redirect onArcade 2
				redirect_page($settings['siteurl'] .'/admin/content.php?a=news', $lang['news_marked_inactive']);
			}
		}
	}

	$news_status_value = array (
		'0'	=>	'<span class="label label-danger">'. $lang['inactive'] .'</span>',
		'1'	=>	'<span class="label label-success">'. $lang['active'] .'</span>'        
	);

	$news_query = mysql_query("
		SELECT
			n.*, u.username
		FROM
			on_news AS n
			LEFT JOIN on_users AS u ON (u.userid = n.author)
		ORDER BY n.newsid DESC");
	$news = array ();
	while($news_row = mysql_fetch_assoc($news_query)) {
		$news[] = array (
			'id'		=>	$news_row['newsid'],
			'title'		=>	nohtml($news_row['title']),
			'date'		=>	mod_date($news_row['date']),
			'author_id'	=>	$news_row['author'],
			'author'	=>	nohtml($news_row['username']),
			'status'	=>	$news_status_value[$news_row['status']]
		);
	}

	$page_title = $lang['news'];

	// Load template
	template_news();
} elseif ($admincp_action == 'add_news') {
	if (isset($_POST['submit_news']) && strlen($_POST['title'])) {
		// Insert news into database
		$add_news_query = mysql_query("INSERT INTO on_news
		 SET title = '". $_POST['title'] ."',
		 message = '". $_POST['message'] ."',
		 status = '". $_POST['news_status'] ."',
		 date = '". time() ."',
		 author = '". $user['id'] ."'");

		// Redirect
		redirect_page($settings['siteurl'] .'/admin/content.php?a=news', $lang['news_added']);
	}

	$page_title = $lang['add_news'];

	// Load template
	template_add_news();
} elseif ($admincp_action == 'edit_news') {
	$news_id  = (int) $_GET['n'];
	if (isset($_POST['submit_news'])) {
		// Update news information
		$edit_news_query = mysql_query("UPDATE on_news SET title = '". $_POST['title'] ."', message = '". $_POST['message'] ."', status = '". $_POST['news_status'] ."' WHERE newsid = '". $news_id ."'");

		// Redirect
		redirect_page($settings['siteurl'] .'/admin/content.php?a=edit_news&n='. $news_id, $lang['news_updated']);
	}

	// Get news information
	$news_query = mysql_query("SELECT * FROM on_news WHERE newsid = '". $news_id ."' LIMIT 1");
	$news_row = mysql_fetch_assoc($news_query);

	$edit_news = array (
		'title'			=>	nohtml($news_row['title']),
		'message'		=>	nohtml($news_row['message']),
		'status'		=>	$news_row['status']
	);

	$page_title = $lang['edit_news'];

	// Load template
	template_edit_news();
} elseif ($admincp_action == 'links') {
	if (isset($_POST['submit_links'])) {
		if (isset($_POST['link_id'])) {
			if ($_POST['links_action'] == 'delete') {
				// Delete link(s)
				foreach ($_POST['link_id'] as $key => $val) {
					$delete_links[] = (int) $key;
				}
				if (is_array($delete_links)) {
					// Delete links
					$delete_links_query = mysql_query("DELETE FROM on_links WHERE linkid IN (". implode(', ', $delete_links) .")");
				}

				// Redirect
				redirect_page($settings['siteurl'] .'/admin/content.php?a=links', $lang['link_deleted']);
			} elseif ($_POST['links_action'] == 'active') {
				// Activate link(s)
				foreach ($_POST['link_id'] as $key => $val) {
					$update_links[] = (int) $key;
				}
				if (is_array($update_links)) {
					$update_links_status_query = mysql_query("UPDATE on_links SET status = '1' WHERE linkid IN (". implode(', ', $update_links) .")");
				}

				// Redirect
				redirect_page($settings['siteurl'] .'/admin/content.php?a=links', $lang['link_marked_active']);
			} elseif ($_POST['links_action'] == 'inactive') {
				// Inactivate link(s)
				foreach ($_POST['link_id'] as $key => $val) {
					$update_links[] = (int) $key;
				}
				if (is_array($update_links)) {
					$update_links_status_query = mysql_query("UPDATE on_links SET status = '2' WHERE linkid IN (". implode(', ', $update_links) .")");
				}

				// Redirect
				redirect_page($settings['siteurl'] .'/admin/content.php?a=links', $lang['link_marked_inactive']);
			}
		}

	}

	$link_status_value = array (
		'2'	=>	'<span class="label label-danger">'. $lang['inactive'] .'</span>',
		'1'	=>	'<span class="label label-success">'. $lang['active'] .'</span>'        
	);

	$links = array ();
	// Get links
	$links_query = mysql_query("SELECT * FROM on_links WHERE status IN ('1', '2') ORDER BY name");
	while ($links_row = mysql_fetch_assoc($links_query)) {
		$links[] = array (
			'id'			=>	$links_row['linkid'],
			'title'			=>	$links_row['name'],
			'url'			=>	$links_row['linkurl'],
			'description'	=>	$links_row['description'],
			'hits_in'		=>	number_format($links_row['hitsin']),
			'hits_out'		=>	number_format($links_row['hits_out']),
			'status'		=>	$link_status_value[$links_row['status']]
		);
	}

	$page_title = $lang['links'];

	// Load template
	template_links();
} elseif ($admincp_action == 'approve_links') {
	if (strlen($_GET['approve_link'])) {
		// Get link information
		$links_query = mysql_query("SELECT * FROM on_links WHERE linkid = '". (int) $_GET['approve_link'] ."'");
		$links_row = mysql_fetch_assoc($links_query);

		// Send email to the submitter
		$email_header = 'Return-Path: '. $settings['sitecontactemail'] .'
From: '. $settings['sitename'] .' <'. $settings['sitecontactemail'] .'>
MIME-Version: 1.0
Content-type: text/plain';

		include ('../languages/'. $settings['language'] .'/email.lang.php');
		@mail($links_row['email'], $lang['link_approved_email'], $lang['link_approved_message'], $email_header);

		$update_link_status_query = mysql_query("UPDATE on_links SET status = '1' WHERE linkid = '". (int) $_GET['approve_link'] ."'");

		// Redirect
		redirect_page($settings['siteurl'] .'/admin/content.php?a=approve_links', $lang['link_approved']);
	} elseif (strlen($_GET['delete_link'])) {
		// Delete link from database
		$delete_link_query = mysql_query("DELETE FROM on_links WHERE linkid = '". (int) $_GET['delete_link'] ."' LIMIT 1");

		// Redirect
		redirect_page($settings['siteurl'] .'/admin/content.php?a=approve_links', $lang['link_deleted']);
	}

	$links = array ();
	// Get links
	$links_query = mysql_query("SELECT * FROM on_links WHERE status = '0'");
	while ($links_row = mysql_fetch_assoc($links_query)) {
		$links[] = array (
			'id'			=>	$links_row['linkid'],
			'title'			=>	$links_row['name'],
			'url'			=>	$links_row['linkurl'],
			'description'	=>	$links_row['description'],
			'email'			=>	$links_row['email'],
			'ip'			=>	$links_row['ip']
		);
	}

	$page_title = $lang['approve_links'];

	// Load template
	template_approve_links();
} elseif ($admincp_action == 'add_link') {
	if (isset($_POST['submit_link']) && strlen($_POST['title'])) {
		// Insert link into database
		$add_link_query = mysql_query("INSERT INTO on_links
					       SET linkurl = '". $_POST['url'] ."',
					       name = '". $_POST['title'] ."',
					       description = '". $_POST['description'] ."',
					       email = '". $_POST['email'] ."',
					       ip = '". $user['ip'] ."',
					       status = '". $_POST['link_status'] ."'");

		// Redirect
		redirect_page($settings['siteurl'] .'/admin/content.php?a=links', $lang['link_added']);
	}

	$page_title = $lang['add_link'];

	// Load template
	template_add_link();
} elseif ($admincp_action == 'edit_link') {
	$link_id = (int) $_GET['l'];
	if (isset($_POST['submit_link'])) {
		// Update link information
		$edit_link_query = mysql_query("UPDATE on_links SET linkurl = '". $_POST['url'] ."', name = '". $_POST['title'] ."', description = '". $_POST['description'] ."', email = '". $_POST['email'] ."', status = '". $_POST['link_status'] ."' WHERE linkid = '". $link_id ."'");

		// Redirect
		redirect_page($settings['siteurl'] .'/admin/content.php?a=edit_link&l='. $link_id, $lang['link_updated']);
	}

	// Get link information
	$link_query = mysql_query("SELECT * FROM on_links WHERE linkid = '". $link_id ."' LIMIT 1");
	$link_row = mysql_fetch_assoc($link_query);

	// on_Arcade
	$edit_link = array (
		'title'			=>	$link_row['name'],
		'url'			=>	$link_row['linkurl'],
		'description'	=>	$link_row['description'],
		'email'			=>	$link_row['email'],
		'hits_in'		=>	number_format($link_row['hitsin']),
		'hits_out'		=>	number_format($link_row['hits_out']),
		'ip'			=>	$link_row['ip'],
		'status'		=>	$link_row['status']
	);

	$page_title = $lang['edit_link'];

	// Load template
	template_edit_link();
} elseif ($admincp_action == 'ads') {
	if (isset($_POST['submit_ads'])) {
		if (isset($_POST['ad_id'])) {
			if ($_POST['ads_action'] == 'delete') {
				// Delete ad(s)
				foreach ($_POST['ad_id'] as $key => $val) {
					$delete_ads[] = (int) $key;
				}
				if (is_array($delete_ads)) {
					// Delete ads
					$delete_ads_query = mysql_query("DELETE FROM on_ads WHERE ad_id IN (". implode(', ', $delete_ads) .")");
				}

				// Redirect
				redirect_page($settings['siteurl'] .'/admin/content.php?a=ads', $lang['ad_deleted']);
			} elseif ($_POST['ads_action'] == 'active') {
				// Activate ad(s)
				foreach ($_POST['ad_id'] as $key => $val) {
					$update_ads[] = (int) $key;
				}
				if (is_array($update_ads)) {
					$update_ads_status_query = mysql_query("UPDATE on_ads SET status = '1' WHERE ad_id IN (". implode(', ', $update_ads) .")");
				}

				// Redirect
				redirect_page($settings['siteurl'] .'/admin/content.php?a=ads', $lang['ad_marked_active']);
			} elseif ($_POST['ads_action'] == 'inactive') {
				// Inactivate ad(s)
				foreach ($_POST['ad_id'] as $key => $val) {
					$update_ads[] = (int) $key;
				}
				if (is_array($update_ads)) {
					$update_ads_status_query = mysql_query("UPDATE on_ads SET status = '0' WHERE ad_id IN (". implode(', ', $update_ads) .")");
				}

				// Redirect
				redirect_page($settings['siteurl'] .'/admin/content.php?a=ads', $lang['ad_marked_inactive']);
			}
		}

	}

	// Some variables
	$ad_status_value = array (
		'0'	=>	'<span class="label label-danger">'. $lang['inactive'] .'</span>',
		'1'	=>	'<span class="label label-success">'. $lang['active'] .'</span>'        
	);
	$ad_zone_value = array (
		'1'	=>	$lang['header_zone'],
		'2'	=>	$lang['footer_zone'],
		'3'	=>	$lang['file_zone'],
		'4'	=>	$lang['before_file_zone']
	);

	$ads = array ();
	// Get ads
	$ads_query = mysql_query("SELECT * FROM on_ads");
	while ($ads_row = mysql_fetch_assoc($ads_query)) {
		$ads[] = array (
			'id'		=>	$ads_row['ad_id'],
			'zone'		=>	$ad_zone_value[$ads_row['ad_zone']],
			'date'		=>	mod_date($ads_row['added']),
			'status'	=>	$ad_status_value[$ads_row['status']]
		);
	}

	$page_title = $lang['ads'];

	// Load template
	template_ads();
} elseif ($admincp_action == 'add_ad') {
	if (isset($_POST['submit_ad'])) {
		// Get ad code
		if ($_POST['ad_type'] == 'banner') {
			$ad_code = '<a href="'. $_POST['link'] .'" target="_blank"><img src="'. $_POST['banner'] .'" border="0" /></a>';
		} else {
			$ad_code = $_POST['ad_code'];
		}

		// Insert into database
		$add_category_query = mysql_query("INSERT INTO on_ads SET ad_code = '". $ad_code ."', ad_zone = '". $_POST['ad_zone'] ."', added = '". time() ."', status = '". $_POST['ad_status'] ."'");

		// Redirect
		redirect_page($settings['siteurl'] .'/admin/content.php?a=ads', $lang['ad_added']);
	}

	$page_title = $lang['add_ad'];

	// Load template
	template_add_ad();
} elseif ($admincp_action == 'edit_ad') {
	$ad_id = (int) $_GET['ad'];
	if (isset($_POST['submit_ad'])) {
		// Update ad information
		$edit_category_query = mysql_query("UPDATE on_ads SET ad_code = '". $_POST['ad_code'] ."', ad_zone = '". $_POST['ad_zone'] ."', status = '". $_POST['ad_status'] ."' WHERE ad_id = '". $ad_id ."' LIMIT 1");

		// Redirect
		redirect_page($settings['siteurl'] .'/admin/content.php?a=edit_ad&ad='. $ad_id, $lang['ad_updated']);
	}

	// Get ad information
	$ad_query = mysql_query("SELECT * FROM on_ads WHERE ad_id = '". $ad_id ."' LIMIT 1");
	$ad_row = mysql_fetch_assoc($ad_query);
	$edit_ad = array (
		'code'		=>	nohtml($ad_row['ad_code']),
		'ad_zone' 	=>	$ad_row['ad_zone'],
		'ad_status'	=>	$ad_row['status']
	);

	$page_title = $lang['edit_ad'];

	// Load template
	template_edit_ad();
} elseif ($admincp_action == 'custom_pages') {
	if (isset($_POST['submit_pages'])) {
		if (isset($_POST['page_id'])) {
			if ($_POST['pages_action'] == 'delete') {
				// Delete page(s)
				foreach ($_POST['page_id'] as $key => $val) {
					$delete_pages[] = (int) $key;
				}
				if (is_array($delete_pages)) {
					// Delete pages
					$delete_pages_query = mysql_query("DELETE FROM on_pages WHERE pageid IN (". implode(', ', $delete_pages) .")");
				}

				// Redirect
				redirect_page($settings['siteurl'] .'/admin/content.php?a=custom_pages', $lang['page_deleted']);
			} elseif ($_POST['pages_action'] == 'active') {
				// Activate page(s)
				foreach ($_POST['page_id'] as $key => $val) {
					$update_pages[] = (int) $key;
				}
				if (is_array($update_pages)) {
					$update_pages_status_query = mysql_query("UPDATE on_pages SET status = '1' WHERE pageid IN (". implode(', ', $update_pages) .")");
				}

				// Redirect
				redirect_page($settings['siteurl'] .'/admin/content.php?a=custom_pages', $lang['page_marked_active']);
			} elseif ($_POST['pages_action'] == 'inactive') {
				// Inactivate page(s)
				foreach ($_POST['page_id'] as $key => $val) {
					$update_pages[] = (int) $key;
				}
				if (is_array($update_pages)) {
					$update_pages_status_query = mysql_query("UPDATE on_pages SET status = '0' WHERE pageid IN (". implode(', ', $update_pages) .")");
				}

				// Redirect
				redirect_page($settings['siteurl'] .'/admin/content.php?a=custom_pages', $lang['page_marked_inactive']);
			}
		}

	}

	// Some variables
	$pages_status_value = array (
		'0'	=>	'<span class="label label-danger">'. $lang['inactive'] .'</span>',
		'1'	=>	'<span class="label label-success">'. $lang['active'] .'</span>'        
	);

	// Get pages
	$custom_pages_query = mysql_query("SELECT * FROM on_pages");
	$custom_pages = array ();
	while($custom_pages_row = mysql_fetch_assoc($custom_pages_query)) {
		$custom_pages[] = array (
			'id'			=>	$custom_pages_row['pageid'],
			'title'			=>	($settings['sefriendly'] == '1' ? '<a href="'. $settings['siteurl'] .'/page/'. $custom_pages_row['pageid'] .'.html">'. nohtml($custom_pages_row['title']) .'</a>' : '<a href="'. $settings['siteurl'] .'/page.php?p='. $custom_pages_row['pageid'] .'">'. nohtml($custom_pages_row['title']) .'</a>'),
			'description'	=>	nohtml($custom_pages_row['description']),
			'status'		=>	$pages_status_value[$custom_pages_row['status']]
		);
	}

	$page_title = $lang['custom_pages'];

	// Load template
	template_custom_pages();
} elseif ($admincp_action == 'add_custom_page') {
	if (isset($_POST['submit_page']) && strlen($_POST['title'])) {
		// Insert page into database
		$add_custom_page_query = mysql_query("INSERT INTO on_pages SET title = '". $_POST['title'] ."', content = '". $_POST['content'] ."', keywords = '". $_POST['keywords'] ."', description = '". $_POST['description'] ."', status = '". $_POST['page_status'] ."'");

		// Redirect
		redirect_page($settings['siteurl'] .'/admin/content.php?a=custom_pages', $lang['page_added']);
	}

	$page_title = $lang['add_custom_page'];

	// Load template
	template_add_custom_page();
} elseif ($admincp_action == 'edit_page') {
	$page_id  = (int) $_GET['page'];
	if (isset($_POST['submit_page'])) {
		// Update page information
		$edit_custom_page_query = mysql_query("UPDATE on_pages SET title = '". $_POST['title'] ."', content = '". $_POST['content'] ."', keywords = '". $_POST['keywords'] ."', description = '". $_POST['description'] ."', status = '". $_POST['page_status'] ."' WHERE pageid = '". $page_id ."' LIMIT 1");

		// Redirect
		redirect_page($settings['siteurl'] .'/admin/content.php?a=edit_page&page='. $page_id, $lang['page_updated']);
	}

	// Get page information
	$page_query = mysql_query("SELECT * FROM on_pages WHERE pageid = '". $page_id ."' LIMIT 1");
	$page_row = mysql_fetch_assoc($page_query);

	$edit_page = array (
		'title'			=>	nohtml($page_row['title']),
		'description'	=>	nohtml($page_row['description']),
		'keywords'		=>	nohtml($page_row['keywords']),
		'content'		=>	nohtml($page_row['content']),
		'status'		=>	$page_row['status']
	);

	// Page URL
	if ($settings['sefriendly'] == '1') {
		$edit_page['page_url'] = $settings['siteurl'] .'/page/'. $page_id .'.html';
	} else {
		$edit_page['page_url'] = $settings['siteurl'] .'/page.php?p='. $page_id;
	}

	$page_title = $lang['edit_custom_page'];

	// Load template
	template_edit_custom_page();
} else {
	$page = @$_GET['p'];
	if (empty($page) || !is_numeric($page)) {
		$page = '1';
	}
	if (isset($_POST['submit_filessort'])) {
    $_SESSION['sort_files']=$_POST['files_sort'];      	   
    }
    
	if (isset($_POST['submit_files'])) {
		if (isset($_POST['file_id'])) {
			if ($_POST['files_action'] == 'delete') {
				foreach ($_POST['file_id'] as $key => $val) {
					$delete_files[] = (int) $key;
				}
				if (is_array($delete_files)) {
					// Delete files
					$delete_files_query = mysql_query("DELETE FROM on_files WHERE fileid IN (". implode(', ', $delete_files) .")");
					// Delete comments
					$delete_comments_query = mysql_query("DELETE FROM on_comments WHERE fileid IN (". implode(', ', $delete_files) .")");
					// Delete scores
					$delete_scores_query = mysql_query("DELETE FROM on_scores WHERE file_id IN (". implode(', ', $delete_files) .")");

					// We should also recount files
					$recount_files_query = mysql_query("SELECT count(fileid) AS files_count FROM on_files WHERE status = '1'");
					$recount_files_row = mysql_fetch_array($recount_files_query);

					// Update statistics table
					$update_stats_query = mysql_query("UPDATE on_statistics SET total_files = '". $recount_files_row['files_count'] ."' WHERE stats_id = '". $stats['id'] ."'");
				}
				// Redirect
				redirect_page($_SERVER['HTTP_REFERER'], $lang['file_deleted']);
			} elseif ($_POST['files_action'] == 'active') {
				foreach ($_POST['file_id'] as $key => $val) {
					$update_files[] = (int) $key;
				}
				if (is_array($update_files)) {
					$update_files_status_query = mysql_query("UPDATE on_files SET status = '1' WHERE fileid IN (". implode(', ', $update_files) .")");

					// We should also recount files
					$recount_files_query = mysql_query("SELECT count(fileid) AS files_count FROM on_files WHERE status = '1'");
					$recount_files_row = mysql_fetch_array($recount_files_query);

					// Update statistics table
					$update_stats_query = mysql_query("UPDATE on_statistics SET total_files = '". $recount_files_row['files_count'] ."' WHERE stats_id = '". $stats['id'] ."'");
				}
				// Redirect
				redirect_page($_SERVER['HTTP_REFERER'], $lang['file_marked_active']);
			} elseif ($_POST['files_action'] == 'inactive') {
				foreach ($_POST['file_id'] as $key => $val) {
					$update_files[] = (int) $key;
				}
				if (is_array($update_files)) {
					$update_files_status_query = mysql_query("UPDATE on_files SET status = '2' WHERE fileid IN (". implode(', ', $update_files) .")");

					// We should also recount files
					$recount_files_query = mysql_query("SELECT count(fileid) AS files_count FROM on_files WHERE status = '1'");
					$recount_files_row = mysql_fetch_array($recount_files_query);

					// Update statistics table
					$update_stats_query = mysql_query("UPDATE on_statistics SET total_files = '". $recount_files_row['files_count'] ."' WHERE stats_id = '". $stats['id'] ."'");
				}
				// Redirect
				redirect_page($_SERVER['HTTP_REFERER'], 'Файл отмечен как не активный');
			} elseif ($_POST['files_action'] == 'game_slave') {
				foreach ($_POST['file_id'] as $key => $val) {
					$update_files[] = (int) $key;
				}
				if (is_array($update_files)) {
					$update_files_status_query = mysql_query("UPDATE on_files SET status = '3' WHERE fileid IN (". implode(', ', $update_files) .")");

					// We should also recount files
					$recount_files_query = mysql_query("SELECT count(fileid) AS files_count FROM on_files WHERE status = '1'");
					$recount_files_row = mysql_fetch_array($recount_files_query);

					// Update statistics table
					$update_stats_query = mysql_query("UPDATE on_statistics SET total_files = '". $recount_files_row['files_count'] ."' WHERE stats_id = '". $stats['id'] ."'");
				}
				// Redirect
				redirect_page($_SERVER['HTTP_REFERER'], $lang['file_marked_game_slave']);
			}
		}
	}


	$file_status_value = array (
		'2'	=>	'<span class="label label-danger">'. $lang['inactive'] .'</span>',
		'1'	=>	'<span class="label label-success">'. $lang['active'] .'</span>',        
		'3'	=>	'<span class="label label-warning">'. $lang['game_slave'] .'</span>'        
	);

	// onArcade search
	$search_term = escape_string(nohtml(@$_REQUEST['t']));
    

    $sort = escape_string(nohtml(@$_REQUEST['files_sort']));
    if ($sort=="") $search = "title";
    if ($sort=="status1") $search = "status";

	// Count the number of members and pages
	if (strlen($search_term)) {
		$files_number_query = mysql_query("SELECT COUNT(*) 
                                           FROM on_files 
                                           WHERE title LIKE '%". $search_term ."%' && 
                                           status IN ('1', '2', '3')");
	} else {
		$files_number_query = mysql_query("SELECT COUNT(*) 
                                           FROM on_files 
                                           WHERE status IN ('1', '2', '3')");
	}
	$files_number_row = mysql_fetch_assoc($files_number_query);
	$start_here = ($page - 1) * 100;
	$pages_count = ceil($files_number_row['COUNT(*)'] / 100);

	// Build navigation menu
	$nav = NULL;
    $nav .= '<ul class="pagination pagination-centered pagination-sm">';
	if ($page > 1) {
		$page_number  = $page - 1;
		$nav .= '<li><a href="content.php?p='. $page_number .'&t='. nohtml($_REQUEST['t']) .'&s='.$_SESSION['sort_files'].'">&lt;</a></li>';
	}
	for ($page_number = 1; $page_number <= $pages_count; $page_number++) {
	   		$nav .= '<li ';
		if ($page_number == $page) $nav .= ' class="active" ';
	    	$nav .= ' ><a href="content.php?p='. $page_number .'&t='. $search_term .'&s='.@$_SESSION['sort_files'].'">'. $page_number .'</a></li>';		
	}
	if ($page < $pages_count) {
		$page_number  = $page + 1;
		$nav .= '<li><a href="content.php?p='. $page_number .'&t='. $search_term .'&s='.@$_SESSION['sort_files'].'">&gt;</a></li>';
	}
    $nav .= '</ul>';
	$files = array ();
// по какому признаку будет сортировка, тип сориторвки в переменной $_SESSION['sort_files']
	if (@$_SESSION['sort_files'] == "status") { //По статусу
      $sort = 'status';
    } elseif (@$_SESSION['sort_files'] == "dateadded") { //По дате
      $sort = 'dateadded';
    } elseif (@$_SESSION['sort_files'] == "category") {  //По категории
      $sort = 'category';        
    } elseif (@$_SESSION['sort_files'] == "filetype") {  //По расширению
      $sort = 'filetype';                                                    
    } elseif (@$_SESSION['sort_files'] == "rating") {    //По рейтингу
      $sort = 'rating';    
    } elseif (@$_SESSION['sort_files'] == "filelocation"){ //По расположению файла
      $sort = 'filelocation';                                                    
 	} elseif (@$_SESSION['sort_files'] == "alph"){ //По алфавиту 
      $sort = 'title';
	} else {
      $sort = 'title';	   
	}
    
	// Get files
	if (strlen($search_term)) {
		$files_query = mysql_query("
			SELECT
				file.*, cat.name AS category_title
			FROM
				on_files AS file
				LEFT JOIN on_categories AS cat ON (cat.catid = file.category)
			WHERE
				file.title LIKE '%". $search_term ."%' && file.status IN ('1', '2', '3')
			ORDER BY ".$sort." LIMIT ". $start_here .", 100");
	} else {
		$files_query = mysql_query("
			SELECT
				file.*, cat.name AS category_title
			FROM
				on_files AS file
				LEFT JOIN on_categories AS cat ON (cat.catid = file.category)
			WHERE
				file.status IN ('1', '2', '3')
			ORDER BY file.".$sort." LIMIT ". $start_here .", 100");
	}
          
	while ($files_row = mysql_fetch_assoc($files_query)) {

	$categories_q = mysql_query("SELECT cat.name as catname , catf.id as catfid  
                                 FROM on_categories AS cat, on_files_categories  AS catf
                                 WHERE catf.category = cat.catid
                                 AND catf.fileid = '".$files_row['fileid']."'
                                 ORDER BY cat.catorder, cat.name");

$categories2 = '';
while ($categories_row = mysql_fetch_assoc($categories_q)) {
		$categories2 .= $categories_row['catname']."<br>";       
        }

		$files[] = array (
			'id'			=>	$files_row['fileid'],
			'title'			=>	$files_row['title'],
			'category'		=>	$categories2,
			//'category'		=>	$files_row['category_title'],
			'file_type'		=>	$files_row['filetype'],
			'date_added'	=>	mod_date($files_row['dateadded']),
			'status'		=>	$file_status_value[$files_row['status']],
			'scores'		=>	$files_row['scores']
		);
	}

	$page_title = 'Каталог файлов';

	// Load template
	//template_files();

	//global $settings, $lang, $files, $nav, $search_term, $sort;

	// Include header
	template_header();
	
	echo '
  <div class="admin_left">';
	template_content_menu();
	echo '
  </div>
  <div class="admin_right">
    <div class="content_box_2">
      <div class="content_box_2_header">
        Каталог игр
      </div>
      <div class="box_text">
        <form name="form" action="" method="post">
        <div style="float: left; width: 20%; text-align: center;"><b>Название</b></div>
        <div style="float: left; width: 15%; text-align: center;"><b>Категория</b></div> 
        <div style="float: left; width: 5%; text-align: center;"><b>Тип</b></div>
        <div style="float: left; width: 10%; text-align: center;"><b>Дата</b></div>
        <div style="float: left; width: 10%; text-align: center;"><b>Статус</b></div>
        <div style="float: left; width: 35%; text-align: center;">&nbsp;</div>
        <div style="float: left; width: 5%; text-align: center;">
        <input type="checkbox" name="all" onclick="check_all();" /></div>
        <div class="line"></div>';
	foreach ($files as $f) {
		echo '<div class="asssdr1';
        if (strstr($f['status'],'Active')) echo ' bg-warning';
        if (strstr($f['status'],'Inactive')) echo ' bg-danger';        
        echo '">
        <div style="float: left; width: 20%; text-align: left;">
        <a href="'. fileurl($f['id'], $f['title']) .'" target="_blank">'. $f['title'] .'</a></div>
        <div style="float: left; width: 15%; text-align: center;">'. $f['category'] .'</div>
        <div style="float: left; width: 5%; text-align: center;">'. $f['file_type'] .'</div>
        <div style="float: left; width: 10%; text-align: center;">'. $f['date_added'] .'</div>
        <div style="float: left; width: 10%; margin-top: 10px; text-align: center;">'. $f['status'] .'</div>
        <div style="float: left; width: 35%; text-align: center;">'. ($f['scores'] == 1 ? '
            <a href="content.php?a=edit_scores&f='. $f['id'] .'">'. $lang['scores_title'] .'</a> - ' : '') .
            '<a class="btn btn-mini btn-primary" href="content.php?a=edit_file&f='. $f['id'] .'">Редактировать</a> - 
             <a class="btn btn-mini btn-primary" href="content.php?a=file_comments&f='. $f['id'] .'">Комментарии</a></div>
        <div style="float: left; width: 5%; text-align: center;"><input type="checkbox" name="file_id['. $f['id'] .']" value="ok" /></div>        
        <div style="clear: both;"></div>
        </div>
        <div class="line"></div>';
	}
	        
    echo '
		<div align="right">Отсортировать по:
			  <select name="files_sort">
 				<option value="alph" '.(@$_SESSION["sort_files"] == "alph"?'selected':'') .'>По алфавиту</option>
				<option value="status" '.(@$_SESSION["sort_files"] == "status"?'selected':'') .'>По статусу</option>
                <option value="dateadded" '.(@$_SESSION["sort_files"] == "dateadded"?'selected':'') .'>По дате</option>
<!--                <option value="category" '.(@$_SESSION["sort_files"] == "category"?'selected':'') .'>По категории</option> -->
                <option value="filetype" '.(@$_SESSION["sort_files"] == "filetype"?'selected':'') .'>По расширению</option>                                                
                <option value="rating" '.(@$_SESSION["sort_files"] == "rating"?'selected':'') .'>По рейтингу</option>
                <option value="filelocation" '.(@$_SESSION["sort_files"] == "filelocation"?'selected':'') .'>По расположению файла</option>                                                
			  </select> 
			<input  class="btn btn-success" type="submit" name="submit_filessort" value="Сортировать" />
		</div>
        <br />    
		<div align="right">
			  <select name="files_action">
				<option value="delete">Удалить</option>
				<option value="active">Включить отмеченные</option>
				<option value="inactive">Выключить отмеченные</option>
				<option value="game_slave">'. $lang['mark_game_slave'] .'</option>
			  </select> 
			<input  class="btn btn-success" type="submit" name="submit_files" value="'. $lang['go'] .'" />
		</div>
		<div align="center">
		'. $nav .'
		</div>
        <div align="right">
		  <input type="text" name="t" size="20" value="'. $search_term .'" /> 
          <input type="submit" name="submit_search" value="'. $lang['search'] .'" />
		</div>
        </form>
  	  </div>
  	</div>
  </div>
  <div style="clear: both;"></div>
  ';
	
	// Include footer
	template_footer();
    
    
}

?>