<?php
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'language' => 'vi',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
        ],

        'i18n' => [
            'translations' => [
                'app*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@common/messages',
                ],
                'frontend*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@common/messages',
                ],
                'backend*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@common/messages',
                ],
                'api*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@common/messages',
                ],
                'log*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@common/messages',
                ],
//                'zii*' => [
//                    'class' => 'yii\i18n\PhpMessageSource',
//                    'basePath' => '@common/messages',
////                    'forceTranslation' => true
//                ],
//                'kvgrid*' => [
                //                    'class' => 'yii\i18n\PhpMessageSource',
                //                    'basePath' => '@common/messages',
                ////                    'forceTranslation' => true
                //                ],
            ],
        ],
    ],
    'timeZone' => 'Asia/Ho_Chi_Minh',
];
