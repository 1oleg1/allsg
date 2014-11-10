<?php //форма создания/изменения жанра ?>
<div class="yiiForm">

<p>
Обязательные поля отмечены <span class="required">*</span>.
</p>

<?php echo CHtml::beginForm(); ?>

<?php echo CHtml::errorSummary($model); ?>

<div class="simple">
<?php echo CHtml::activeLabelEx($model,'t_id'); ?>
<?php echo CHtml::activeTextField($model,'t_id',array('size'=>45,'maxlength'=>45)); ?>
</div>

<div class="simple">
<?php echo CHtml::activeLabelEx($model,'t_name'); ?>
<?php echo CHtml::activeTextField($model,'t_name',array('size'=>45,'maxlength'=>45)); ?>
</div>

<div class="action">
<?php echo CHtml::submitButton($update ? 'Изменить' : 'Создать'); ?>
</div>

<?php echo CHtml::endForm(); ?>

</div><!-- yiiForm -->