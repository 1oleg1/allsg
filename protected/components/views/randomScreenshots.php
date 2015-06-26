<h2>Случайные скриншоты</h2>

<?php
// каждый скриншот - это ссылка с картинкой
foreach ($screenshots as $screenshot) {
	$img = CHtml::image($screenshot->s_thumbnail, 'Скриншот '.$screenshot->s_id);
	echo CHtml::link($img, array('screenshots/show', 'id'=>$screenshot->s_id));
}
?>