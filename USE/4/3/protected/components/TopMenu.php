<?php
/**
 * Этот виджет формирует верхнее горизонтальное меню
 * 
 * @author Vladimir Statsenko
 */
class TopMenu extends CWidget {
	//тут мы просто перечисляем все нужные ссылки
	public function run() {
		$items[] = array('title'=>'Главная', 'link'=>array('games/list'));
		$items[] = array('title'=>'Контакты', 'link'=>array('site/contact'));
		if (!Yii::app()->user->isGuest) {
			//эта ссылка будет показана если посетитель не является гостем
			$items[] = array('title'=>'Выход', 'link'=>array('dashboard/logout'));
		}
		
		$this->render('topMenu',array('items'=>$items));
	}
}
//end of TopMenu.php