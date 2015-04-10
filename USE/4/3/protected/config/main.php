<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');
   Yii::setPathOfAlias('bootstrap', dirname(__FILE__).'/../extensions/bootstrap');
// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	//название сайта
	'name'=>'Лучшие игры',
	//язык
	'language'=>'ru',
    // подключаем bootstrap
    'theme'=>'bootstrap',    
	// preloading 'log' component
	'preload'=>array('log','bootstrap'),
	// контроллер по-умолчанию
	'defaultController'=>'games',

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
	),

	// application components
	'components'=>array(
/*		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
			),
		),*/
        'bootstrap'=> array(
            'class'=> 'bootstrap.components.Bootstrap',
        ),        
        
        
		'user'=>array(
			// включаем аутенитификацию и указываем страницу с формой входа
			'allowAutoLogin'=>true,
			'loginUrl'=>array('dashboard/login'),
		),
		// настройки подключения к базе данных, тутже включаем кеширование
		// схемы и профайлинг
		'db'=>array(
//			'connectionString'=>'mysql:host=localhost;dbname=gameyii',
//			'username'=>'gameyii',
//			'password'=>'123456',

                        'connectionString'=>'mysql:host=localhost;dbname=flash4me',
                        'username'=>'flash4me',
                        'password'=>'flash4me1',
			'charset'=>'utf8',
			'enableParamLogging'=>true,
			'enableProfiling'=>true,
			'schemaCachingDuration'=>3600
		),
		//тип кеша, который нужно использовать
		'cache'=>array(
			'class'=>'system.caching.CFileCache',
		),
		//настройки лога событий
		/*'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					//выводим лог внизу страницы
					'class'=>'CWebLogRoute',
					'levels'=>'trace, info, profile',
				),
			),
		),*/

		'urlManager'=>array(
			'urlFormat'=>'path',
			'rules'=>array(
				'games/show/<id:\d+>'=>'games/show',
				'screenshots/show/<id:\d+>'=>'screenshots/show',
				'games/archive/<year:\d+>/<month:\d+>'=>'games/archive',
			),
			'showScriptName'=>false,
		),

        'gii'=>array(
	       'class' => 'system.gii.GiiModule',
	       'password'=>'vfhfpvfnbr',
	       // If removed, Gii defaults to localhost only. Edit carefully to taste.
	       'ipFilters' => array('127.0.0.1', '::1'),
	       'generatorPaths'=>array(
		      'bootstrap.gii',
	       ),
        ),      
        
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		// this is used in contact page
		'adminEmail'=>'webmaster@example.com',
	),
);