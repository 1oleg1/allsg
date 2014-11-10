<?php
//страница "Создание жанра"
//используется шаблон dashboard
$this->layout = 'dashboard';
?>
<h2>New Types</h2>

<div class="actionBar">
[<?php echo CHtml::link('Управление жанрами',array('admin')); ?>]
</div>

<?php echo $this->renderPartial('_form', array(
	'model'=>$model,
	'update'=>false,
)); ?>