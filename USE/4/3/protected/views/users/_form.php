<?php //форма для создания и изменения данных пользотелей ?>
<div class="yiiForm">

<p>
Обязательные поля отмечены <span class="required">*</span>.
</p>

<?php echo CHtml::beginForm(); ?>

<?php echo CHtml::errorSummary($model); ?>

<div class="simple">
<?php echo CHtml::activeLabelEx($model,'u_name'); ?>
<?php echo CHtml::activeTextField($model,'u_name',array('size'=>50,'maxlength'=>50)); ?>
</div>
<div class="simple">
<?php echo CHtml::activeLabelEx($model,'u_login'); ?>
<?php echo CHtml::activeTextField($model,'u_login',array('size'=>50,'maxlength'=>50)); ?>
</div>
<div class="simple">
<?php echo CHtml::activeLabelEx($model,'u_password'); ?>
<?php echo CHtml::passwordField('Users[u_password]','',array('size'=>50,'maxlength'=>50)); ?>
</div>
<div class="simple">
<?php echo CHtml::activeLabelEx($model,'u_pass_conf'); ?>
<?php echo CHtml::passwordField('Users[u_pass_conf]','',array('size'=>50,'maxlength'=>50)); ?>
</div>
<div class="simple">
<?php echo CHtml::activeLabelEx($model,'u_xml'); ?>
<?php echo CHtml::activeTextField($model,'u_xml',array('size'=>60,'maxlength'=>255)); ?>
</div>

<div class="action">
<?php echo CHtml::submitButton($update ? 'Save' : 'Create'); ?>
</div>

<?php echo CHtml::endForm(); ?>

</div><!-- yiiForm -->