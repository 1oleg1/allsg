<?php
/**
 * Контроллер для работы с жанрами игр
 * 
 * @author Vladimir Statsenko
 */
class TypesController extends CController
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
	 * Правила доступа. Гости могут только просматривать список жанров.
	 * 
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			// закрываем доступ к list и show
/*			array('allow',  
				'actions'=>array('list','show'),
				'users'=>array('*'),
			),*/
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Этот метод не используется, т.к. список игр для выбранного
	 * жанра можно посмотреть в games/list, когда указан параметр type_id
	 */
	public function actionShow()
	{
	}

	/**
	 * Создает новый жанр.
	 */
	public function actionCreate()
	{
		$model=new Types;
		//если запрос отправлен через форму создания жанра
		if(isset($_POST['Types']))
		{
			$model->attributes=$_POST['Types'];
			//если жанр сохранен, переходим на страницу управления жанрами
			if($model->save())
				$this->redirect(array('admin'));
		}
		$this->render('create',array('model'=>$model));
	}

	/**
	 * Изменяет выбранный жанр.
	 */
	public function actionUpdate()
	{
		$model=$this->loadTypes();
		//если запрос отправлен через форму изменения жанра
		if(isset($_POST['Types']))
		{
			$model->attributes=$_POST['Types'];
			//если жанр сохранен, переходим на страницу управления жанрами
			if($model->save())
				$this->redirect(array('admin'));
		}
		$this->render('update',array('model'=>$model));
	}

	/**
	 * Удаляет жанры.
	 * Не используется. Запросы на удаление получает actionAdmin,
	 * после этого удаление выполняется в методе processAdminCommand.
	 */
	public function actionDelete()
	{
		if(Yii::app()->request->isPostRequest)
		{
			// we only allow deletion via POST request
			$this->loadTypes()->delete();
			$this->redirect(array('list'));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}

	/**
	 * Выводит список жанров.
	 * Не используется, т.к. этот список мы выводим с помощью
	 * виджета TypesMenu, а для панели управления используется метод actionAdmin
	 */
	public function actionList()
	{
		$criteria=new CDbCriteria;

		$pages=new CPagination(Types::model()->count($criteria));
		$pages->pageSize=self::PAGE_SIZE;
		$pages->applyLimit($criteria);

		$models=Types::model()->findAll($criteria);

		$this->render('list',array(
			'models'=>$models,
			'pages'=>$pages,
		));
	}

	/**
	 * Управление жанрами.
	 */
	public function actionAdmin()
	{
		//обработка команды удаления
		$this->processAdminCommand();
		//формируем запрос
		$criteria=new CDbCriteria;

		$pages=new CPagination(Types::model()->count($criteria));
		$pages->pageSize=self::PAGE_SIZE;
		$pages->applyLimit($criteria);
		//настраиваем сортировку
		$sort=new CSort('Types');
		$sort->applyOrder($criteria);
		//выполняем запрос
		$models=Types::model()->findAll($criteria);
		//показываем страницу
		$this->render('admin',array(
			'models'=>$models,
			'pages'=>$pages,
			'sort'=>$sort,
		));
	}

	/**
 	 * Возвращает жанр по его id, если он не найден - 404-ую ошибку
	 * id может быть указан в первом параметре или в массиве $_GET
	 * 
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the primary key value. Defaults to null, meaning using the 'id' GET variable
	 */
	public function loadTypes($id=null)
	{
		if($this->_model===null)
		{
			if($id!==null || isset($_GET['id']))
				$this->_model=Types::model()->findbyPk($id!==null ? $id : $_GET['id']);
			if($this->_model===null)
				throw new CHttpException(404,'The requested page does not exist.');
		}
		return $this->_model;
	}

	/**
	 * Выполняет команды, отправлены со страницы управления жанрами.
	 */
	protected function processAdminCommand()
	{
		if(isset($_POST['command'], $_POST['id']) && $_POST['command']==='delete')
		{
			$this->loadTypes($_POST['id'])->delete();
			// reload the current page to avoid duplicated delete actions
			$this->refresh();
		}
	}
	
}
