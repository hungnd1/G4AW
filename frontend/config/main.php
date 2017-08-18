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
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'language' => 'vi-VN',
    'aliases' => [
        '@news_image' => 'static/news',
        '@uploads' => 'uploads',
        '@area_image' => 'area_image',
        '@images' => 'images',
        '@file_upload'=>'file_upload',
        '@village_image'=>'village_image',
        '@avatar'=>'static/avatar',
        '@news_video'=>'news_video',
        '@unit_link'=>'static/unit_link'
    ],
    'components' => [
        'user' => [
            'identityClass' => 'common\models\Subscriber',
            'enableAutoLogin' => true,
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
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],

        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,

        ],

        'view' => [
            'theme' => [
                'pathMap' => [
                    '@app/views' => '@app/themes/advance',
                    '@app/widgets' => '@app/themes/advance/widgets',
                ],
                'baseUrl' => '@web/themes/advance',

            ],
        ],
        'reCaptcha' => [
            'name' => 'reCaptcha',
            'class' => 'himiklab\yii2\recaptcha\ReCaptcha',
            'siteKey' => '6LdzoCcTAAAAAMxwj0EJtGD5p-1pl-np-ZjjrBK9',
            'secret' => '6LdzoCcTAAAAAD8kmxx8ZRpu_c-edCw_tqf-5xnv',
        ],
    ],
    'params' => $params,
];
