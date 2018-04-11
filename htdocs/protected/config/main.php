<?php

// раскомментируйте следующее, чтобы определить путь патча
// Yii::setPathOfAlias('local','path/to/local-folder');

// Это основная конфигурация веб-приложения. Любой доступный для записи
// Свойства CWebApplication можно настроить здесь.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'Yii Блог Демо',

	// предварительная загрузка компонента «log»
	'preload'=>array('log'),

	// автозагрузка модели и классов компонентов
	'import'=>array(
		'application.models.*',
		'application.components.*',
	),

	'defaultController'=>'post',

	// компоненты приложения
	'components'=>array(
		'user'=>array(
			// включить аутентификацию на основе файлов cookie
			'allowAutoLogin'=>true,
		),
		'db'=>array(
			'connectionString' => 'sqlite:protected/data/blog.db',
			'tablePrefix' => 'tbl_',
		),
		// раскомментируйте следующее, чтобы использовать базу данных MySQL
		
		'db'=>array(
			'connectionString' => 'mysql:host=localhost;dbname=blog',
			'emulatePrepare' => true,
			'username' => 'root',
			'password' => '',
			'charset' => 'utf8',
			'tablePrefix' => 'tbl_',
		),
		
		'errorHandler'=>array(
			// использовать действие «сайт / ошибка» для отображения ошибок
			'errorAction'=>'site/error',
		),
		'urlManager'=>array(
			'urlFormat'=>'path',
			'rules'=>array(
				'post/<id:\d+>/<title:.*?>'=>'post/view',
				'posts/<tag:.*?>'=>'post/index',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
			),
		),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
				// раскомментируйте следующее, чтобы показать сообщения журнала на веб-страницах
				
				//array(
				//	'class'=>'CWebLogRoute',
				//),
				
			),
		),
	),

	// параметры уровня приложения, к которым можно получить доступ
	// using Yii::app()->params['paramName']
	'params'=>require(dirname(__FILE__).'/params.php'),
);