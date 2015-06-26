<?php
//не используется
$this->layout = 'dashboard';
?>
<h2>Types List</h2>

<div class="actionBar">
[<?php echo CHtml::link('New Types',array('create')); ?>]
[<?php echo CHtml::link('Manage Types',array('admin')); ?>]
</div>

<?php $this->widget('CLinkPager',array('pages'=>$pages)); ?>

<?php //foreach($models as $n=>$model): ?>
<div class="item">
<?php //echo CHtml::encode($model->getAttributeLabel('t_id')); ?>:
<?php //echo CHtml::link($model->t_id,array('show','id'=>$model->t_id)); ?>
<br/>
<?php //echo CHtml::encode($model->getAttributeLabel('t_name')); ?>:
<?php //echo CHtml::encode($model->t_name); ?>
<br/>

</div>
<?php //endforeach; ?>
<br/>
<?php $this->widget('CLinkPager',array('pages'=>$pages)); ?>