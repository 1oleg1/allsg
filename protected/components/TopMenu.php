<?php
/**
 * Этот виджет формирует верхнее горизонтальное меню
 */
class TopMenu extends CWidget {
	//тут мы просто перечисляем все нужные ссылки
	public function run() {
		$items[] = array(
                        'title'=>'Все', 
                        'link'=>array('games/list'),
                        'class'=>'btn btn-success'
                        );
		$items[] = array(
                        'title'=>'Новинки', 
                        'link'=>array('games/news'),
                        'class'=>'btn btn-danger'
                        );
		$items[] = array(
                        'title'=>'Популярные', 
                        'link'=>array('games/popular'),
                        'class'=>'btn btn-danger'
                        );                                                  
//		$items[] = array(
//                        'title'=>'Контакты', 
//                        'link'=>array('site/contact'),
//                        'class'=>'btn btn-success'
//                        );
//		if (!Yii::app()->user->isGuest) {
//			//эта ссылка будет показана если посетитель не является гостем
//			$items[] = array(
//                             'title'=>'Выход', 
//                             'link'=>array('dashboard/logout'),
//                             'class'=>'btn btn-danger'
//                             );
//		}		
		$this->render('topMenu',array('items'=>$items));
	}
}
//end of TopMenu.php