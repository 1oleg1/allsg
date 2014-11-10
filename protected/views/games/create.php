<?php
//страница с формой создания игры (не используется)
//шаблон dashboard
$this->layout = 'dashboard';
?>
<h2>New Games</h2>

<div class="actionBar">
[<?php echo CHtml::link('Games List',array('list')); ?>]
[<?php echo CHtml::link('Manage Games',array('admin')); ?>]
</div>

<?php echo $this->renderPartial('_form', array(
	'model'=>$model,
	'update'=>false,
)); ?>