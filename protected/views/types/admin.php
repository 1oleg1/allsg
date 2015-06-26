<?php
//страница "Управление жанрами"
//используется шаблон dashboard
$this->layout = 'dashboard';
?>
<div class="grid_8" id="types_admin">
<h2>Управление жанрами</h2>

<div class="actionBar">
[<?php echo CHtml::link('Создать жанр',array('create')); ?>]
</div>

<table class="dataGrid">
  <thead>
  <tr>
    <th><?php echo $sort->link('t_id'); ?></th>
    <th><?php echo $sort->link('t_name'); ?></th>
	<th>Actions</th>
  </tr>
  </thead>
  <tbody>
<?php foreach($models as $n=>$model): ?>
  <tr class="<?php echo $n%2?'even':'odd';?>">
    <td><?php echo CHtml::link($model->t_id,array('show','id'=>$model->t_id)); ?></td>
    <td><?php echo CHtml::encode($model->t_name); ?></td>
    <td>
      <?php echo CHtml::link('Update',array('update','id'=>$model->t_id)); ?>
      <?php echo CHtml::linkButton('Delete',array(
      	  'submit'=>'',
      	  'params'=>array('command'=>'delete','id'=>$model->t_id),
      	  'confirm'=>"Are you sure to delete #{$model->t_id}?")); ?>
	</td>
  </tr>
<?php endforeach; ?>
  </tbody>
</table>
<br/>
<?php $this->widget('CLinkPager',array('pages'=>$pages)); ?>
</div><!-- types_admin -->