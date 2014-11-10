<?php
//Изменение скриншота, не используется
?>
<h2>Update Screenshots <?php echo $model->s_id; ?></h2>

<div class="actionBar">
[<?php echo CHtml::link('Screenshots List',array('list')); ?>]
[<?php echo CHtml::link('New Screenshots',array('create')); ?>]
[<?php echo CHtml::link('Manage Screenshots',array('admin')); ?>]
</div>

<?php echo $this->renderPartial('_form', array(
	'model'=>$model,
	'update'=>true,
)); ?>