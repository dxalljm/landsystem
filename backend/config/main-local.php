<<<<<<< HEAD
<?php

$config = [
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'Rf1CqJ19uGABX083nhJy-AfFymPG4f70',
        ],
    ],
];

if (!YII_ENV_TEST) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
    		'class' => 'yii\debug\Module',
    		'allowedIPs' => ['*']
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
    		'class' => 'yii\gii\Module',
    		'allowedIPs' => ['*']
    ];
}

return $config;
=======
<?php

$config = [
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'Rf1CqJ19uGABX083nhJy-AfFymPG4f70',
        ],
    ],
];

if (!YII_ENV_TEST) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = 'yii\debug\Module';

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii']['class'] = 'yii\gii\Module';
    $config['modules']['gii']['allowedIPs'] = ['*'];
}

return $config;
>>>>>>> e8af1cd29bb9d17f4c7726861a0ddbdd054c389f
