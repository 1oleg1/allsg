<h1><?php echo $title; ?></h1>

<?php
$i = 0;
foreach($games as $n=>$model) {
	//шаблон для вывода игры находится в views/games/_short_desc.php
	$this->controller->renderPartial('_short_desc', array('game'=>$model));
	if ($i % 2 !== 0) {
		echo '<div class="clear"></div>';
	}
	$i++;
}
?>