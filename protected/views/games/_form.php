<?php //Эта форма используется для обновления данных игры и удаления скриншотов ?>
<div class="yiiForm">

<p>
Обязательные поля отмеченны <span class="required">*</span>.
</p>

<?php echo CHtml::beginForm(); ?>

<?php echo CHtml::errorSummary($model); ?>

<div class="simple">
<?php echo CHtml::activeLabelEx($model,'g_rate'); ?>
<?php echo CHtml::activeTextField($model,'g_rate'); ?>
</div>
<div class="simple">
<?php echo CHtml::activeLabelEx($model,'g_name_url'); ?>
<?php echo CHtml::activeTextField($model,'g_name_url',array('size'=>60,'maxlength'=>255)); ?>
</div>
<?php //убираем g_type, т.к. мы добавляем список чекбоксов для установки жанров ?>
<div class="simple">
<?php echo CHtml::activeLabelEx($model,'g_added'); ?>
<?php echo CHtml::activeTextField($model,'g_added'); ?>
</div>
<div class="simple">
<?php echo CHtml::activeLabelEx($model,'g_size'); ?>
<?php echo CHtml::activeTextField($model,'g_size'); ?>
</div>
<div class="simple">
<?php echo CHtml::activeLabelEx($model,'g_name'); ?>
<?php echo CHtml::activeTextField($model,'g_name',array('size'=>60,'maxlength'=>255)); ?>
</div>
<div class="simple">
<?php echo CHtml::activeLabelEx($model,'g_medium_pic'); ?>
<?php echo CHtml::activeTextField($model,'g_medium_pic',array('size'=>60,'maxlength'=>255)); ?>
</div>
<div class="simple">
<?php echo CHtml::activeLabelEx($model,'g_small_pic'); ?>
<?php echo CHtml::activeTextField($model,'g_small_pic',array('size'=>60,'maxlength'=>255)); ?>
</div>
<div class="simple">
<?php echo CHtml::activeLabelEx($model,'g_download_link'); ?>
<?php echo CHtml::activeTextField($model,'g_download_link',array('size'=>60,'maxlength'=>255)); ?>
</div>
<div class="simple">
<?php echo CHtml::activeLabelEx($model,'g_shortdescr'); ?>
<?php echo CHtml::activeTextArea($model,'g_shortdescr',array('rows'=>6, 'cols'=>70)); ?>
</div>
<div class="simple">
<?php echo CHtml::activeLabelEx($model,'g_fulldescr'); ?>
<?php echo CHtml::activeTextArea($model,'g_fulldescr',array('rows'=>6, 'cols'=>70)); ?>
</div>
<div class="simple">
<?php echo CHtml::activeLabelEx($model,'g_publish_date'); ?>
<?php echo CHtml::activeTextField($model,'g_publish_date'); ?>
</div>
<div class="simple">
<?php echo CHtml::activeLabelEx($model,'g_state'); ?>
<?php echo CHtml::activeDropDownList($model, 'g_state'
		, array('0'=>'Опубликовано','1'=>'Черновик')); ?>
</div>
<div class="types_list">
<?php echo '<strong>Жанры</strong>:<br />'; ?>
<?php
$curTypes = $model->ygs_types;
$curT = array();
foreach ($curTypes as $type) {
	$curT[] = $type->t_id;
}
$allT = array();
foreach ($types as $type) {
	$allT[$type->t_id] = $type->t_name;
}
echo CHtml::checkBoxList('types',$curT,$allT, array('separator'=>''));
?>
</div>
<div class="action">
<?php echo CHtml::submitButton($update ? 'Сохранить' : 'Создать'); ?>
</div>

<div class="action">
<?php echo CHtml::link('Просмотр', array('games/show', 'id'=>$model->g_id), array('target'=>'_blank')); ?>
</div>

<?php echo CHtml::endForm(); ?>

</div><!-- yiiForm -->

<?php //форма удаления скриншотов ?>

<div class="yiiForm">
<h2>Управление скриншотами</h2>
<?php echo CHtml::beginForm('', 'post', array('id'=>'screenshots_form')); ?>
<div class="simple">
<?php
$screenshots = $model->ygs_screenshots;
$gameScreenshots = array();
foreach ($screenshots as $screenshot) {
	$gameScreenshots[$screenshot->s_id] = CHtml::image($screenshot->s_thumbnail, $screenshot->s_game_id);
}
echo CHtml::checkBoxList('screenshots',array(),$gameScreenshots);
?>
</div>
<div class="action">
<?php echo CHtml::submitButton('Удалить отмеченные'); ?>
</div>
<?php echo CHtml::endForm(); ?>
</div><!-- yiiForm -->