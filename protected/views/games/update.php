<?php
//страница "Редактирование игры"
//шаблон dashboard
$this->layout = 'dashboard';
?>
<h2>Редактирование: <?php echo $model->g_name; ?></h2>

<div class="actionBar">
[<?php echo CHtml::link('Просмотр',array('list')); ?>]
[<?php echo CHtml::link('Управление играми',array('admin')); ?>]
</div>

<?php echo $this->renderPartial('_form', array(
	'model'=>$model,
	'update'=>true,
	'types'=>$types
)); ?>