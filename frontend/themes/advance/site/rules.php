<?php
/**
 * Created by PhpStorm.
 * User: HungChelsea
 * Date: 09-Aug-16
 * Time: 4:08 PM
 */
use yii\helpers\Url;
?>
<!-- content -->
<div class="content">
    <div class="container">
        <div class="cr-page-link">
            <a href="<?= Url::toRoute(['site/index']) ?>">Trang chủ</a>
            <span>/</span>
            <a href="<?= Url::toRoute(['site/rules']) ?>">Nội quy</a>
        </div>
    </div>
    <div class="container">
        <div class="main-cm-1">
            <div class="list-noi-quy">
                <div class="box-nq">
                    <a href="<?=Yii::$app->getUrlManager()->getBaseUrl()?>/img/HUONGDANSUDUNG.pdf">
                        <i class="fa fa-file-text" aria-hidden="true"></i><br>
                        HƯỚNG DẪN SỬ DỤNG
                    </a>
                </div>
                <div class="box-nq">
                    <a href="<?=Yii::$app->getUrlManager()->getBaseUrl()?>/img/QUYCHESUDUNG.pdf">
                        <i class="fa fa-list" aria-hidden="true"></i><br>
                        QUY CHẾ SỬ DỤNG
                    </a>
                </div>
                <div class="box-nq">
                    <a href="<?=Yii::$app->getUrlManager()->getBaseUrl()?>/img/CHETAIXUPHAT.pdf">
                        <i class="fa fa-exclamation-triangle" aria-hidden="true"></i><br>
                        CHẾ TÀI XỬ PHẠT
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end content -->
