<?php //шаблон полного описания игры ?>

<div class="game_full">
<h1><?php echo CHtml::link($game->g_name, $game->g_download_link); ?></h1>
<p><?php
echo CHtml::image($game->g_medium_pic, $game->g_name);
echo $game->g_fulldescr;
?></p>
<?php
$types = array();
foreach ($game->g_types as $type) {
	$types[] = CHtml::link($type->t_name, array('types/show', 'id'=>$type->t_id));	
}
?>
<p>Жанры: <?php echo implode(', ', $types); ?></p>
<h2><?php echo CHtml::link('Скачать', $game->g_download_link, array('class'=>'download_link')); ?></h2>

<h2>Скриншоты</h2>
<div id="screenshots">
<?php
foreach ($game->ygs_screenshots as $screenshot) {
	echo CHtml::link(CHtml::image($screenshot->s_thumbnail), $screenshot->s_image);
}
?>
</div><!-- screenshots -->
</div><!-- game_full -->