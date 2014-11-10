<?php
//страница "Управление пользователями"
//используется шаблон dashboard
$this->layout = 'dashboard'; ?>
<div class="grid_8" id="users_admin">
<h2>Управление пользователями</h2>

<div class="actionBar">
[<?php echo CHtml::link('Создать пользователя',array('create')); ?>]
</div>

<table class="dataGrid">
  <thead>
  <tr>
    <th><?php echo $sort->link('u_id'); ?></th>
    <th><?php echo $sort->link('u_name'); ?></th>
    <th><?php echo $sort->link('u_login'); ?></th>
	<th>Actions</th>
  </tr>
  </thead>
  <tbody>
<?php foreach($models as $n=>$model): ?>
  <tr class="<?php echo $n%2?'even':'odd';?>">
    <td><?php echo CHtml::link($model->u_id,array('show','id'=>$model->u_id)); ?></td>
    <td><?php echo CHtml::encode($model->u_name); ?></td>
    <td><?php echo CHtml::encode($model->u_login); ?></td>
    <td>
      <?php echo CHtml::link('Update',array('update','id'=>$model->u_id)); ?>
      <?php echo CHtml::linkButton('Delete',array(
      	  'submit'=>'',
      	  'params'=>array('command'=>'delete','id'=>$model->u_id),
      	  'confirm'=>"Are you sure to delete #{$model->u_id}?")); ?>
	</td>
  </tr>
<?php endforeach; ?>
  </tbody>
</table>
<br/>
<?php $this->widget('CLinkPager',array('pages'=>$pages)); ?>
</div><!-- users_admin -->