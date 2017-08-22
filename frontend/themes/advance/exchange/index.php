<?php
/**
 * Created by PhpStorm.
 * User: Linh
 * Date: 14/03/2016
 * Time: 11:13 PM
 */
use common\models\TypeCoffee;
use kartik\form\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/** @var $model \common\models\Exchange */
/** @var $model_exchange \common\models\ExchangeBuy */
?>
<!-- common page-->

<div class="content">
    <div class="container">
        <div class="cr-page-link">
            <a href="<?= Url::toRoute(['site/index']) ?>">Trang chủ</a>
            <span>/</span>
            <a href="<?= Url::toRoute(['user/my-page', 'id' => Yii::$app->user->identity->id]) ?>">Cá nhân</a>
        </div>
    </div>
    <div class="container">
        <div class="main-cm-1">
            <div class="left-cn hidden-xs hidden-sm">
                <div class="block-cm-left top-cn-left">
                    <a href="<?= Url::toRoute(['user/update', 'id' => $user->id]) ?>" class="bt-edit"><i
                            class="fa fa-pencil"></i></a>
                    <img src="<?= $user->getImageLink() ?>"><br>
                    <h4><?= $user->username ?></h4>
                    <p><?= $user->address ?></p>
                </div>
                <div class="block-cm-left">
                    <span class="t-span">Số điện thoại</span><br>
                    <span class="b-span"><?= $user->username ?></span>
                </div>
                <div class="block-cm-left">
                    <span class="t-span">Email</span><br>
                    <span class="b-span"><?= $user->email ?></span>
                </div>
                <div class="block-cm-left">
                    <span class="t-span">Giới tính</span><br>
                    <?= \common\models\User::getGenderName($user->sex) ?>
                </div>
                <div class="block-cm-left">
                    <span class="t-span">Tuổi</span><br>
                    <span class="b-span"><?= \common\models\User::getOld($user->birthday) ?></span>
                </div>
            </div>
            <div class="right-cn">
                <div class="box-creat-rq">
                    <h4>ĐĂNG KÝ ĐỂ BÁN HOẶC MUA COFFEE</h4>
                </div>
                <div class="tab-ct">
                    <!-- Nav tabs -->
                    <div class="out-ul-tab ">
                        <ul class="nav nav-tabs" role="tablist">
                            <li role="presentation" class="active"><a href="#t1" aria-controls="" role="tab"
                                                                      data-toggle="tab">Cần bán</i></a></li>
                            <li role="presentation"><a href="#t2" aria-controls="" role="tab" data-toggle="tab">Cần
                                    mua</a></li>
                        </ul>
                    </div>
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane active" id="t1">
                            <div class="list-item list-tab-2">
                                <div class="list-field">
                                    <?php $form = ActiveForm::begin([
                                        'options' => ['enctype' => 'multipart/form-data'],
                                        'id' => 'gdgd',
                                        'action'=>'exchange-sold',
                                        'enableAjaxValidation' => false,
                                        'enableClientValidation' => true,
                                    ]); ?>
                                    <div class="l-f-1">
                                        <?= $form->field($model, 'total_quality_id')->textInput(['maxlength' => true,'class' => 'input-circle'])->label('Tổng sản lượng bán (tấn) (*)') ?>
                                    </div>
                                    <div class="l-f-1">
                                        <?= $form->field($model, 'sold_id')->textInput(['maxlength' => true])->label('Sản lượng đã bán (tấn) (*)') ?>
                                    </div>
                                    <div class="l-f-1">
                                        <?php $data = ArrayHelper::map(TypeCoffee::find()->asArray()->all(), 'id', 'name') ?>
                                        <?= $form->field($model, 'type_coffee')->dropDownList(
                                            $data
                                        ) ?>
                                    </div>
                                    <div class="l-f-1">
                                        <?= $form->field($model, 'price')->textInput(['maxlength' => true])->label('Giá bán (VNĐ/kg) (*)') ?>
                                    </div>
                                    <div class="l-f-1">
                                        <?= $form->field($model, 'location_name')->textInput(['maxlength' => true])->label('Địa điểm giao dịch') ?>
                                    </div>
                                    <div class="line-bt" style="width:100px">
                                        <button class="bt-common-1">Bán</button>
                                    </div>
                                    <?php ActiveForm::end(); ?>
                                </div>
                            </div>
                        </div>
                        <div role="tabpanel" class="tab-pane" id="t2">
                            <div class="list-item list-tab-4">
                                <div class="list-field">
                                    <?php $form = ActiveForm::begin([
                                        'options' => ['enctype' => 'multipart/form-data'],
                                        'id' => 'gdgd',
                                        'action'=>'exchange-buy',
                                        'enableAjaxValidation' => false,
                                        'enableClientValidation' => true,
                                    ]); ?>
                                    <div class="l-f-1">
                                        <?= $form->field($model_exchange, 'total_quantity')->textInput(['maxlength' => true,'class' => 'input-circle'])->label('Tổng sản lượng mua (tấn) (*)') ?>
                                    </div>
                                    <div class="l-f-1">
                                        <?php $data = ArrayHelper::map(TypeCoffee::find()->asArray()->all(), 'id', 'name') ?>
                                        <?= $form->field($model_exchange, 'type_coffee_id')->dropDownList(
                                            $data
                                        ) ?>
                                    </div>
                                    <div class="l-f-1">
                                        <?= $form->field($model_exchange, 'price_buy')->textInput(['maxlength' => true])->label('Giá mua (VNĐ/kg) (*)') ?>
                                    </div>
                                    <div class="l-f-1">
                                        <?= $form->field($model_exchange, 'location_name')->textInput(['maxlength' => true])->label('Địa điểm giao dịch') ?>
                                    </div>
                                    <div class="line-bt" style="width:100px">
                                        <button class="bt-common-1">Mua</button>
                                    </div>
                                    <?php ActiveForm::end(); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<!-- end common page-->
