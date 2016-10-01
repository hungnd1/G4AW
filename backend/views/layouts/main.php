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
        'label' => 'Quản lý chung',
        'url' => 'javascript:;',
        'options' => ['class' => 'menu-dropdown mega-menu-dropdown'],
        'linkOptions' => ['data-hover' => 'megamenu-dropdown', 'data-close-others' => 'true'],
        'items' => [
//            [
//                'encode' => false,
//                'label' => 'Quản lý vùng',
//                'url' => ['area/index'],
//            ],
            [
                'encode' => false,
                'label' => 'Quản lý tỉnh',
                'url' => ['province/index'],
            ],
            [
                'encode' => false,
                'label' => 'Quản lý xã',
                'url' => ['village/index'],
            ],
            [
                'encode' => false,
                'label' => 'Quản lý đơn vị liên kết',
                'url' => ['unit-link/index'],
            ],
        ]

    ],
    [
        'label' => 'QL Tin tức',
        'url' => 'javascript:;',
        'options' => ['class' => 'menu-dropdown mega-menu-dropdown'],
        'linkOptions' => ['data-hover' => 'megamenu-dropdown', 'data-close-others' => 'true'],
        'items' => [
            [
                'encode' => false,
                'label' => 'Danh mục tin tức',
                'url' => ['category/index','type' => \common\models\News::TYPE_NEW],
            ],
            [
                'encode' => false,
                'label' => 'Quản lý tin tức',
                'url' => ['news/index', 'type' => \common\models\News::TYPE_NEW],
            ],
            [
                'encode' => false,
                'label' => 'Quản lý trang giới thiệu',
                'url' => ['introduction/index'],
            ],
        ]
    ],

    [
        'label' => 'Nhà nông nên biết',
        'url' => 'javascript:;',
        'options' => ['class' => 'menu-dropdown mega-menu-dropdown'],
        'linkOptions' => ['data-hover' => 'megamenu-dropdown', 'data-close-others' => 'true'],
        'items' => [
            [
                'encode' => false,
                'label' => 'Danh mục nhà nông',
                'url' => ['category/index','type' => \common\models\News::TYPE_KNOW],
            ],
            [
                'encode' => false,
                'label' => 'Tin tức nhà nông',
                'url' => ['news/index', 'type' => \common\models\News::TYPE_KNOW],
            ],
        ]
    ],
    [
        'label' => 'QL Video hướng dẫn',
        'url' => ['news/index', 'type' => \common\models\News::TYPE_VIDEO],
        'options' => ['class' => 'menu-dropdown mega-menu-dropdown'],
        'linkOptions' => ['data-hover' => 'megamenu-dropdown', 'data-close-others' => 'true'],
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
                'label' => User::getTypeNameByID(User::TYPE_MINISTRY_EDITOR),
                'url' => ['user/index', "type" => User::TYPE_MINISTRY_EDITOR],
            ],
            [
                'encode' => false,
                'label' => User::getTypeNameByID(User::TYPE_VILLAGE),
                'url' => ['user/index', "type" => User::TYPE_VILLAGE],
            ],
            [
                'encode' => false,
                'label' => User::getTypeNameByID(User::TYPE_USER),
                'url' => ['user/index', "type" => User::TYPE_USER],
            ],

        ]
    ],
    [
        'label' => 'QL Bình luận',
        'url' => 'javascript:;',
        'options' => ['class' => 'menu-dropdown mega-menu-dropdown'],
        'linkOptions' => ['data-hover' => 'megamenu-dropdown', 'data-close-others' => 'true'],
        'items' => [
//                [
//                    'encode' => false,
//                    'label' => 'Quản lý người dùng',
//                    'url' => ['user/index'],
//                ],
            [
                'encode' => false,
                'label' => 'Bình luận tin tức',
                'url' => ['comment/index','type'=>\common\models\Comment::TYPE_NEW],
            ],
            [
                'encode' => false,
                'label' => 'Bình luận xã',
                'url' => ['comment/index','type'=>\common\models\Comment::TYPE_VILLAGE],
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
            [
                'encode' => false,
                'label' => 'Quản lý quyền',
                'url' => ['rbac-backend/permission'],
            ],
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
        <p><b>&copy;Copyright <?php echo date('Y'); ?> </b>. G4AW-CMS by hungnd .,Ltd.</p>
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
