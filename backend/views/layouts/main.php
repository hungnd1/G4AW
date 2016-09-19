<?php
use backend\assets\AppAsset;
use common\models\User;
use common\widgets\Alert;
use common\widgets\Nav;
use common\widgets\NavBar;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);

\common\assets\ToastAsset::register($this);
\common\assets\ToastAsset::config($this, [
    'positionClass' => \common\assets\ToastAsset::POSITION_TOP_RIGHT
]);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="page-container-bg-solid page-boxed page-md">
<?php $this->beginBody() ?>
<div class="page-header">
<!-- BEGIN HEADER TOP -->
<div class="container">
    <?php

    NavBar::begin([
        'brandLabel' => '<img src="' . Url::to("@web/img/logo-big.png") . '" alt="logo" class="logo-default"/>',
        'brandUrl' => Yii::$app->homeUrl,
        'brandOptions' => [
            'class' => 'page-logo'
        ],
        'renderInnerContainer' => true,
        'innerContainerOptions' => [
            'class' => 'container-fluid'
        ],
        'options' => [
            'class' => 'page-header-top',
        ],
        'containerOptions' => [
            'class' => 'top-menu'
        ],
    ]);
    $rightItems = [];
    if (Yii::$app->user->isGuest) {

    } else {

        $rightItems[] = [
            'encode' => false,
            'label' => '<img alt="" class="img-circle" src="' . Url::to("@web/img/avatar0.jpg") . '"/>
					<span class="username username-hide-on-mobile">
						 ' . Yii::$app->user->identity->username . '
					</span>',
            'options' => ['class' => 'dropdown dropdown-user dropdown-dark'],
            'linkOptions' => [
                'data-hover' => "dropdown",
                'data-close-others' => "true"
            ],
            'url' => 'javascript:;',
            'items' => [

                [
                    'encode' => false,
                    'label' => '<i class="icon-key"></i> Đăng xuất',
                    'url' => ['/site/logout'],
                    'linkOptions' => ['data-method' => 'post'],
                ],
            ]
        ];
    }

    echo Nav::widget([
        'options' => ['class' => 'navbar-nav pull-right'],
        'items' => $rightItems,
        'activateParents' => true
    ]);
    echo \common\widgets\Badge::widget([]);

    NavBar::end();
    ?>
</div>
<!-- END HEADER TOP -->

<?php
NavBar::begin([
    'renderInnerContainer' => true,
    'innerContainerOptions' => [
        'class' => 'container '
    ],
    'options' => [
        'class' => 'page-header-menu green',
        'style' => 'display: block; background:#32d3c3;border-bottom: solid 4px #1baa9c;'
    ],
    'containerOptions' => [
        'class' => 'hor-menu'
    ],
    'toggleBtn' => false
]);

