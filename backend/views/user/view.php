<?php

use common\models\User;
use kartik\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $active int */

$this->title = 'Chi tiết người dùng '.$model->username.' thuộc tài khoản '.$model->getTypeName();
$this->params['breadcrumbs'][] = ['label' => "Tài khoản " . $model->getTypeName(), 'url' => ['index', "type" => $model->type]];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-md-12">
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-cogs font-green-sharp"></i>
                    <span
                        class="caption-subject font-green-sharp bold uppercase"><?php echo $this->title ?></span>
                </div>
                <div class="tools">
                    <a href="javascript:;" class="collapse" data-original-title="" title="">
                    </a>

                </div>
            </div>
            <div class="portlet-body">
                <div class="tabbable-custom ">
                    <ul class="nav nav-tabs ">
                        <li class="<?= ($active == 1) ? 'active' : '' ?>">
                            <a href="#tab1" data-toggle="tab">
                                Thông tin chung</a>
                        </li>
                        <?php if (Yii::$app->user->identity->type == User::TYPE_ADMIN) { ?>
                            <li class=" <?= ($active == 2) ? 'active' : '' ?>">
                                <a href="#tab2" data-toggle="tab">
                                    Phân quyền </a>
                            </li>
                        <?php } ?>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane <?= ($active == 1) ? 'active' : '' ?>" id="tab1">
                            <?= $this->render('_detail', ['model' => $model]) ?>
                        </div>
                        <?php if (Yii::$app->user->identity->type == User::TYPE_ADMIN) { ?>
                            <div class="tab-pane <?= ($active == 2) ? 'active' : '' ?>" id="tab2">
                                <?= $this->render('_user_role', ['model' => $model]) ?>
                            </div>
                        <?php } ?>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>