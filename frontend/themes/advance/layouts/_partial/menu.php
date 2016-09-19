<?php
/**
 * Created by PhpStorm.
 * User: Linh
 * Date: 17/12/2015
 * Time: 3:38 PM
 */
use yii\helpers\Html;
use frontend\widgets\NavBar;
use frontend\widgets\Nav;

$menuItems = [];
$menuItems[] = [
    'label' => 'Hướng dẫn',
    'url' => '#',
];
$menuItems[] = [
    'label' => 'Hỗ trợ',
    'url' => '#',
];
$menuItems[] = [
    'label' => 'Về chúng tôi',
    'url' => '#',
];
$menuItems[] = [
    'label' => 'Liên hệ',
    'url' => '#',
];

if(Yii::$app->user->isGuest){
    $menuItems[] = [
        'label' => '',
        'labelIcon' => 'fa fa-search',
        'url' => '#',
        'linkOptions' => [
            'data-toggle' => 'modal',
            'data-target'=>'#md-search'
        ],

    ];
    $menuItems[] = [
        'label' => 'Đăng nhập',
        'labelContainerTag'=>'span',
        'linkOptions' => [
            'data-toggle' => 'modal',
            'data-target'=>'#formLogin'
        ],
        'options'=>[
            'class'=>'sign-in'
        ],
        'url' => '#',
    ];
}else{
    $user =\common\models\User::findOne(Yii::$app->user->getId());
    $menuItems[] =[
        'label' =>'Trang cá nhân' ,
        'url' => ['/user/index'],
    ];
    $menuItems[] = [
        'label' => '',
        'labelIcon' => 'fa fa-search',
        'url' => '#',
        'linkOptions' => [
            'data-toggle' => 'modal',
            'data-target'=>'#md-search'
        ],

    ];
    $menuItems[] = [
        'label' => $user->username,

        'linkOptions' => [
            'data-toggle' => 'dropdown',
            'aria-expanded'=>'false',
            'aria-haspopup'=>'true'
        ],
        'options'=>[
            'class'=>'sign-in signed dropdown'
        ],
        'url' => '#',
        'items'=>[

            [
                'label' =>'Cài đặt tài khoản' ,
                'url' => ['/user/setting'],
            ],
            [
                'label' =>'Đăng xuất' ,

                'linkOptions' => [
                    'data-method' => 'post',
                ],
                'options'=>[

                ],
                'url' => ['/site/logout'],
            ]
        ]
    ];
}

NavBar::begin([
    'options' => [
        'class' => '',
    ],
    'containerOptions' => [
        'class' => '',
        'id' => 'navbar'
    ],
]);


echo Nav::widget([
    'options' => ['class' => 'nav navbar-nav nav-mb'],
    'activateParents' => true,
    'items' => $menuItems,
]);
NavBar::end();
?>
