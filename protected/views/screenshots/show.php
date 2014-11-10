<?php //страница выбранного скриншота ?>
<h2>Скриншот из игры <?php echo $model->s_game->g_name; ?></h2>

<div class="full_screenshot">
<?php
$img = CHtml::image($model->s_image, $model->s_game->g_name, array('width'=>640, 'height'=>480));
echo CHtml::link($img, array('games/show', 'id'=>$model->s_game->g_id));
?>
</div>