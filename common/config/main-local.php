<?php
return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=feature.jios.org;port=33060;dbname=landsystem',
            'username' => 'landsystem',
            'password' => 'landsystem',
            'charset' => 'utf8',
			'tablePrefix' => 'land_',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
		'authManager' => [
 			'class' => 'yii\rbac\DbManager',
 			'itemTable' => '{{%auth_item}}',
			'assignmentTable' => '{{%auth_assignment}}',
 			'itemChildTable' => '{{%auth_item_child}}',
 		],
    ],
];
