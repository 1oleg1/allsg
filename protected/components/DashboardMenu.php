<?php
/**
 * Этот виджет создает главное меню для панели управления (dashboard)
 * 
 * @author Vladimir Statsenko
 */
class DashboardMenu extends CWidget {
	public function run() {
		//если мы находимся на странице с формой входа, то этот виджет не показываем
		$action = Yii::app()->controller->action->id;
		if ('login' === $action) {
			return;
		}
		
		$this->render('dashboardMenu');
	}
}

// end of DashboardMenu.php