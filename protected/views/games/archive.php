<?php //страница с архивом игр за выбранный метод ?>
<h1>Архив: <?php echo $date; ?></h1>

<div class="grid_8">
<?php $this->widget('CLinkPager',array('pages'=>$pages)); ?>
</div>

<?php
$i = 0;
foreach($models as $n=>$model) {
	$this->renderPartial('_short_desc', array('game'=>$model));
	if ($i % 2 !== 0) {
		echo '<div class="clear"></div>';
	}
	$i++;
}
?>
<div class="grid_8">
<?php $this->widget('CLinkPager',array('pages'=>$pages)); ?>
</div>