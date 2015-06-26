<?php
//страница "Управление играми"
//используется шаблон dashboard
$this->layout = 'dashboard';

//$this->breadcrumbs=array(
//	'Language',
//);
?>
<?php // echo $this->id . '/' . $this->action->id; ?>
<h1>Настройка перевода</h1>

<div class="clear"></div>

<? 
$this->widget('tstranslation.widgets.TsTranslationWidget', array(
        'includeBootstrap' => false, // if bootstrap.js loaded
        'showTooltips' => true, // if you want disable bootstrap tooltips
    ));
   
?>
<div class="clear"></div>
