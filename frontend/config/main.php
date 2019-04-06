<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
	'language'=>'zh-CN',
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'components' => [
    		'elasticsearch' => [
    				'class' => 'yii\elasticsearch\Connection',
//     				'nodes' => [
//     						['http_address' => '127.0.0.1:9200'],
//     						// configure more hosts if you have a cluster
//     				],
    		],
    		'session' => [
    				'class' => 'yii\web\Session',
    				'name' => 'FRONTENDSSID',//可以自定义
    				//'savePath' => __DIR__ . '/../tmp',//手工在backend目录下新建文件夹TMP
    		],
    		'excel'=>array(
    				'class'=>'application.extensions.PHPExcel',
    		),
    		
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning','info','trace'],
                ],
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['info'],
                    'categories' => ['rhythmk'],
                    'logFile' => '@app/runtime/logs/Mylog/requests.log',
                    'maxFileSize' => 1024 * 2,
                    'maxLogFiles' => 20,
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
    ],
    'params' => $params,
];
