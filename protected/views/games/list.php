<?php //страница с общим списком игр ?>
<?php if (null !== $type) : ?>
<h1>Игры: <?php echo $type->t_name; ?></h1>
<?php else: ?>
<h1>Игры</h1>
<?php endif; ?>

<div class="grid_9 pager">
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
<div class="grid_9 pager">
<?php $this->widget('CLinkPager',array('pages'=>$pages)); ?>
</div>