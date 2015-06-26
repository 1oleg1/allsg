<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="language" content="en" />
<!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
<!--[if lt IE 9]>
  <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
<title><?php echo CHtml::encode($this->pageTitle); ?> | <?php echo Yii::app()->name; ?></title>
<?if (!empty($this->description)) {?>
<meta name="description" content="<?= CHtml::encode($this->description); ?>" />
<? } ?>
 
<?php echo CHtml::cssFile(Yii::app()->baseUrl.'/css/reset.css'); ?>
<?php echo CHtml::cssFile(Yii::app()->baseUrl.'/css/text.css'); ?>
<?php echo CHtml::cssFile(Yii::app()->baseUrl.'/css/960.css'); ?>
<?php echo CHtml::cssFile(Yii::app()->baseUrl.'/css/main.css'); ?>
<link href='http://fonts.googleapis.com/css?family=Jura&subset=cyrillic,latin' rel='stylesheet' type='text/css'></link>
<?
$scriptPosition = CClientScript::POS_HEAD;
$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerCssFile('/css/bootstrap.css');
$cs->registerCssFile('/css/bootstrap-yii.css');
$cs->registerCssFile('/css/bootstrap-theme.css');
$cs->registerCssFile('/css/bootstrap-responsive.css');
$cs->registerCssFile('/css/custom.css');
$cs->registerScriptFile('/js/bootstrap.min.js', $scriptPosition);
?>
<?php //echo CHtml::cssFile(Yii::app()->baseUrl.'/css/bootstrap-editable.css'); ?>
<?php //echo CHtml::cssFile(Yii::app()->baseUrl.'/css/tstranslation.css'); ?>
</head>

<body>
<? // echo Yii::app()->request->userHostAddress; ?>

<div id="page" class="container_12">

<div id="header" class="grid_12">
<div id="logo">
<?php 
$img = CHtml::image(Yii::app()->baseUrl.'/images/header.png', Yii::app()->name);
//echo CHtml::link($img, Yii::app()->homeUrl);
?></div>
</div><!-- header -->

<div id="main_top" class="grid_12"></div>

<div id="mainmenu" class="grid_12">
<?php $this->widget('application.components.TopMenu'); ?>

</div><!-- mainmenu -->

<div id="content_container" class="grid_12">


<div id="sidebar" class="grid_3">
<div class="menu typemenu">
<?php $this->widget('application.components.TypesMenu'); ?>
</div><!-- menu -->
<? /*
<div class="menu">
<?php $this->widget('application.components.RandomScreenshots'
	, array('count'=>2)); ?>
</div><!-- menu -->
<div class="menu">
<?php $this->widget('application.components.ArchiveMenu'); ?>
</div><!-- menu -->
*/ ?>
</div><!-- sidebar -->




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



<div class="clear"></div>
</div><!-- content_container -->

<div id="footer" class="grid_12">
<div>
Copyright &copy; <?= date("Y") ?> by <a href="http://allsupergames.net">allsupergames.net</a> | <a href="site/contact">Контакты</a><br/>
<?php
if (!Yii::app()->user->isGuest) {
//показываем суммарные данные по использованию ресурсов
    $memory = round(Yii::getLogger()->memoryUsage/1024/1024, 3);
    $time = round(Yii::getLogger()->executionTime, 3);
    echo '<br />Использовано памяти: '.$memory.' МБ<br />';
    echo 'Время выполнения: '.$time.' с';
}
?>
<br />
</div>
</div><!-- footer -->

<div id="main_bottom" class="grid_12"></div>

</div><!-- page -->
</body>

</html>