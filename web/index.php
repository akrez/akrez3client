<?php

defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');
defined('BASE_PATH') or define('BASE_PATH', dirname(__DIR__));
defined('VENDOR_PATH') or define('VENDOR_PATH', BASE_PATH . '/vendor');
defined('APP_NAME') or define('APP_NAME', 'اکــرز');

require VENDOR_PATH . '/autoload.php';
require VENDOR_PATH . '/yiisoft/yii2/Yii.php';

$params = require(__DIR__ . '/../config/params.php');

if ($params['isParked']) {
    $rules = [
        '/site/gallery/<type:\w+>/<whq>/<name:[\w\.]+>' => 'site/gallery',
        '/<controller:[\w\-]+>/<action:[\w\-]+>/<id:\d+>' => '<controller>/<action>',
        '/<controller:[\w\-]+>/<action:[\w\-]+>' => '<controller>/<action>',
        '/<controller:[\w\-]+>' => '<controller>/index',
        '/' => 'site/index',
    ];
} else {
    $rules = [
        '/<_blog:[\w\-]+>/gallery/<type:\w+>/<whq>/<name:[\w\.]+>' => 'site/gallery',
        '/<_blog:[\w\-]+>/<action:[\w\-]+>/<id>' => 'site/<action>',
        '/<_blog:[\w\-]+>/<action:[\w\-]+>' => 'site/<action>',
        '/<_blog:[\w\-]+>' => 'site/index',
    ];
}

$config = [
    'id' => 'basic',
    'name' => APP_NAME,
    'basePath' => BASE_PATH,
    'language' => 'fa-IR',
    'controllerNamespace' => 'app\controllers',
    'bootstrap' => [
        'log',
    ],
    'vendorPath' => VENDOR_PATH,
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],
    'components' => [
        'db' => $params['db'],
        'cache' => [
            'class' => 'yii\caching\FileCache',
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
        'i18n' => [
            'translations' => [
                'app' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@app/messages',
                ],
            ],
        ],
        'request' => [
            'csrfParam' => '_csrf-blog-' . $params['blogName'],
            'cookieValidationKey' => $params['cookieValidationKey'],
            'baseUrl' => $params['baseUrl'],
        ],
        'session' => [
            'name' => 'basic-blog-' . $params['blogName'],
            'cookieParams' => [
                'httpOnly' => true,
            ],
        ],
        /*
          'user' => [
          'class' => 'yii\web\User',
          'identityClass' => 'app\models\User',
          'enableAutoLogin' => true,
          'identityCookie' => ['name' => '_identity-app', 'httpOnly' => true],
          ],
         */
        'blog' => [
            'class' => 'app\components\BlogContainer',
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'normalizer' => [
                'class' => 'yii\web\UrlNormalizer',
                'collapseSlashes' => true,
                'normalizeTrailingSlash' => true,
            ],
            'rules' => $rules,
        ],
        'formatter' => [
            'class' => 'app\components\Formatter',
        ],
        'view' => [
            'theme' => [
                'pathMap' => [
                    '@app/views' => '@app/views/' . $params['theme'],
                ],
            ],
        ],
    ],
    'params' => $params['params'],
];

if (YII_ENV == 'dev') {
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        'allowedIPs' => ['127.0.0.1', '::1'],
    ];
    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

(new yii\web\Application($config))->run();
