<?php

$config = [
    'components' => [
        'ftp' => [
            'class' => '\gftp\FtpComponent',
//            'protocol' => \gftp\FtpProtocol::valueOf('FTPS'),
            'driverOptions' => [
                'class' => '\gftp\drivers\FtpDriver',
//                'protocol' => \gftp\FtpProtocol::valueOf('FTP'),
                'user' => 'administrator',
                'pass' => 'lngwh@2738013',
                'host' => '192.168.1.10',
                'port' => 2121,
                'timeout' => 120,
                'passive' => false,
            ],
        ],
//        'ftp' => [
//            'class' => '\gftp\FtpComponent',
//            'connectionString' => '192.168.1.10:2121',
////            'timeout' => 120,
////            'passive' => false,
//        ],
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'zP1A_XxkejMFDr9HIV4RWdTyqpizQQqJ',
        ],
    ],
];

if (!YII_ENV_TEST) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        'allowedIPs' => ['192.168.1.10']
    ];
//     $config['modules']['allowedIPs'] = ['1,2,3,4','127.0.0.1','::1'];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = 'yii\gii\Module';
}

return $config;
