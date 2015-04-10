<?php
/**
 * Контроллер для работы со скриншотами
 * 
 * @author Vladimir Statsenko
 */
class ScreenshotsController extends CController
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
			array('allow',  // allow all users to perform 'list' and 'show' actions
				'actions'=>array('list','show'),
				'users'=>array('*'),
			),
			//закрываем доступ к методам create, update, admin, delete
/*			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'users'=>array('admin'),
			),*/
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Показываем выбранный скриншот.
	 */
	public function actionShow()
	{
		if (isset($_GET['id'])) {
			$model = Screenshots::model()->with('s_game')->findByPk($_GET['id']);
		} else {
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
		}
		
		if (null === $model) {
			throw new CHttpException(404,'The requested page does not exist.');
		}
		$this->render('show',array('model'=>$this->loadScreenshots()));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'show' page.
	 */
	public function actionCreate()
	{
		$model=new Screenshots;
		if(isset($_POST['Screenshots']))
		{
			$model->attributes=$_POST['Screenshots'];
			if($model->save())
				$this->redirect(array('show','id'=>$model->s_id));
		}
		$this->render('create',array('model'=>$model));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'show' page.
	 */
	public function actionUpdate()
	{
		$model=$this->loadScreenshots();
		if(isset($_POST['Screenshots']))
		{
			$model->attributes=$_POST['Screenshots'];
			if($model->save())
				$this->redirect(array('show','id'=>$model->s_id));
		}
		$this->render('update',array('model'=>$model));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'list' page.
	 */
	public function actionDelete()
	{
		if(Yii::app()->request->isPostRequest)
		{
			// we only allow deletion via POST request
			$this->loadScreenshots()->delete();
			$this->redirect(array('list'));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}

	/**
	 * Показывает список скриншотов.
	 */
	public function actionList()
	{
		//формируем запрос
		$criteria=new CDbCriteria;

		$pages=new CPagination(Screenshots::model()->with('s_game')->count($criteria));
		$pages->pageSize=self::PAGE_SIZE;
		$pages->applyLimit($criteria);
		//получаем данные
		$models=Screenshots::model()->with('s_game')->findAll($criteria);

		if (empty($models)) {
			throw new CHttpException(404,'The requested page does not exist.');
		}
		//показываем страницу
		$this->render('list',array(
			'models'=>$models,
			'pages'=>$pages,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$this->processAdminCommand();

		$criteria=new CDbCriteria;

		$pages=new CPagination(Screenshots::model()->count($criteria));
		$pages->pageSize=self::PAGE_SIZE;
		$pages->applyLimit($criteria);

		$sort=new CSort('Screenshots');
		$sort->applyOrder($criteria);

		$models=Screenshots::model()->findAll($criteria);

		$this->render('admin',array(
			'models'=>$models,
			'pages'=>$pages,
			'sort'=>$sort,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the primary key value. Defaults to null, meaning using the 'id' GET variable
	 */
	public function loadScreenshots($id=null)
	{
		if($this->_model===null)
		{
			if($id!==null || isset($_GET['id']))
				$this->_model=Screenshots::model()->findbyPk($id!==null ? $id : $_GET['id']);
			if($this->_model===null)
				throw new CHttpException(404,'The requested page does not exist.');
		}
		return $this->_model;
	}

	/**
	 * Executes any command triggered on the admin page.
	 */
	protected function processAdminCommand()
	{
		if(isset($_POST['command'], $_POST['id']) && $_POST['command']==='delete')
		{
			$this->loadScreenshots($_POST['id'])->delete();
			// reload the current page to avoid duplicated delete actions
			$this->refresh();
		}
	}
}
