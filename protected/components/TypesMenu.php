<?php
/**
 * Этот виджет создает меню с перечнем жанров игр
 */
class TypesMenu extends CWidget {
	public function run() {
		$types = Types::model()->findAll();		
		$this->render('typesMenu',array('types'=>$types));
	}
}

//end of TypesMenu.php