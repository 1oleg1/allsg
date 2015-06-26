<?php
//формируем страницу с формой входа
//используем шаблон layouts/dashboard.php
$this->layout = 'dashboard';
?>
<?php $this->pageTitle=Yii::app()->name . ' - Login'; ?>

<h1>Login</h1>

<div class="yiiForm">
<?php echo CHtml::beginForm(); ?>

<?php echo CHtml::errorSummary($form); ?>

<div class="simple">
<?php echo CHtml::activeLabel($form,'username'); ?>
<?php echo CHtml::activeTextField($form,'username') ?>
</div>

<div class="simple">
<?php echo CHtml::activeLabel($form,'password'); ?>
<?php echo CHtml::activePasswordField($form,'password') ?>
<p class="hint">
Подсказка: Для демо версии используйте <tt>admin/admin</tt>.
</p>
</div>

<div class="action">
<?php echo CHtml::activeCheckBox($form,'rememberMe'); ?>
<?php echo CHtml::activeLabel($form,'rememberMe'); ?>
<br/>
<?php echo CHtml::submitButton('Войти'); ?>
</div>

<?php echo CHtml::endForm(); ?>

</div><!-- yiiForm -->