<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');
//   Yii::setPathOfAlias('bootstrap', dirname(__FILE__).'/../extensions/bootstrap');
// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	//название сайта
	'name'=>'Детские игры',
	//язык
	'language'=>'ru',
    // подключаем bootstrap
    //'theme'=>'bootstrap',    
	// preloading 'log' component
	'preload'=>array(
            'log',
//            'bootstrap',
              'tstranslation'
            ),
    // Add controller map for `tstranslation`
    'controllerMap' => array(
        'tstranslation' => 'tstranslation.controllers.TsTranslationController'
    ),            
	// контроллер по-умолчанию
	'defaultController'=>'games',

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
	),

	// application components
	'components'=>array(

			'tstranslation'=>array(
				/**
				* Set `tstranslation` class
				*/
				'class' => 'ext.tstranslation.components.TsTranslation',
				
				/**
				 * Set `accessRules` parameter (NOT REQUIRED),
				 * parameter effects to dynamic content translation and language managment
				 *
				 * AVAILABLE VALUES:
				 * - '*' means all users
				 * - '@' means all registered users
				 * - `username`. Example: 'admin' means Yii::app()->user->name === 'admin'
				 * - `array of usernames`. Example: array('admin', 'manager') means in_array(array('admin', 'manager'), Yii::app()->user->name)
				 * - your custom expression. Example: array('expression' => 'Yii::app()->user->role === "admin"')
				 * DEFAULT VALUE: '@'
				*/
				'accessRules' => '@',
				
				/**
				 * Set `languageChangeFunction` (NOT REQUIRED),
				 * function processing language change
				 *
				 * AVAILABLE VALUES:
				 * - `true` means uses extension internal function (RECOMENDED)
				 * - `array()` means user defined function. Example: array('TestClass', 'testMethod'), 'TestClass' and 'testMethod' must be exist and imported to project
				 * DEFAULT VALUE: `true`
				*/
				'languageChangeFunction' => true,
			),
    
    
/*		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
			),
		),*/
//        'bootstrap'=> array(
//            'class'=> 'bootstrap.components.Bootstrap',
//        ),        
                
		'user'=>array(
			// включаем аутенитификацию и указываем страницу с формой входа
			'allowAutoLogin'=>true,
			'loginUrl'=>array('dashboard/login'),
		),
		// настройки подключения к базе данных, тутже включаем кеширование
		// схемы и профайлинг
		'db'=>array(
			'connectionString'=>'mysql:host=localhost;dbname=allsupergames',
			'username'=>'allsupergames',
			'password'=>'11123qw',
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
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					//выводим лог внизу страницы
					'class'=>'CWebLogRoute',
					'levels'=>'trace, info, profile',
				),
			),
		),

		'urlManager'=>array(
            'class' => 'TsUrlManager',
            /**
            * Set `showLangInUrl` parameter (NOT REQUIRED),
            *
            * AVAILABLE VALUES:
            * - `true` means language code shows in url. Example: .../mysite/en/article/create
            * - `false` means language code not shows in url. Example: .../mysite/article/create
            * DEFAULT VALUE: `true`
            */
            'showLangInUrl' => true,
            /**
            * Set `prependLangRules` parameter (NOT REQUIRED),
            * this parameter takes effect only if `showLangInUrl` parameter is `true`.
            * It strongly recomended to add language rule to `rules` parameter handly
            *
            * AVAILABLES VALUES:
            * - `true` means automaticly prepends `_lang` parameter before all rules.
            *      Example: '<_lang:\w+><controller:\w+>/<id:\d+>' => '<controller>/view',
            * - `false` means `_lang` parameter you must add handly
            * DEFAULT VALUE: `true`
            */
            'prependLangRules' => true,                        
			'urlFormat'=>'path',
			'rules'=>array(
//				'games/show/<id:\d+>'=>'games/show',
//				'screenshots/show/<id:\d+>'=>'screenshots/show',
//				'games/archive/<year:\d+>/<month:\d+>'=>'games/archive',
			),
			'showScriptName'=>false,
		),

            /**
             * Add `messages` component
             */
            'messages' => array(
                /**
                * Set `messages` class
                */
                'class' => 'TsDbMessageSource',

                /**
                * Set `Missing Messages` translation action
                */
                'onMissingTranslation' => array('TsTranslation', 'addTranslation'),

                /**
                 * Set `notTranslatedMessage` parameter (NOT REQUIRED),
                 *
                 * AVAILABLE VALUES:
                 * - `false / null` means nothing shows if message translation is empty
                 * - `text` means shows defined text if message translation is empty.
                 *      Example: 'Not translated data!'
                 * DEFAULT VALUE: `null`
                */
               'notTranslatedMessage' => 'Not translated data!',

                /**
                 * Set `ifNotTranslatedShowDefault` parameter (NOT REQUIRED),
                 *
                 * AVAILABLE VALUES:
                 * - `false` means shows `$this->notTranslatedMessage` if message translation is empty
                 * - `true` means shows default language translation if message translation is empty.
                 * DEFAULT VALUE: `true`
                */
                'ifNotTranslatedShowDefault' => false,

            ),    
	),
    
    'modules'=>array(
        'gii'=>array(
            'class'=>'system.gii.GiiModule',
	       'password'=>'wqa2467',
	       // If removed, Gii defaults to localhost only. Edit carefully to taste.
	       'ipFilters' => array('127.0.0.1', '::1'),
//	       'generatorPaths'=>array(
//		      'bootstrap.gii',
//	       ),           
            // 'newFileMode'=>0666,
            // 'newDirMode'=>0777,
        ),
    ),    
    

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		// this is used in contact page
		'adminEmail'=>'webmaster@example.com',
	),
   /**
   * Set `language` and `sourceLanguage` (NOT REQUIRED)
   */
   'language' => 'ru',
//   'sourceLanguage' => 'ru',    
    
);