<h2>Архив</h2>
<ul>
<?php
foreach ($items as $item) {
	//формируем якорь ссылки
	$title = $monthNames[$item['month']].' '.$item['year'];
	//формируем и показываем саму ссылку
	echo '<li>'.CHtml::link($title, array('/games/archive','year'=>$item['year'],'month'=>$item['month'])).'</li>';
}
?>
</ul>