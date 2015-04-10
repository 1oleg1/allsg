<?php //Эта форма используется для обновления данных игры и удаления скриншотов ?>
<?php $form=$this->beginWidget('CActiveForm', array(
    'id'=>'file-form',
    'enableAjaxValidation'=>false,
    'htmlOptions'=>array('enctype'=>'multipart/form-data'),
)); ?>
<div class="yiiForm">
<p>
Обязательные поля отмеченны <span class="required">*</span>.
</p>
<p>Чтобы нормально работал перевод, в переводимом тексте все предложения не должны быть больше 400 
    символов, или от точки до точки не должно быть больше 400 символов.</p>

<?php echo CHtml::beginForm(); ?>

<?php echo CHtml::errorSummary($model); ?>

<div class="simple">
<?php echo CHtml::activeLabelEx($model,'g_name'); ?>
<?= $this->_name_rus ?>
<?php echo CHtml::activeTextField($model,'g_name',array('size'=>60,'maxlength'=>255)); ?>
</div>
<div class="simple">
<?php echo CHtml::activeLabelEx($model,'g_name_en'); ?>
<?= $this->_name_eng ?>    
<?php echo CHtml::activeTextField($model,'g_name_en',array('size'=>60,'maxlength'=>255)); ?>
</div>

<div class="simple">
<label class="required" for="Games_g_name_en">Ширина и Высота</label>
<?php echo CHtml::activeTextField($model,'width',array('size'=>5,'maxlength'=>255)); ?>X
<?php echo CHtml::activeTextField($model,'height',array('size'=>5,'maxlength'=>255)); ?>
</div>
<div class="simple">
<?php echo CHtml::activeLabelEx($model,'ext'); ?>
<?php //echo '<span style="font-weight:bold;line-height: 30px;">Расширение: </span>' ?>
<?php echo CHtml::activeDropDownList($model, 'ext'
		,array('0'=>'SWF')); ?>
</div>
<div class="simple filesmanager">
<?php echo CHtml::activeLabelEx($model,'filename'); ?>
<?php echo CHtml::activeFileField($model,'filename2',array('size'=>160,'maxlength'=>255,'class'=>'btn btn-primary')); ?>
<span style="color:#FF3333"><? echo $model->filename; ?></span>
<div class="clearfix"></div>    
</div>
<div class="simple imgmanager">
		<?php echo $form->labelEx($model,'icon'); ?>
		<?php // Вывод уже загруженной картинки или изображения No_photo
		echo $this->material_image($model->g_id, $model->g_name, $model->g_medium_pic, '150','small_img');?>
		
		<?php //Если картинка загружена, предложить её удалить, отметив чекбокс
		if(isset($model->g_medium_pic) && file_exists($_SERVER['DOCUMENT_ROOT'].$model->g_medium_pic))
		{ 
                    	echo $form->labelEx($model,'del_img',array('class'=>'span-2'));
			echo $form->checkBox($model,'del_img',array('class'=>'span-1'));                        
		}
		?> 
		<br /><br>
		<?php //Поле загрузки файлаclass="btn btn-success"
		echo CHtml::activeFileField($model, 'icon',array('class'=>'btn btn-success')); ?>
                <span style="color:#FF3333"><? echo $model->g_medium_pic; ?></span>
                <div class="clearfix"></div>
	</div>
<div class="simple">
<?php echo CHtml::activeLabelEx($model,'g_shortdescr'); ?>
<?= $this->_shortdescr_rus ?>    
<?php echo CHtml::activeTextArea($model,'g_shortdescr',array('rows'=>2, 'cols'=>80)); ?>
</div>
<div class="simple">
<?php echo CHtml::activeLabelEx($model,'g_shortdescr_en'); ?>
<?= $this->_shortdescr_eng ?>    
<?php echo CHtml::activeTextArea($model,'g_shortdescr_en',array('rows'=>2, 'cols'=>80)); ?>
</div>
<div class="simple">
<?    
//echo $model->g_fulldescr;
//$model->g_fulldescr = "sss"; ?>
<?php echo CHtml::activeLabelEx($model,'g_fulldescr'); ?>
<?= $this->_fulldescr_rus ?>    
<?php echo CHtml::activeTextArea($model,'g_fulldescr',array('class'=>'editme','rows'=>10, 'cols'=>80)); ?>
</div>
<div class="simple">
<?php echo CHtml::activeLabelEx($model,'g_fulldescr_en'); ?>
<?= $this->_fulldescr_eng ?>    
<?php echo CHtml::activeTextArea($model,'g_fulldescr_en',array('class'=>'editme','rows'=>10, 'cols'=>80)); ?>
</div>
<div class="simple">
<?php echo CHtml::activeLabelEx($model,'g_publish_date'); ?>
<?php //echo CHtml::activeTextField($model,'g_publish_date'); ?>
<?
if (empty($model->g_publish_date)) 
    $model->g_publish_date = date("Y-m-d");

$this->widget('zii.widgets.jui.CJuiDatePicker', array(
    'model' => $model,
    'attribute' => 'g_publish_date',
    'language' => 'ru',
    'options' => array(
            'showAnim' => 'fold',
            'dateFormat'=>'yy-mm-dd',
        ),
    'htmlOptions' => array(
        'size' => '10',         // textField size
        'maxlength' => '10',    // textField maxlength
    ),
));
?>        
  
    
</div>
<div class="simple">
<?php echo CHtml::activeLabelEx($model,'g_size'); ?>
<?php echo CHtml::activeTextField($model,'g_size'); ?>
</div>
<div class="simple">
<?php echo CHtml::activeLabelEx($model,'g_rate'); ?>
<?php echo CHtml::activeTextField($model,'g_rate'); ?>
</div>
<div class="simple">
<?php echo CHtml::activeLabelEx($model,'g_state'); ?>
<?php echo CHtml::activeDropDownList($model, 'g_state'
		, array('0'=>'Опубликовано','1'=>'Черновик')); ?>
</div>
<?php //убираем g_type, т.к. мы добавляем список чекбоксов для установки жанров ?>
<div class="types_list">
<?php echo '<strong>Жанры</strong>:<br />'; ?>
<?php
$curTypes = $model->ygs_types;
$curT = array();
foreach ($curTypes as $type) {
	$curT[] = $type->t_id;
}
$allT = array();
//получаем список жанров
$types = Types::model()->findAll();
foreach ($types as $type) {
	$allT[$type->t_id] = $type->t_name;
}
echo CHtml::checkBoxList('types',$curT,$allT, array('separator'=>''));
?>
</div>
<div class="action">
<?php echo CHtml::submitButton($update ? 'Сохранить' : 'Создать',array('class'=>'btn-lg btn-success')); ?>
</div>
<? if($model->g_id) {?>
<div class="action">
<?php echo CHtml::link('Перейти на страницу игры', array('games/show', 'id'=>$model->g_id), array('target'=>'_blank')); ?>
</div>
<? } ?>
<?php echo CHtml::endForm(); ?>

</div><!-- yiiForm -->

<?php //форма удаления скриншотов ?>
<?/**
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
*/?>
<?php $this->endWidget(); ?>