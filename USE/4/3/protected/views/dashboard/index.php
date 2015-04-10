<?php $this->layout = 'dashboard'; ?>

<div class="grid_3">
<?php echo CHtml::link('Панель управления', array('dashboard/index')); ?>
</div>

<div class="grid_3">
Игры
<ul>
	<li><?php echo CHtml::link('Управление', array('games/admin')); ?></li>
	<li><?php echo CHtml::link('Импорт', array('games/import')); ?></li>
</ul>
</div>

<div class="grid_3">
Жанры
<ul>
	<li><?php echo CHtml::link('Управление', array('types/admin')); ?></li>
	<li><?php echo CHtml::link('Создать', array('types/create')); ?></li>
</ul>
</div>

<div class="grid_3">
<?php echo CHtml::link('Выход', array('dashboard/logout')); ?>
</div>