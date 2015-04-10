<?php
/**
 * Этот виджет показывает случайные скриншоты
 * Можно изменять количество с помощью переменной $count
 * 
 * @author Vladimir Statsenko
 */
class RandomScreenshots extends CWidget {
	//количество скриншотов
	public $count = 2;
	
	public function run() {
		//формируем условия поиска скриншотов
		$criteria = new CDbCriteria;
		//ограничиваем количество
		$criteria->limit = $this->count;
		//устанавливаем случайный порядок вывода
		$criteria->order = 'RAND()';
		
		//выполняем поиск и показываем виджет
		$screenshots = Screenshots::model()->findAll($criteria);
		$this->render('randomScreenshots', array('screenshots'=>$screenshots));
	}
}