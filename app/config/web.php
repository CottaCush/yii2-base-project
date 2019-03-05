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
            'appendTimestamp' => true,
            'bundles' => [
                'yii\web\JqueryAsset' => [
                    'sourcePath' => '@npm/jquery/dist',
                    'js' => [
                        YII_ENV_DEV ? 'jquery.js' : '//cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js'
                    ]
                ],
                'yii\bootstrap\BootstrapAsset' => [
                    'sourcePath' => '@npm/bootstrap/dist',
                    'css' => [
                        YII_ENV_DEV ? 'css/bootstrap.css' :
                            '//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css',
                    ]
                ],
                'yii\bootstrap\BootstrapPluginAsset' => [
                    'sourcePath' => '@npm/bootstrap/dist',
                    'js' => [
                        YII_ENV_DEV ? 'js/bootstrap.js' :
                            '//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js',
                    ]
                ]
            ],
        ],
        'view' => [
            'class' => '\ogheo\htmlcompress\View',
            'compress' => YII_ENV_DEV ? false : true,
        ]
    ],
    'params' => require('params.php'),
    'modules' => [
        'admin' => [
            'class' => 'app\modules\admin\Module',
        ],
    ],
    'vendorPath' => dirname(__DIR__) . "/../vendor",
    'aliases' => [
        '@npm'   => dirname(__DIR__) . "/../node_modules",
    ]
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
