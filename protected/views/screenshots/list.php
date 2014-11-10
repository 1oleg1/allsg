<?php //список скриншотов ?>
<h2>Скриншоты</h2>

<?php $this->widget('CLinkPager',array('pages'=>$pages)); ?>

<?php foreach($models as $n=>$model): ?>
<div class="screenshot grid_4 alpha omega">
<?php
$img = CHtml::image($model->s_thumbnail, $model->s_game->g_name.' скриншот '.$model->s_id);
echo CHtml::link($img, array('screenshots/show', 'id'=>$model->s_id));
echo CHtml::link($model->s_game->g_name, array('games/show', 'id'=>$model->s_game->g_id));
?>
</div>
<?php endforeach; ?>
<div class="clear"></div>
<br/>
<?php $this->widget('CLinkPager',array('pages'=>$pages)); ?>