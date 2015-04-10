<?php
//страница "Создать пользователя"
//используется шаблон dashboard

$this->layout = 'dashboard';
?>
<h2>New Users</h2>

<div class="actionBar">
[<?php echo CHtml::link('Users List',array('list')); ?>]
[<?php echo CHtml::link('Manage Users',array('admin')); ?>]
</div>

<?php echo $this->renderPartial('_form', array(
	'model'=>$model,
	'update'=>false,
)); ?>