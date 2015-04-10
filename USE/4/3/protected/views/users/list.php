<?php //не используется ?>
<?php $this->layout = 'dashboard'; ?>
<h2>Users List</h2>

<div class="actionBar">
[<?php echo CHtml::link('New Users',array('create')); ?>]
[<?php echo CHtml::link('Manage Users',array('admin')); ?>]
</div>

<?php $this->widget('CLinkPager',array('pages'=>$pages)); ?>

<?php foreach($models as $n=>$model): ?>
<div class="item">
<?php echo CHtml::encode($model->getAttributeLabel('u_id')); ?>:
<?php echo CHtml::link($model->u_id,array('show','id'=>$model->u_id)); ?>
<br/>
<?php echo CHtml::encode($model->getAttributeLabel('u_name')); ?>:
<?php echo CHtml::encode($model->u_name); ?>
<br/>
<?php echo CHtml::encode($model->getAttributeLabel('u_login')); ?>:
<?php echo CHtml::encode($model->u_login); ?>
<br/>
<?php echo CHtml::encode($model->getAttributeLabel('u_password')); ?>:
<?php echo CHtml::encode($model->u_password); ?>
<br/>
<?php echo CHtml::encode($model->getAttributeLabel('u_xml')); ?>:
<?php echo CHtml::encode($model->u_xml); ?>
<br/>

</div>
<?php endforeach; ?>
<br/>
<?php $this->widget('CLinkPager',array('pages'=>$pages)); ?>