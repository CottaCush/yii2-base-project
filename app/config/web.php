<?php

$config = [
    'id' => 'yii2_basic_template',
    'name' => 'Yii2 Basic Template',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'fwcW1zcfS3R2ks4aiUj28coB2vhWujfl9NGktEnG',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => getenv('SMTP_HOST'),
                'username' => getenv('SMTP_USERNAME'),
                'password' => getenv('SMTP_PASSWORD'),
                'port' => getenv('SMTP_PORT'),
                'encryption' => 'tls',
            ],
            'useFileTransport' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => require('db.php'),
        'urlManager' => [
            'enablePrettyUrl' => true,
            'enableStrictParsing' => false,
            'showScriptName' => false,
            'rules' => [

            ]
        ],
        'assetManager' => [
            'appendTimestamp' => true
        ],
        'view' => [
            'class' => '\smilemd\htmlcompress\View',
            'compress' => YII_ENV_DEV ? false : true,
        ],
        's3bucket' => [
            'class' => \frostealth\yii2\aws\s3\Storage::className(),
            'region' => 'eu-west-1',
            'credentials' => [
                'key' => getenv('AWS_KEY'),
                'secret' => getenv('AWS_SECRET'),
            ],
            'bucket' => getenv('APPLICATION_ENV') . '.yii2baseproject',
            'defaultAcl' => \frostealth\yii2\aws\s3\Storage::ACL_PUBLIC_READ,
            'debug' => false,
        ],
        'objectStorage' => [
            'class' => \CottaCush\Yii2\File\ObjectStorageComponent::class,
            'objectStorageClientClass' => \CottaCush\Yii2\File\FrostealthS3ObjectStorageClient::class
        ]
    ],
    'params' => require('params.php'),
    'modules' => [
        'admin' => [
            'class' => 'app\modules\admin\Module',
        ],
    ],
    'vendorPath' => dirname(__DIR__) . "/../vendor"
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;
