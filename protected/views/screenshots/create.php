<?php
//создание скриншота, не используется
?>
<h2>New Screenshots</h2>

<div class="actionBar">
[<?php echo CHtml::link('Screenshots List',array('list')); ?>]
[<?php echo CHtml::link('Manage Screenshots',array('admin')); ?>]
</div>

<?php echo $this->renderPartial('_form', array(
	'model'=>$model,
	'update'=>false,
)); ?>