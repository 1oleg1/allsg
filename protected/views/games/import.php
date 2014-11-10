<?php
//страница "Импорт игр"
//шаблон dashboard
$this->layout = 'dashboard';
?>
<div class="importForm">

<div>XML: <?php echo $xml; ?></div>

<?php echo CHtml::beginForm(); ?>

<?php echo CHtml::hiddenField('import',''); ?>

<div class="action">
<?php echo CHtml::submitButton('Импортировать'); ?>
</div>

<?php echo CHtml::endForm(); ?>

<div>
<?php echo $results; ?>
</div>

<div>
<?php 
foreach ($errors as $error) {
	echo '<p>'.$error->message.'</p>';
}
?>
</div>

</div><!-- yiiForm -->