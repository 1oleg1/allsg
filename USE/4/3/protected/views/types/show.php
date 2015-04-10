<?php //не используется ?>
<h2>View Types <?php echo $model->t_id; ?></h2>

<div class="actionBar">
[<?php echo CHtml::link('Types List',array('list')); ?>]
[<?php echo CHtml::link('New Types',array('create')); ?>]
[<?php echo CHtml::link('Update Types',array('update','id'=>$model->t_id)); ?>]
[<?php echo CHtml::linkButton('Delete Types',array('submit'=>array('delete','id'=>$model->t_id),'confirm'=>'Are you sure?')); ?>
]
[<?php echo CHtml::link('Manage Types',array('admin')); ?>]
</div>

<table class="dataGrid">
<tr>
	<th class="label"><?php echo CHtml::encode($model->getAttributeLabel('t_name')); ?>
</th>
    <td><?php echo CHtml::encode($model->t_name); ?>
</td>
</tr>
</table>
