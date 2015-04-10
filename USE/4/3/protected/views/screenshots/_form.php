<?php //форма добавления/изменения скриншота, не используется ?>
<div class="yiiForm">

<p>
Fields with <span class="required">*</span> are required.
</p>

<?php echo CHtml::beginForm(); ?>

<?php echo CHtml::errorSummary($model); ?>

<div class="simple">
<?php echo CHtml::activeLabelEx($model,'s_game_id'); ?>
<?php echo CHtml::activeTextField($model,'s_game_id'); ?>
</div>
<div class="simple">
<?php echo CHtml::activeLabelEx($model,'s_image'); ?>
<?php echo CHtml::activeTextField($model,'s_image',array('size'=>60,'maxlength'=>255)); ?>
</div>
<div class="simple">
<?php echo CHtml::activeLabelEx($model,'s_thumbnail'); ?>
<?php echo CHtml::activeTextField($model,'s_thumbnail',array('size'=>60,'maxlength'=>255)); ?>
</div>

<div class="action">
<?php echo CHtml::submitButton($update ? 'Save' : 'Create'); ?>
</div>

<?php echo CHtml::endForm(); ?>

</div><!-- yiiForm -->