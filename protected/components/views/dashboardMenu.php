<?php //тут просто список со ссылками на соответствующие операции ?>
<ul>
	<li><?php echo CHtml::link('Панель управления', array('dashboard/index')); ?></li>
	<li>Игры</li>
	<li>
		<ul>
			<li><?php echo CHtml::link('Управление', array('games/admin')); ?></li>
			<li><?php echo CHtml::link('Импорт', array('games/import')); ?></li>
		</ul>
	</li>
	<li>Пользователи</li>
	<li>
		<ul>
			<li><?php echo CHtml::link('Управление', array('users/admin')); ?></li>
			<li><?php echo CHtml::link('Создать', array('users/create')); ?></li>
		</ul>
	</li>
	<li>Жанры</li>
	<li>
		<ul>
			<li><?php echo CHtml::link('Управление', array('types/admin')); ?></li>
			<li><?php echo CHtml::link('Создать', array('types/create')); ?></li>
		</ul>
	</li>
	<li><?php echo CHtml::link('Выход', array('dashboard/logout')); ?></li>
</ul>