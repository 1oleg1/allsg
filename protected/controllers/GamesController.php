<?php
/**
 * Этот контроллер формирует страницы с играми,
 * и страницы для управления играми в панели управления. 
 * 
 * @author Vladimir Statsenko
 */
class GamesController extends CController
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
	
	//массив с полным переченем жанров 
	private $_allTypes = array();
        
        public $_name_rus = "";
        public $_name_eng = "";
        public $_shortdescr_rus = "";
        public $_shortdescr_eng = "";
        public $_fulldescr_rus = "";
        public $_fulldescr_eng = "";

        //массив с названиями месяцев
	private $monthNames = array(
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

	/**
	 * @return array action filters
	 */
	public function filters(){
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
		//в этом массиве перечисляем методы доступ к которым разрешен
		//пользовалям
		return array(
			array('allow',  // для гостей
				'actions'=>array('list','show','archive'),
				'users'=>array('*'),
			),
			array('allow', // для администратора (убрали из списка 'create', т.о. заблокировали эту операцию)
				'actions'=>array('update','create'),
				'users'=>array('@'),
			),
			array('allow', // для администратора
				'actions'=>array('admin','delete','import'),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Отображает страницу с подробным описанием игры.
	 * Для создания галлереи скриншотов используется библиотека jQuery и плагин
	 * lightbox.
	 */
	public function actionShow()
	{
		$cs = Yii::app()->clientScript;
		//подключаем jquery, которая идет в комплекте с фреймворком
		$cs->registerCoreScript('jquery');
		//подключаем lightbox
		$cs->registerScriptFile(Yii::app()->request->baseUrl.'/js/lightbox/js/jquery.lightbox-0.5.min.js', CClientScript::POS_END);
		$cs->registerScriptFile(Yii::app()->request->baseUrl.'/js/init_lightbox.js' ,CClientScript::POS_END);
		$cs->registerCssFile(Yii::app()->request->baseUrl.'/js/lightbox/css/jquery.lightbox-0.5.css');
		//показываем форму
		$this->render('show',array('model'=>$this->loadGames()));
	}

	/**
	 * Создание игры (к нему доступ закрыт).
	 * If creation is successful, the browser will be redirected to the 'show' page.
	 */
	public function actionCreate()
	{
                $cs = Yii::app()->clientScript;
		//подключаем редактор tinyMce для 'class'=>'editme'
		$cs->registerScriptFile(Yii::app()->request->baseUrl.'/js/tiny_mce/tiny_mce_gzip.js', CClientScript::POS_END);
		$cs->registerScriptFile(Yii::app()->request->baseUrl.'/js/tiny_mce/init_gz.js' ,CClientScript::POS_END);
		$cs->registerScriptFile(Yii::app()->request->baseUrl.'/js/tiny_mce/init_editor.js' ,CClientScript::POS_END);

		$model=new Games;

		if(isset($_POST['Games'])){
                        //Полю icon присвоить значения поля формы icon
			$model->icon=CUploadedFile::getInstance($model,'icon');
                        $model->filename2=CUploadedFile::getInstance($model,'filename2');
                        if ($model->icon){
				$sourcePath = pathinfo($model->icon->getName());	
				$fileName = '/games/images/'.date('Y').'/'.'img_'.$model->g_publish_date.rand().'.'.$sourcePath['extension'];
				$model->g_medium_pic = $fileName;
			}
                        // Присваиваем файл и записываем в базу
			if ($model->filename2){
				$sourcePath = pathinfo($model->filename2->getName());			
				$fileName2 = '/games/files/'.date('Y').'/'.$model->g_publish_date.rand().'.'.$sourcePath['extension'];
				$model->filename = $fileName2;
			}                        
			$model->attributes=$_POST['Games'];
			$model->g_types=$_POST['types'];
			$model->g_type = $this->_encodeTypes();
			if($model->save()){	                         	
				if ($model->icon){ //Если поле загрузки файла не было пустым, то сохранить файл на сервере в каталог           
					$file = $_SERVER['DOCUMENT_ROOT'].$fileName;
					$model->icon->saveAs($file); // записываем картинку                                                                                                                                                                
				}
                                if ($model->filename2){  
                                        $file2 = $_SERVER['DOCUMENT_ROOT'].$fileName2;
                                        $model->filename2->saveAs($file2);
                                }                                 
				$this->redirect(array('show','id'=>$model->g_id));
                        }
		}
		//$this->render('create',array('model'=>$model));
                $this->render('create',array('model'=>$model, 'types'=>$types));                
	}

	/**
	 * Изменение игры.
	 */
	public function actionUpdate()
	{
		$cs = Yii::app()->clientScript;
		//подключаем редактор tinyMce для 'class'=>'editme'
		$cs->registerScriptFile(Yii::app()->request->baseUrl.'/js/tiny_mce/tiny_mce_gzip.js', CClientScript::POS_END);
		$cs->registerScriptFile(Yii::app()->request->baseUrl.'/js/tiny_mce/init_gz.js' ,CClientScript::POS_END);
		$cs->registerScriptFile(Yii::app()->request->baseUrl.'/js/tiny_mce/init_editor.js' ,CClientScript::POS_END);
		//получаем данные игры из базы
		$model=$this->loadGames();
                //$model->attributes['g_fulldescr'] = "sss";
                //$model->g_fulldescr_en = "sss";
                $red5 = "<span style='color: green; font-size: 10px; font-weight: bold;'>Онлайн перевод</span><br />";
                //---------------------
                if (empty($model->g_name_en)){ // перевод с русского на английский                
                    $model->g_name_en = Games::actionGoogleTranslate('ru','en',$model->g_name,1);
                    $this->_name_eng = $red5;
                }
                if (empty($model->g_name)){ // перевод английского на русский
                    $model->g_name = Games::actionGoogleTranslate('en','ru',$model->g_name_en,1);
                    $this->_name_rus = $red5;
                }                
                //---------------------
                if (empty($model->g_shortdescr_en)){ // перевод полного с русского на английский                
                    $model->g_shortdescr_en = Games::actionGoogleTranslate('ru','en',$model->g_shortdescr);
                    $this->_shortdescr_eng = $red5;
                }
                if (empty($model->g_shortdescr)){ // перевод полного с английского на русский
                    $model->g_shortdescr = Games::actionGoogleTranslate('en','ru',$model->g_shortdescr_en);
                    $this->_shortdescr_rus = $red5;
                }                
                //---------------------
                if (empty($model->g_fulldescr_en)){ // перевод полного с русского на английский                
                    $model->g_fulldescr_en = Games::actionGoogleTranslate('ru','en',$model->g_fulldescr);
                    $this->_fulldescr_eng = $red5;
                }
                if (empty($model->g_fulldescr)){ // перевод полного с английского на русский
                    $model->g_fulldescr = Games::actionGoogleTranslate('en','ru',$model->g_fulldescr_en);
                    $this->_fulldescr_rus = $red5;
                }                
                //---------------------                
                //получаем список жанров
		//$types = Types::model()->findAll();
		//если получены данные игры
		if(isset($_POST['Games']))
		{
			//записываем атрибуты
			$model->attributes=$_POST['Games'];
			//получаем список чекбоксов, соответствующих выбранным жанрам...
			$model->g_types=$_POST['types'];
			//... кодируем их и результат записываем в атрибут g_type
			$model->g_type = $this->_encodeTypes();
			//эта переменная запрещает изменение списка скриншотов игры
			//(т.к. для удаления скриншотов используется отдельная форма)
			$model->updateScreenshots = false;
			$model->icon=CUploadedFile::getInstance($model,'icon');
                        $model->filename2=CUploadedFile::getInstance($model,'filename2');
                        // Присваиваем скриншот и записываем в базу
			if ($model->icon){
				$sourcePath = pathinfo($model->icon->getName());			
				$fileName = '/games/images/'.substr($model->g_publish_date, 0, 4).'/'.'img_'.$model->g_publish_date.rand().'.'.$sourcePath['extension'];
				$model->g_medium_pic = $fileName;
			}
                        // Присваиваем файл и записываем в базу
			if ($model->filename2){
				$sourcePath = pathinfo($model->filename2->getName());			
				$fileName2 = '/games/files/'.substr($model->g_publish_date, 0, 4).'/'.$model->g_publish_date.rand().'.'.$sourcePath['extension'];
				$model->filename = $fileName2;
			}                         
			//сохраняем игру
			if($model->save()) {
                            //Если отмечен чекбокс «удалить файл»            
                            if($model->del_img){
                                    if(file_exists($_SERVER['DOCUMENT_ROOT'].$model->g_medium_pic)) {//удаляем файл						
					unlink($_SERVER['DOCUMENT_ROOT'].$model->g_medium_pic);
					$model->g_medium_pic = '';
                                        $model->save();
                                    }
                            }                            				
                            //Если поле загрузки файла не было пустым, то            
                            if ($model->icon){  
				$file = $_SERVER['DOCUMENT_ROOT'].$fileName;
				$model->icon->saveAs($file);
                            }                                                                    
                            if ($model->filename2){  
				$file2 = $_SERVER['DOCUMENT_ROOT'].$fileName2;
				$model->filename2->saveAs($file2);
                            }                            
			$this->redirect(array('update','id'=>$model->g_id));
                        }
		}
		//если получены данные из формы удаления скриншотов
//		if (isset($_POST['screenshots'])) {
//			//удаляем выбранные скриншоты
//			foreach ($_POST['screenshots'] as $id) {
//				$s = Screenshots::model()->findByPk($id);
//				$s->delete();
//			}
//		}
       
		//показываем форму
		$this->render('update',array('model'=>$model, 'types'=>$types));
	}

	/**
	 * Удаляет игры.
	 * Не используется. Запросы на удаление получает actionAdmin,
	 * после этого удаление выполняется в методе processAdminCommand.
	 */
	public function actionDelete()
	{
		if(Yii::app()->request->isPostRequest)
		{
			// запрос на удаление должен быть типа POST
			$this->loadGames()->delete();
			$this->redirect(array('admin'));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}

	/**
	 * Формирует список игр (общий или отдельно для выбранного жанра).
	 */
	public function actionList()
	{
		$type = null;
		//формируем запрос на поиск игр с сортировкой по дате
		$criteria=new CDbCriteria;
		$criteria->order = 'g_publish_date DESC';
		//если указан параметр type_id, нужно показывать только игры выбранного жанра
		if (isset($_GET['type_id']) && is_numeric($_GET['type_id'])) {
			//ищем указанный жанр
			$type = Types::model()->findByPk($_GET['type_id']);
			//если указанный жанр найден...
			if (null !== $type) {
				//...добавляем в запрос дополнительный параметр
				$criteria->condition = 't_id=:t_id';
				$criteria->params = array(':t_id'=>$_GET['type_id']);
			}
		}

		//получаем данные для пагинации
		if (null !== $type) {
			$pages=new CPagination(Games::model()->published()->with('ygs_types')->count($criteria));
		} else {
			$pages=new CPagination(Games::model()->published()->count($criteria));
		}
		$pages->pageSize=self::PAGE_SIZE;
		$pages->applyLimit($criteria);

		//получаем список игр
		if (null !== $type) {
			//ВАЖНО! Вызов published должен идти до with
			$models=Games::model()->published()->with('ygs_types')->findAll($criteria);
		} else {
			$models=Games::model()->published()->findAll($criteria);
		}
		
		//если ни одной страницы не найдено, отправляем 404-ую ошибку
		if (empty($models)) {
			throw new CHttpException(404,'The requested page does not exist.');
		}

		//заполняем массив с жанрами игр
		foreach ($models as $key=>$game) {
			$criteria=new CDbCriteria;
			$criteria->order = 'g_publish_date DESC';
			//расшифровываем жанры игр (по коду в поле g_type)
			$models[$key]->g_types = $this->_decodeTypes($game->g_type);
		}
		//показываем страницу
		$this->render('list',array(
			'models'=>$models,
			'pages'=>$pages,
			'type'=>$type
		));
	}
	
	/**
	 * Формирует список игр, опубликованных в выбранный месяц
	 */
	public function actionArchive() {
		//определяем месяц и год
		$year = isset($_GET['year']) ? $_GET['year'] : date('Y',time());
		$month = isset($_GET['month']) ? $_GET['month'] : date('n',time());
		
		//формируем запрос
		$criteria=new CDbCriteria;
		$criteria->order = 'g_publish_date DESC';
		$criteria->condition = 'YEAR(g_publish_date)=:year AND MONTH(g_publish_date)=:month';
		$criteria->params = array(':year'=>$year,':month'=>$month);
		//получаем данные для пагинации
		$pages=new CPagination(Games::model()->published()->count($criteria));
		$pages->pageSize=self::PAGE_SIZE;
		$pages->applyLimit($criteria);
		//получаем список игр
		$models=Games::model()->published()->findAll($criteria);
		//если игры не найдены, показываем 404-ую ошибку
		if (empty($models)) {
			throw new CHttpException(404,'The requested page does not exist.');
		}
		
		//заполняем массив с жанрами игр
		foreach ($models as $key=>$game) {
			$criteria=new CDbCriteria;
			$criteria->order = 'g_publish_date DESC';

			$models[$key]->g_types = $this->_decodeTypes($game->g_type);
		}
		
		$monthYear = $this->monthNames[$month].' '.$year;
		//показываем страницу
		$this->render('archive',array(
			'models'=>$models,
			'pages'=>$pages,
			'date'=>$monthYear
		));
	}

	/**
	 * Управление играми.
	 */
	public function actionAdmin()
	{
		//обработка команды удаления
		$this->processAdminCommand();

		//формируем запрос
		$criteria=new CDbCriteria;
		//получаем данные для пагинации
		$pages=new CPagination(Games::model()->count($criteria));
		$pages->pageSize=self::PAGE_SIZE;
		$pages->applyLimit($criteria);
		//создаем объект для сортировки (при клике по названию столбца в таблице
		//будет выполняться сортировка по этому столбцу)
		$sort=new CSort('Games');
		$sort->applyOrder($criteria);
		//получаем список игр
		$models=Games::model()->findAll($criteria);
		//показываем страницу
		$this->render('admin',array(
			'models'=>$models,
			'pages'=>$pages,
			'sort'=>$sort,
		));
	}

	/**
	 * Возвращает игру по ее id, если она не найдена - 404-ую ошибку
	 * id может быть указан в первом параметре или в массиве $_GET
	 *  
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the primary key value. Defaults to null, meaning using the 'id' GET variable
	 */
	public function loadGames($id=null)
	{
		if($this->_model===null)
		{
			if($id!==null || isset($_GET['id']) || !empty($_GET['id'])){
				$this->_model=Games::model()->findbyPk($id!==null ? $id : $_GET['id']);
				$this->_model->g_types = $this->_decodeTypes($this->_model->g_type);
                        } else
//			if($this->_model===null)
				throw new CHttpException(404,'The requested page does not exist2.');
		}
		return $this->_model;
	}

	/**
	 * Выполняет команды, отправлены со страницы управления играми.
	 */
	protected function processAdminCommand()
	{
		if(isset($_POST['command'], $_POST['id']) && $_POST['command']==='delete')
		{
			$this->loadGames($_POST['id'])->delete();
			// reload the current page to avoid duplicated delete actions
			$this->refresh();
		}
	}

	/**
	 * Импортирует игры из xml фида партнерки в базу данных
	 * @return страница с результатами
	 */
	public function actionImport() {
		//получаем список всех жанров
		$types = Types::model()->findAll();
		//ищем все сохраненные игры (их id)
		$existingIds = Games::model()->getExistingIds();
		$errors = array();
		$results = '';
		//обработка команды
		if (isset($_POST['import'])) {
			libxml_use_internal_errors(true);
			//загружаем xml фид
			$xml = simplexml_load_file(Yii::app()->user->xml);
			if (!$xml) {
				$errors = libxml_get_errors();
			}
			else {
				$i = 0;
				//парсинг фида
				foreach ($xml->result->ITEM as $game) {
					//если эта игра уже сохранена...
					if (in_array($game->ID, $existingIds)) {
						//...переходим к следующей
						continue;
					}
					//создаем новую игру
					$newGame = new Games;
					//заполняем атрибуты
					$newGame->g_id = $game->ID;
					$newGame->g_rate = $game->RATE;
					$newGame->g_name_url = $game->NAME_URL;
					$newGame->g_type = $game->TYPE;
					$newGame->g_added = $game->ADDED;
					$newGame->g_size = $game->SIZE;
					$newGame->g_name = $game->NAME;
					$newGame->g_medium_pic = $game->MEDIUM_PIC;
					$newGame->g_small_pic = $game->SMALL_PIC;
					$newGame->g_shortdescr = $game->SHORTDESCR;
					$newGame->g_fulldescr = $game->FULLDESCR;
					$newGame->g_publish_date = date('Y-m-d', time());
					$newGame->g_state = Games::PUBLISHED;
					//записываем массив со скриншотами
					foreach ($game->SCREENSHOT as $sh) {
						$newGame->g_screenshots[] = $sh;
					}
					//разбираем поле с жанрами
					foreach ($types as $type) {
						if ($newGame->g_type & $type['t_id']) {
							$newGame->g_types[] = $type['t_id'];
						}
					}
					//сохраняем игру в БД
					//сохранение скриншотов и жанров выполняетя в Games::afterSave()
					if (!$newGame->save()) {
						$errors[] = 'Не могу сохранить игру id = '.$newGame->g_id;
					}
					else {
						$i++;
					}
				}
				$results = 'Сохранено новых игр '.$i;
			}
		}
		//показываем форму
		$this->render('import',
			array('xml'=>Yii::app()->user->xml, 'errors'=>$errors, 'results'=>$results));
	}

	/**
	 * Метод предназначен для преобразования значения в поле g_rate
	 * таблицы ygs_games в массив соответствующих жанров
	 * @return array массив с жанрами 
	 */
	public function _decodeTypes($value) {
		if (count($this->_allTypes) == 0) {
			//получаем список жанров
			$this->_allTypes = Types::model()->findAll();
		}
		$types = array();
		//перебираем все жанры и проверяем указаны ли они в поле жанра игры
		//для этого используется логическая операция "И" 
		foreach ($this->_allTypes as $type) {
			if ((int)$value & (int)$type->t_id) {
				$types[] = $type;
			}
		}
		//возвращаем массив с жанрами
		return $types;
	}
	
	/**
	 * Этот метод кодирует список жанров для сохранения в поле g_type.
	 * Для кодирования используется операция OR.
	 * @return int закодированное значение
	 */
	private function _encodeTypes() {
		$res = 0;
		if (isset($_POST['types'])) {
			foreach ($_POST['types'] as $type) {
				//Кодирование выполняем с помощью операции "ИЛИ"
				$res |= (int)$type;
			}
		}
		return $res;
	}

        public function material_image($id, $title, $image, $width='150', $class='material_img'){
            if(isset($image) && file_exists($_SERVER['DOCUMENT_ROOT'].$image))
		return CHtml::image($image, $title,array('width'=>$width,'class'=>$class,));
            else
		return CHtml::image('/images/no_image.jpg','Нет картинки',array('width'=>$width,'class'=>$class));
	}        
        
}
