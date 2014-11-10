<?php
/**
 * Выводит список ссылок на месячные архивы игр.
 * Учитываются только опубликованные игры (g_state == 0).
 * 
 * @author Vladimir Statsenko
 */
class ArchiveMenu extends CWidget {
	public function run() {
		//массив с русскими названиями месяцев
		$monthNames = array(
			'1'=>'Январь',
			'2'=>'Февраль',
			'3'=>'Март',
			'4'=>'Апрель',
			'5'=>'Май',
			'6'=>'Июнь',
			'7'=>'Июль',
			'8'=>'Август',
			'9'=>'Сентябрь',
			'10'=>'Октябрь',
			'11'=>'Ноябрь',
			'12'=>'Декабрь'
		);
		
		//в результате выполнения этого зарпоса мы получим список
		//всех месяцев в которых была опубликована хотябы одна игра
		//(и количество этих игр)
		$sql = 'SELECT YEAR( g_added ) AS year, MONTH( g_added ) AS month ,'
			.' COUNT( g_name ) AS games_count '
			.' FROM ygs_games'
			.' WHERE g_state=0'
			.' GROUP BY year DESC, month DESC';
		
		$command = Yii::app()->db->createCommand($sql);
		//выполняем запрос
		$items = $command->queryAll();
		//показываем виджет
		$this->render('archiveMenu',array('items'=>$items, 'monthNames'=>$monthNames));
	}
}

// end of ArchiveMenu.php