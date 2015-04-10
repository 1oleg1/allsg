<?php //шаблон полного описания игры ?>

<div class="game_full">
<h1><?= $game->g_name ?></h1>
<p><?php
echo CHtml::image($game->g_medium_pic, $game->g_name);
echo $game->g_fulldescr;
?></p>
<?php
$types = array();
foreach ($game->g_types as $type) {
	$types[] = CHtml::link($type->t_name, array('types/show', 'id'=>$type->t_id), array('class'=>"btn btn-default btn-xs"));	
}
?>
<p>Жанры: <?php echo implode(', ', $types); ?></p>
<h2><?php // echo CHtml::link('Скачать', $game->g_download_link, array('class'=>'download_link')); ?></h2>

<div id="screenshots">
<?php
//foreach ($game->ygs_screenshots as $screenshot) {
//	echo CHtml::link(CHtml::image($screenshot->s_thumbnail), $screenshot->s_image);
//}
?>
</div><!-- screenshots -->
<?php $this->widget('ext.swfobject.ESwfObject', array(
          'width'         => $game->width,
          'height'        => $game->height,
          'swfFile'       => Yii::app()->baseUrl . $game->filename,
          'playerVersion' => '9.0.0',
          'params'        => array('menu' => 'false', 'quality' => 'high', 'wmode' => 'transparent'),
          'flashvars'     => array(),
          'attributes'    => array(),
      )); 
?>
</div><!-- game_full -->