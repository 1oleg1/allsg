<?php
//страница "Изменить жанр"
//используется шаблон dashboard
$this->layout = 'dashboard'; ?>
<h2>Изменить жанр: <?php echo $model->t_name; ?></h2>

<div class="actionBar">
[<?php echo CHtml::link('Управление жанрами',array('admin')); ?>]
[<?php echo CHtml::link('Создать жанр',array('create')); ?>]
</div>

<?php echo $this->renderPartial('_form', array(
	'model'=>$model,
	'update'=>true,
)); ?>