<?php
/**
 * Этот виджет создает меню с перечнем жанров игр
<<<<<<< HEAD
 */
class TypesMenu extends CWidget {
	public function run() {
		$types = Types::model()->findAll();		
=======
 * 
 * @author Vladimir Statsenko
 */
class TypesMenu extends CWidget {
	public function run() {
		$types = Types::model()->findAll();
		
>>>>>>> 1aaf85629a561129683dcedc153c648b79ac25d5
		$this->render('typesMenu',array('types'=>$types));
	}
}

//end of TypesMenu.php