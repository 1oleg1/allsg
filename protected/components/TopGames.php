<?php
/**
 * Этот виджет формирует список самых популярных игр.
 * Для оценки популярности используется поле g_rate в таблице ygs_games.
 * Указать можно такие параметры:
 * 1) Заголовок виджета
 * 2) Массив страниц на которых будет отображаться виджет (в формате controller/action)
 * 3) Максимальное количество игр в виджете
 * 
 * @author Vladimir Statsenko
 */
class TopGames extends CWidget {
	
	public $title = 'Популярные игры';
	public $showOn = array();
	public $count = 10;
	
	public function run() {
		//определяем название контроллера и метода (action)
		$controller = Yii::app()->controller->id;
		$action = Yii::app()->controller->action->id;
		//проверяем нужно ли показывать виджет
		if (!in_array($controller.'/'.$action, $this->showOn)) {
			return;
		}
		//проверяем, нужно ли выводить игры только данного жанра
		if (isset($_GET['type_id'])) {
			$id = $_GET['type_id'];
			$params = array('limit'=>$this->count,'order'=>'g_rate DESC'
				,'condition'=>'t_id=:id'
				,'params'=>array(':id'=>$id));
			//ищем игры
			$games = Games::model()->getTopGames($params)->published()->with('ygs_types')->findAll();
		} else {
			$params = array('limit'=>$this->count,'order'=>'g_rate DESC');
			//ищем игры
			$games = Games::model()->getTopGames($params)->published()->findAll();
		}
		
		//заполняем массивы с жанрами игр для каждой найденной игры
		//для этого раскодируем поле g_type
		foreach ($games as $key=>$game) {
			$games[$key]->g_types = Yii::app()->controller->_decodeTypes($game->g_type);
		}
		//показываем виджет
		$this->render('topGames'
			, array('title'=>$this->title, 'games'=>$games));
	}
}

// end of TopGames.php