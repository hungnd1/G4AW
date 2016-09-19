<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use common\auth\filters\Yii2Auth;
use common\models\User;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $type */
/* @var $searchModel common\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Quản lý tài khoản';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-md-12">
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-cogs font-green-sharp"></i>
                    <span
                        class="caption-subject font-green-sharp bold uppercase">Quản lý tài khoản <?= User::getTypeNameByID($type) ?> </span>
                </div>
                <div class="tools">
                    <a href="javascript:;" class="collapse">
                    </a>
                </div>
            </div>
            <div class="portlet-body">
                <p>
                    <?php
                        if(Yii::$app->user->identity->type == User::TYPE_ADMIN) {
                            if($type != User::TYPE_USER) {
                                echo Html::a('Tạo mới người dùng', ['create', 'type' => $type], ['class' => 'btn btn-success']);
                            }
                        }
                    ?>
                </p>

                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'formatter' => ['class' => 'yii\i18n\Formatter','nullDisplay' => ''],
                    'columns' => [
                        [
                            'attribute' => 'username',
                            'format' => 'raw',
                            'value' => function ($model, $key, $index, $widget) {
                                /**
                                 * @var $model \common\models\User
                                 */
                                $action = "user/view";
                                $res = Html::a('<kbd>' . $model->username . '</kbd>', [$action, 'id' => $model->id]);
                                return $res;

                            },
                        ],
                        'email:email',
                        [
                            'class' => '\kartik\grid\DataColumn',
                            'attribute' => 'status',
                            'label' => 'Trạng thái',
                            'format' => 'raw',
                            'value' => function ($model, $key, $index, $widget) {
                                /**
                                 * @var $model \common\models\User
                                 */
                                if ($model->status == User::STATUS_ACTIVE) {
                                    return '<span class="label label-success">' . $model->getStatusName() . '</span>';
                                } else {
                                    return '<span class="label label-danger">' . $model->getStatusName() . '</span>';
                                }

                            },
                            'filter' => User::listStatus(),
                            'filterType' => GridView::FILTER_SELECT2,
                            'filterWidgetOptions' => [
                                'pluginOptions' => ['allowClear' => true],
                            ],
                            'filterInputOptions' => ['placeholder' => "Tất cả"],
                        ],
                        [
                            'format' => 'html',
                            'label' => 'Quyền người dùng',
//                            'visible' => $type == User::TYPE_ADMIN ? true : false,
                            //                'vAlign' => 'middle',
                            'value' => function ($model, $key, $index, $widget) {
                                /**
                                 * @var $model \common\models\User
                                 */
                                $e = new Yii2Auth();
                                if ($e->superAdmin != $model->username) {
                                    return $model->getRolesName();
                                } else {
                                    return "Supper Admin";
                                }
                            },
                        ],

                        ['class' => 'yii\grid\ActionColumn',
                            'template' => '{view}{update}{delete}',
                            'buttons' => [
                                'view' => function ($url, $model) {
                                    return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', Url::toRoute(['user/view', 'id' => $model->id]), [
                                        'title' => 'Thông tin người dùng',
                                    ]);

                                },
                                'update' => function ($url, $model) {
                                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', Url::toRoute(['user/update', 'id' => $model->id]), [
                                        'title' => 'Cập nhật thông tin người dùng',
                                    ]);
                                },
                                'delete' => function ($url, $model) {
//                        Nếu là chính nó thì không cho thay đổi trạng thái
                                    if ($model->id != Yii::$app->user->getId()) {
                                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', Url::toRoute(['user/delete', 'id' => $model->id]), [
                                            'title' => 'Xóa người dùng',
                                            'data-confirm' => "Bạn có chắc chắn muốn xóa tài khoản này?",
                                            'data-method' => 'post',
                                        ]);
                                    }
                                }
                            ]
                        ],
                    ],
                ]); ?>

            </div>
        </div>
    </div>
</div>