$menuItems = [
    [
        'label' => 'QL Chiến dịch',
        'url' => 'javascript:;',
        'options' => ['class' => 'menu-dropdown mega-menu-dropdown'],
        'linkOptions' => ['data-hover' => 'megamenu-dropdown', 'data-close-others' => 'true'],
        'items' => [
            [
                'encode' => false,
                'label' => 'Danh sách chiến dịch',
                'url' => ['campaign/index'],
            ],
            [
                'encode' => false,
                'label' => 'Phát triển cộng đồng',
                'url' => ['campaign/create'],
            ],
            [
                'encode' => false,
                'label' => 'Những tấm lòng nhân ái',
                'url' => ['donation-request/index'],
            ],
            [
                'encode' => false,
                'label' => 'Danh mục ủng hộ',
                'url' => ['donation-item/index'],
            ],
        ]
    ],
    [
        'label' => 'QL xã',
        'url' => ['village/index']
    ],
    [
        'label' => 'QL doanh nghiệp đỡ đầu',
        'url' => ['lead-donor/index']
    ],
//        [
//            'label' => 'Quản lý xã',
//            'url' => 'javascript:;',
//            'options' => ['class' => 'menu-dropdown mega-menu-dropdown'],
//            'linkOptions' => ['data-hover' => 'megamenu-dropdown', 'data-close-others' => 'true'],
//            'items' => [
//                [
//                    'encode' => false,
//                    'label' => 'Quản lý xã',
//                    'url' => ['village/index'],
//                ],
//                [
//                    'encode' => false,
//                    'label' => 'Xã của tôi',
//                    'url' => ['village/view_my_village',"id" => Yii::$app->user->identity->village_id],
//                ],
//                [
//                    'encode' => false,
//                    'label' => 'Quản lý doanh nghiệp đỡ đầu',
//                    'url' => ['lead-donor/index'],
//                ],
//                [
//                    'encode' => false,
//                    'label' => 'Doanh nghiệp của tôi',
//                    'url' => ['lead-donor/view_my_lead_donor',"id" => Yii::$app->user->identity->lead_donor_id],
//                ],
//            ]
//        ],

    [
        'label' => 'QL Tin tức',
        'url' => 'javascript:;',
        'options' => ['class' => 'menu-dropdown mega-menu-dropdown'],
        'linkOptions' => ['data-hover' => 'megamenu-dropdown', 'data-close-others' => 'true'],
        'items' => [
            [
                'encode' => false,
                'label' => 'Danh mục tin tức',
                'url' => ['category/index'],
            ],
            [
                'encode' => false,
                'label' => 'Tin tức chung',
                'url' => ['news/index', 'type' => \common\models\News::TYPE_COMMON],
            ],
            [
                'encode' => false,
                'label' => 'Tin tức chiến dịch',
                'url' => ['news/index', 'type' => \common\models\News::TYPE_CAMPAIGN],
            ],
            [
                'encode' => false,
                'label' => 'Tin tức về DN đỡ đầu',
                'url' => ['news/index', 'type' => \common\models\News::TYPE_DONOR],
            ],
            [
                'encode' => false,
                'label' => 'Tin tức về xã',
                'url' => 'javascript:;',
                'options' => ['class' => 'menu-dropdown mega-menu-dropdown'],
                'linkOptions' => ['data-hover' => 'megamenu-dropdown', 'data-close-others' => 'true'],
                'items' => [
                    [
                        'encode' => false,
                        'label' => 'Ý tưởng',
                        'url' => ['news/index', 'type' => \common\models\News::TYPE_IDEA],
                    ],
                    [
                        'encode' => false,
                        'label' => 'Giao thương',
                        'url' => ['news/index', 'type' => \common\models\News::TYPE_TRADE],
                    ],
                ]
            ],
        ]
    ],
//        [
//            'label' => 'Thống kê',
//            'url' => ['report/donation']
//        ],
    [
        'label' => 'QL Tài khoản',
        'url' => 'javascript:;',
        'options' => ['class' => 'menu-dropdown mega-menu-dropdown'],
        'linkOptions' => ['data-hover' => 'megamenu-dropdown', 'data-close-others' => 'true'],
        'items' => [
            [
                'encode' => false,
                'label' => User::getTypeNameByID(User::TYPE_ADMIN),
                'url' => ['user/index', "type" => User::TYPE_ADMIN],
            ],
            [
                'encode' => false,
                'label' => User::getTypeNameByID(User::TYPE_MANAGER),
                'url' => ['user/index', "type" => User::TYPE_MANAGER],
            ],
            [
                'encode' => false,
                'label' => User::getTypeNameByID(User::TYPE_LEAD_DONOR),
                'url' => ['user/index', "type" => User::TYPE_LEAD_DONOR],
            ],
            [
                'encode' => false,
                'label' => User::getTypeNameByID(User::TYPE_VILLAGE),
                'url' => ['user/index', "type" => User::TYPE_VILLAGE],
            ],
            [
                'encode' => false,
                'label' => User::getTypeNameByID(User::TYPE_MINISTRY_EDITOR),
                'url' => ['user/index', "type" => User::TYPE_MINISTRY_EDITOR],
            ],
            [
                'encode' => false,
                'label' => User::getTypeNameByID(User::TYPE_USER),
                'url' => ['user/index', "type" => User::TYPE_USER],
            ],
        ]
    ],
    [
        'label' => 'QL Phân quyền',
        'url' => 'javascript:;',
        'options' => ['class' => 'menu-dropdown mega-menu-dropdown'],
        'linkOptions' => ['data-hover' => 'megamenu-dropdown', 'data-close-others' => 'true'],
        'items' => [
//                [
//                    'encode' => false,
//                    'label' => 'Quản lý người dùng',
//                    'url' => ['user/index'],
//                ],
//            [
//                'encode' => false,
//                'label' => 'Quản lý quyền',
//                'url' => ['rbac-backend/permission'],
//            ],
            [
                'encode' => false,
                'label' => 'Quản lý nhóm quyền',
                'url' => ['rbac-backend/role'],
            ],
        ]
    ],
];
echo Nav::widget([
    'options' => ['class' => 'navbar-nav'],
    'items' => $menuItems,
    'activateParents' => true
]);
NavBar::end();
?>


</div>

<style>
    .hor-menu ul li {
        border-left: solid 1px rgba(255, 255, 255, 0.3);
    }

    .hor-menu li:last-child {
        border-right: solid 1px rgba(255, 255, 255, 0.3);
    }

    .hor-menu ul li a {
        color: white !important;
    }

    .hor-menu ul li a:hover {
        background-color: #1baa9c !important;
    }

</style>
<!-- BEGIN CONTAINER -->
<div class="page-container">

    <div class="page-content-wrapper">
        <!-- BEGIN PAGE CONTENT BODY -->
        <div class="page-content">
            <div class="container-fluid">
                <?= Breadcrumbs::widget([
                    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                    'options' => [
                        'class' => 'page-breadcrumb breadcrumb'
                    ],
                    'itemTemplate' => "<li>{link}<i class=\"fa fa-circle\"></i></li>\n",
                    'activeItemTemplate' => "<li class=\"active\">{link}</li>\n"
                ]) ?>
                <?= Alert::widget() ?>
                <?= \common\widgets\Toast::widget(['view' => $this]) ?>
                <div class="page-content-inner">
                    <?= $content ?>
                </div>
            </div>
        </div>
        <!-- END PAGE CONTENT BODY -->
    </div>
</div>
<!-- END CONTAINER -->

<!-- BEGIN FOOTER -->
<div class="page-footer">
    <div class="container-fluid">
        <p><b>&copy;Copyright <?php echo date('Y'); ?> </b>. vnDonor-CMS by VIVAS Co.,Ltd.</p>
    </div>
</div>
<div class="scroll-to-top">
    <i class="icon-arrow-up"></i>
</div>
<!-- END FOOTER -->

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
