<?php
//страница "Изменить пользователя"
//используется шаблон dashboard

$this->layout = 'dashboard';
?>
<h2>Изменить данные пользователя: <?php echo $model->u_name; ?></h2>

<div class="actionBar">
[<?php echo CHtml::link('Управление пользователями',array('admin')); ?>]
[<?php echo CHtml::link('Создать пользователя',array('create')); ?>]
</div>

<?php echo $this->renderPartial('_form', array(
	'model'=>$model,
	'update'=>true,
)); ?>