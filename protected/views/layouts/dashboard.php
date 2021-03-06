<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="language" content="en" />
<?php echo CHtml::cssFile(Yii::app()->baseUrl.'/css/reset.css'); ?>
<?php echo CHtml::cssFile(Yii::app()->baseUrl.'/css/text.css'); ?>
<?php echo CHtml::cssFile(Yii::app()->baseUrl.'/css/960.css'); ?>
<?php echo CHtml::cssFile(Yii::app()->baseUrl.'/css/dashboard.css'); ?>

<?
$scriptPosition = CClientScript::POS_HEAD;
$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerCssFile('/css/bootstrap.css');
$cs->registerCssFile('/css/bootstrap-yii.css');
$cs->registerScriptFile('/js/bootstrap.min.js', $scriptPosition);
?>
<?php echo CHtml::cssFile(Yii::app()->baseUrl.'/css/bootstrap-theme.css'); ?>
<?php echo CHtml::cssFile(Yii::app()->baseUrl.'/css/bootstrap-responsive.css'); ?>
<?php //echo CHtml::cssFile(Yii::app()->baseUrl.'/css/bootstrap-editable.css'); ?>
<?php //echo CHtml::cssFile(Yii::app()->baseUrl.'/css/tstranslation.css'); ?>
<?php echo CHtml::cssFile(Yii::app()->baseUrl.'/css/custom.css'); ?>


<title><?php echo $this->pageTitle; ?></title>
</head>

<body>
<div id="page" class="container_12">

<div id="header" class="grid_12">
<div id="logo">Панель управления</div>
</div><!-- header -->

<div id="sidebar" class="grid_4">
<?php $this->widget('application.components.DashboardMenu'); ?>
</div><!-- sidebar -->

<div class="grid_8">
<div id="content">
<?php echo $content; ?>
<div class="clear"></div>
</div><!-- content -->
</div>



<div id="footer" class="grid_12">

<?php
//показываем суммарные данные по использованию ресурсов
$memory = round(Yii::getLogger()->memoryUsage/1024/1024, 3);
$time = round(Yii::getLogger()->executionTime, 3);
echo '<br />Использовано памяти: '.$memory.' МБ<br />';
echo 'Время выполнения: '.$time.' с'
?>
</div><!-- footer -->

<div class="clear"></div>

</div><!-- page -->
</body>

</html>