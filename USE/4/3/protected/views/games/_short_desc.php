<?php //шаблон краткого описания игры ?>

<div class="game_short grid_4">
<?php
echo CHtml::image($game->g_small_pic, $game->g_name);
?>
<h2><?php
echo CHtml::link($game->g_name
	, array('games/show', 'id'=>$game->g_id));
?></h2>
<p><?php echo $game->g_shortdescr; ?></p>
<p class="details">
<?php echo CHtml::link('подробнее', array('games/show', 'id'=>$game->g_id)); ?></p>
<?php
$types = array();
foreach ($game->g_types as $type) {
	$types[] = CHtml::link($type->t_name, array('games/list', 'type_id'=>$type->t_id));	
}
?>
<p>Жанры: <?php echo implode(', ', $types); ?></p>

</div><!-- game_short -->