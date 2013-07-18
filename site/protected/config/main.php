<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'name' => 'Catalog',

    // preloading 'log' component
    'preload' => array('log'),

    // autoloading model and component classes
    'import' => array(
        'application.models.*',
        'application.components.*',
        'application.extensions.bootstrap.*',
    ),
    'modules' => array(
       /* 'gii' => array(
            'class' => 'system.gii.GiiModule',
            'password' => 'xxxx',
            'ipFilters' => array(
                '127.0.0.1',
                '192.168.56.1',
                '172.28.25.216'
            ), // EDIT TO TASTE
        ),*/
         ),

    'defaultController' => 'site',

    // application components
    'components' => array(

        'user' => array(
            // enable cookie-based authentication
            'allowAutoLogin' => true, ),
        // uncomment the following to use a MySQL database
        'db' => array(
            'connectionString' => 'mysql:host=localhost;dbname=catalog_yii_php',
            'emulatePrepare' => true,
            'username' => 'root',
            'password' => 'omakoleg',
            'charset' => 'utf8',
            'tablePrefix' => '',
            'enableProfiling' => true,
            'enableParamLogging' => true,
        ),
        'errorHandler' => array(
            // use 'site/error' action to display errors
            'errorAction' => 'site/error', ),
        'urlManager' => array(
            'showScriptName' => false,
            'urlFormat' => 'path',
            'rules' => array(
                'c/<slug:.*?>' => 'site/view',
                'p/<slug:.*?>/<id:.*?>' => 'site/item',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
            ),
        ),
        'log' => array(
            'class' => 'CLogRouter',
            'routes' => array(
                array(
                    'class' => 'CFileLogRoute',
                    'levels' => 'error, warning, trace, notice',
                ),
                // array('class' => 'CWebLogRoute',
                // 'enabled' => true
				// ),
            ),
        )
    ),

    // application-level parameters that can be accessed
    // using Yii::app()->params['paramName']
    'params' =>
    require (dirname(__FILE__) . '/params.php'),
);
