<?php
//управление скриншотами, не используется
?>
<h2>Managing Screenshots</h2>

<div class="actionBar">
[<?php echo CHtml::link('Screenshots List',array('list')); ?>]
[<?php echo CHtml::link('New Screenshots',array('create')); ?>]
</div>

<table class="dataGrid">
  <thead>
  <tr>
    <th><?php echo $sort->link('s_id'); ?></th>
    <th><?php echo $sort->link('s_game_id'); ?></th>
    <th><?php echo $sort->link('s_image'); ?></th>
    <th><?php echo $sort->link('s_thumbnail'); ?></th>
	<th>Actions</th>
  </tr>
  </thead>
  <tbody>
<?php foreach($models as $n=>$model): ?>
  <tr class="<?php echo $n%2?'even':'odd';?>">
    <td><?php echo CHtml::link($model->s_id,array('show','id'=>$model->s_id)); ?></td>
    <td><?php echo CHtml::encode($model->s_game_id); ?></td>
    <td><?php echo CHtml::encode($model->s_image); ?></td>
    <td><?php echo CHtml::encode($model->s_thumbnail); ?></td>
    <td>
      <?php echo CHtml::link('Update',array('update','id'=>$model->s_id)); ?>
      <?php echo CHtml::linkButton('Delete',array(
      	  'submit'=>'',
      	  'params'=>array('command'=>'delete','id'=>$model->s_id),
      	  'confirm'=>"Are you sure to delete #{$model->s_id}?")); ?>
	</td>
  </tr>
<?php endforeach; ?>
  </tbody>
</table>
<br/>
<?php $this->widget('CLinkPager',array('pages'=>$pages)); ?>