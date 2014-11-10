<?php //не используется ?>
<?php $this->layout = 'dashboard'; ?>
<h2>View Users <?php echo $model->u_id; ?></h2>

<div class="actionBar">
[<?php echo CHtml::link('Users List',array('list')); ?>]
[<?php echo CHtml::link('New Users',array('create')); ?>]
[<?php echo CHtml::link('Update Users',array('update','id'=>$model->u_id)); ?>]
[<?php echo CHtml::linkButton('Delete Users',array('submit'=>array('delete','id'=>$model->u_id),'confirm'=>'Are you sure?')); ?>
]
[<?php echo CHtml::link('Manage Users',array('admin')); ?>]
</div>

<table class="dataGrid">
<tr>
	<th class="label"><?php echo CHtml::encode($model->getAttributeLabel('u_name')); ?>
</th>
    <td><?php echo CHtml::encode($model->u_name); ?>
</td>
</tr>
<tr>
	<th class="label"><?php echo CHtml::encode($model->getAttributeLabel('u_login')); ?>
</th>
    <td><?php echo CHtml::encode($model->u_login); ?>
</td>
</tr>
<tr>
	<th class="label"><?php echo CHtml::encode($model->getAttributeLabel('u_xml')); ?>
</th>
    <td><?php echo CHtml::encode($model->u_xml); ?>
</td>
</tr>
</table>
