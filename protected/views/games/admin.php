<?php
//страница "Управление играми"
//используется шаблон dashboard
$this->layout = 'dashboard';
?>
<div class="grid_8" id="games_admin">
<h2>Игры &gt;&gt; Управление</h2>

<div class="actionBar">
[<?php echo CHtml::link('Управление играми',array('list')); ?>]
</div>

<table class="dataGrid">
  <thead>
  <tr>
    <th><?php echo $sort->link('g_id'); ?></th>
    <th><?php echo $sort->link('g_name'); ?></th>
    <th><?php echo $sort->link('g_rate'); ?></th>
    <th><?php echo $sort->link('g_small_pic'); ?></th>
    <th><?php echo $sort->link('g_state'); ?></th>
	<th>Действия</th>
  </tr>
  </thead>
  <tbody>
<?php foreach($models as $n=>$model): ?>
  <tr class="<?php echo $n%2?'even':'odd';?>">
    <td><?php echo CHtml::link($model->g_id,array('show','id'=>$model->g_id)); ?></td>
    <td><?php echo CHtml::encode($model->g_name); ?></td>
    <td><?php echo CHtml::encode($model->g_rate); ?></td>
    <td><?php echo CHtml::image($model->g_small_pic, $model->g_name); ?></td>
    <?php
    if (0 === $model->g_state) {
    	$state = 'Опубликовано';
    } else {
    	$state = 'Черновик';
    }
    ?>
    <td><?php echo $state; ?></td>
    <td>
      <?php echo CHtml::link('Update',array('update','id'=>$model->g_id)); ?>
      <?php echo CHtml::linkButton('Delete',array(
      	  'submit'=>'',
      	  'params'=>array('command'=>'delete','id'=>$model->g_id),
      	  'confirm'=>"Are you sure to delete #{$model->g_id}?")); ?>
	</td>
  </tr>
<?php endforeach; ?>
  </tbody>
</table>
<?php $this->widget('CLinkPager',array('pages'=>$pages)); ?>
</div><!-- games_admin -->