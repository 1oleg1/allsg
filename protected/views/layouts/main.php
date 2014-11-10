<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="language" content="en" />
<?php echo CHtml::cssFile(Yii::app()->baseUrl.'/css/reset.css'); ?>
<?php echo CHtml::cssFile(Yii::app()->baseUrl.'/css/text.css'); ?>
<?php echo CHtml::cssFile(Yii::app()->baseUrl.'/css/960.css'); ?>
<?php echo CHtml::cssFile(Yii::app()->baseUrl.'/css/main.css'); ?>
<title><?php echo $this->pageTitle; ?></title>
</head>

<body>
<div id="page" class="container_12">

<div id="header" class="grid_12">
<div id="logo">
<?php 
$img = CHtml::image(Yii::app()->baseUrl.'/images/header.png', Yii::app()->name);
echo CHtml::link($img, Yii::app()->homeUrl);
?></div>
</div><!-- header -->

<div id="main_top" class="grid_12"></div>

<div id="mainmenu" class="grid_12">
<?php $this->widget('application.components.TopMenu'); ?>
</div><!-- mainmenu -->

<div id="content_container" class="grid_12">

<div id="main_block" class="grid_9 alpha omega">

<div id="top_games" class="grid_9">
<?php $this->widget('application.components.TopGames'
	, array('title'=>'Популярные игры'
		,'showOn'=>array('games/list','types/show')
		,'count'=>4)); ?>
</div><!-- top_games -->

<div id="content" class="grid_9">
<?php echo $content; ?>
</div><!-- content -->

</div><!-- main_block -->

<div id="sidebar" class="grid_3">
<div class="menu">
<?php $this->widget('application.components.TypesMenu'); ?>
</div><!-- menu -->

<div class="menu">
<?php $this->widget('application.components.RandomScreenshots'
	, array('count'=>2)); ?>
</div><!-- menu -->

<div class="menu">
<?php $this->widget('application.components.ArchiveMenu'); ?>
</div><!-- menu -->

</div><!-- sidebar -->

<div class="clear"></div>
</div><!-- content_container -->

<div id="footer" class="grid_12">
<div>
Copyright &copy; 2010 by <a href="http://www.simplecoding.org">Стаценко Владимир</a>.<br/>
All Rights Reserved.<br/>
<?php echo Yii::powered(); ?>
<?php
//показываем суммарные данные по использованию ресурсов
$memory = round(Yii::getLogger()->memoryUsage/1024/1024, 3);
$time = round(Yii::getLogger()->executionTime, 3);
echo '<br />Использовано памяти: '.$memory.' МБ<br />';
echo 'Время выполнения: '.$time.' с'
?>
</div>
</div><!-- footer -->

<div id="main_bottom" class="grid_12"></div>

</div><!-- page -->
</body>

</html>