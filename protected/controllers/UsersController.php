<?php
/**
 * Контроллер для работы с пользователями.
 * Для работы этого приложения достаточно только одного пользователя.
 * 
 * @author Vladimir Statsenko
 */
class UsersController extends CController
{
	const PAGE_SIZE=10;

	/**
	 * @var string specifies the default action to be 'list'.
	 */
	public $defaultAction='list';

	/**
	 * @var CActiveRecord the currently loaded data model instance.
	 */
	private $_model;

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			//закрываем для гостей доступ ко всем методам этого класса
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('list','show','create','update','admin','delete'),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Не используется.
	 * Shows a particular model.
	 */
	public function actionShow()
	{
		$this->render('show',array('model'=>$this->loadUsers()));
	}

	/**
	 * Создает нового пользователя.
	 */
	public function actionCreate()
	{
		$model=new Users;
		if(isset($_POST['Users']))
		{
			$model->attributes=$_POST['Users'];
			if($model->save())
				$this->redirect(array('admin'));
		}
		$this->render('create',array('model'=>$model));
	}

	/**
	 * Изменяет данные выбранного пользователя.
	 */
	public function actionUpdate()
	{
		$model=$this->loadUsers();
		if(isset($_POST['Users']))
		{
			$model->attributes=$_POST['Users'];
			if($model->save())
				$this->redirect(array('admin'));
		}
		$this->render('update',array('model'=>$model));
	}

	/**
	 * Удаляет данные выбранного пользователя.
	 * Не используется. Запросы на удаление получает actionAdmin,
	 * после этого удаление выполняется в методе processAdminCommand.
	 */
	public function actionDelete()
	{
		if(Yii::app()->request->isPostRequest)
		{
			// we only allow deletion via POST request
			$this->loadUsers()->delete();
			$this->redirect(array('list'));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}

	/**
	 * Выводит список пользователей.
	 * Не используется.
	 */
	public function actionList()
	{
		$criteria=new CDbCriteria;

		$pages=new CPagination(Users::model()->count($criteria));
		$pages->pageSize=self::PAGE_SIZE;
		$pages->applyLimit($criteria);

		$models=Users::model()->findAll($criteria);

		$this->render('list',array(
			'models'=>$models,
			'pages'=>$pages,
		));
	}

	/**
	 * Создает страницу "Управление пользователями".
	 */
	public function actionAdmin()
	{
		//обрабатываем запрос на удаление
		$this->processAdminCommand();
		//формируем запрос
		$criteria=new CDbCriteria;

		$pages=new CPagination(Users::model()->count($criteria));
		$pages->pageSize=self::PAGE_SIZE;
		$pages->applyLimit($criteria);
		//настраиваем сортировку
		$sort=new CSort('Users');
		$sort->applyOrder($criteria);
		//получаем список пользователей
		$models=Users::model()->findAll($criteria);
		//показываем страницу
		$this->render('admin',array(
			'models'=>$models,
			'pages'=>$pages,
			'sort'=>$sort,
		));
	}

	/**
 	 * Возвращает данные пользователя по его id, если они не найдены - 404-ую ошибку
	 * id может быть указан в первом параметре или в массиве $_GET
	 * 
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the primary key value. Defaults to null, meaning using the 'id' GET variable
	 */
	public function loadUsers($id=null)
	{
		if($this->_model===null)
		{
			if($id!==null || isset($_GET['id']))
				$this->_model=Users::model()->findbyPk($id!==null ? $id : $_GET['id']);
			if($this->_model===null)
				throw new CHttpException(404,'The requested page does not exist.');
		}
		return $this->_model;
	}

	/**
	 * Выполняет команды, отправлены со страницы управления пользователями.
	 */
	protected function processAdminCommand()
	{
		if(isset($_POST['command'], $_POST['id']) && $_POST['command']==='delete')
		{
			$this->loadUsers($_POST['id'])->delete();
			// reload the current page to avoid duplicated delete actions
			$this->refresh();
		}
	}
}
