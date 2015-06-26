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

<div class="simple">
<?php echo CHtml::activeLabelEx($model,'t_name_en'); ?>
<?php echo CHtml::activeTextField($model,'t_name_en',array('size'=>45,'maxlength'=>45)); ?>
</div>

<div class="simple">
<?php echo CHtml::activeLabelEx($model,'description'); ?>
<?php echo CHtml::activeTextArea($model,'description',array('rows'=>2, 'cols'=>80)); ?>    
</div>

<div class="simple">
<?php echo CHtml::activeLabelEx($model,'description_en'); ?>
<?php echo CHtml::activeTextArea($model,'description_en',array('rows'=>2, 'cols'=>80)); ?>
</div>

<div class="simple">
<?php echo CHtml::activeLabelEx($model,'description_site'); ?>
<?php echo CHtml::activeTextArea($model,'description_site',array('rows'=>4, 'cols'=>80)); ?>    
</div>

<div class="simple">
<?php echo CHtml::activeLabelEx($model,'description_en_site'); ?>
<?php echo CHtml::activeTextArea($model,'description_en_site',array('rows'=>4, 'cols'=>80)); ?>    
</div>

<div class="action">
<?php echo CHtml::submitButton($update ? 'Изменить' : 'Создать'); ?>
</div>

<?php echo CHtml::endForm(); ?>

</div><!-- yiiForm -->