<?php
//страница с форомой "Контакты"
$this->pageTitle=Yii::app()->name . ' - Контакты'; ?>

<h1>Обратная связь</h1>

<div id="contact_form">
<?php if(Yii::app()->user->hasFlash('contact')): ?>
<div class="confirmation">
<?php echo Yii::app()->user->getFlash('contact'); ?>
</div>
<?php else: ?>

<p>
Если у Вас есть вопрос, отправьте его с помощью этой формы.
</p>

<div class="yiiForm">

<?php echo CHtml::beginForm(); ?>

<?php echo CHtml::errorSummary($contact); ?>

<div class="simple">
<?php echo CHtml::activeLabel($contact,'name'); ?>
<?php echo CHtml::activeTextField($contact,'name'); ?>
</div>
<div class="simple">
<?php echo CHtml::activeLabel($contact,'email'); ?>
<?php echo CHtml::activeTextField($contact,'email'); ?>
</div>
<div class="simple">
<?php echo CHtml::activeLabel($contact,'subject'); ?>
<?php echo CHtml::activeTextField($contact,'subject',array('size'=>60,'maxlength'=>128)); ?>
</div>
<div class="simple">
<?php echo CHtml::activeLabel($contact,'body'); ?>
<?php echo CHtml::activeTextArea($contact,'body',array('rows'=>6, 'cols'=>50)); ?>
</div>

<?php if(extension_loaded('gd')): ?>
<div class="simple">
	<?php echo CHtml::activeLabel($contact,'verifyCode'); ?>
	<div>
	<?php $this->widget('CCaptcha'); ?>
	<?php echo CHtml::activeTextField($contact,'verifyCode'); ?>
	</div>
	<p class="hint">Пожалуйства, перепишите буквы с картинки, регистр роли не играет.</p>
</div>
<?php endif; ?>

<div class="action">
<?php echo CHtml::submitButton('Отправить'); ?>
</div>

<?php echo CHtml::endForm(); ?>

</div><!-- yiiForm -->
<?php endif; ?>

</div><!-- contact_form -->