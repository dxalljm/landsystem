<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/../../frontend/config/params.php'),
    require(__DIR__ . '/../../frontend/config/params-local.php'),
    require(__DIR__ . '/params.php')
//     require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log', 'gii'],
    'controllerNamespace' => 'console\controllers',
    'modules' => [
        'gii' => 'yii\gii\Module',
    ],
    'components' => [
    		'elasticsearch' => [
    				'class' => 'yii\elasticsearch\Connection',
    				//     				'nodes' => [
    						//     						['http_address' => '127.0.0.1:9200'],
    						//     						// configure more hosts if you have a cluster
    						//     				],
    		],
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
    ],
    'params' => $params,
];
