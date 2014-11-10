<ul>
<?php
foreach ($items as $item) {
	echo '<li>'.CHtml::link($item['title'], $item['link']).'</li>';
}
?>
</ul>