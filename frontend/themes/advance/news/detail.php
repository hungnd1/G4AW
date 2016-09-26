<?php
/**
 * Created by PhpStorm.
 * User: HungChelsea
 * Date: 11-Aug-16
 * Time: 11:36 AM
 */
use kartik\form\ActiveForm;
use yii\helpers\Url;

/** @var $model  \common\models\News */
?>

<!-- content -->
<div class="content">
    <div class="main-cm-2">
        <div class="container">
            <div class="left-content">
                <div class="cr-page-link">
                    <a href="<?= Url::toRoute(['site/index']) ?>">Trang chủ</a>
                    <span>/</span>
                    <a href="<?= Url::toRoute(['news/index']) ?>"><?= $title ?></a>
                    <span>/</span>
                    <a href=""><?= $model->title ?></a>
                </div>
                <div class="m-content">
                    <h1><?= $model->title ?></h1>
                    <p class="des-dt"><?= $model->short_description ?></p>
                    <div class="content-dt">
                        <?= preg_replace('/(\<img[^>]+)(style\=\"[^\"]+\")([^>]+)(>)/', '${1}${3}${4}', $model->content) ?>
                    </div>
                    <div class="post-related">
                        <h2>Bình luận</h2>
                        <div class="top-box-comment">
                            <b>Bạn muốn chia sẻ?</b>
                            <?php $form = ActiveForm::begin([
                                'id' => 'comment-form'
                            ]); ?>
                            <div>
                                <textarea rows="4" id="comment"></textarea>
                            </div>
                            <div class="line-bottom-comment">
                                <span><i>Nhập ý kiến của bạn</i></span>
                                <a onclick="feedBack($(this));" class="send-comment">Gửi ý kiến</a><br><br>
                            </div>
                            <?php ActiveForm::end(); ?>
                        </div>
                        <div class="list-comments">
                            <div id="head-comment"></div>
                            <?php for ($i=0;$i<=4;$i++) {
                                    ?>
                                    <div class="comment-box-item">
                                        <img
                                            src="<?= Yii::$app->request->baseUrl . '/img/avt_df.png' ?>">
                                        <div class="left-comment">
                                            <h5 class="">a <span
                                                    class="time-up">aa</span>
                                            </h5>

                                            <p>aaa</p>
                                        </div>
                                    </div>
                                <?php } ?>
                            <div id="last-comment"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="right-content">
                <div class="block-related block-cm-2">
                    <h3>Có thể bạn quan tâm</h3>
                    <div class="list-related">
                        <?php if(isset($listNewRelated) && !empty($listNewRelated)){
                            foreach($listNewRelated as $item ) {?>
                                <?= \frontend\widgets\NewsWidget::widget(['content'=>$item]) ?>
                            <?php } }?>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<!-- end content -->
