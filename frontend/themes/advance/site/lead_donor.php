<?php
/**
 * Created by PhpStorm.
 * User: HungChelsea
 * Date: 18-Aug-16
 * Time: 2:49 PM
 */
use yii\helpers\Url;

?>

<div class="content">
    <div class="container">
        <div class="cr-page-link">
            <a href="<?= Url::toRoute(['site/index']) ?>">Trang chủ</a>
            <span>/</span>
            <a href="">Danh sách đơn vị tài trợ</a>
        </div>
    </div>
    <div class="container">
        <div class="main-cm-1">
            <div class="block-list-dv">
                <h2>Các đơn vị tài trợ</h2>
                <ul class="list-dv-donor">
                    <?php if(isset($listDonor) && !empty($listDonor)) {
                        foreach($listDonor as $item){
                            /** @var $item \common\models\LeadDonor */
                            ?>
                            <li>
                                <a href="<?=Url::toRoute(['donor/view','id'=>$item->id]) ?>">
                                    <img src="<?= $item->getImageLink() ?>">
                                    <h3><?= $item->name ?></h3>
                                </a>
                            </li>
                        <?php }
                    }else{ ?>
                    <li>
                        <a href="">
                            <img src="<?=Yii::$app->getUrlManager()->getBaseUrl()?>/img/p1.png">
                            <h3>VTV</h3>
                        </a>
                    </li>
                    <li>
                        <a href="">
                            <img src="<?=Yii::$app->getUrlManager()->getBaseUrl()?>/img/p2.png">
                            <h3>VnExpress</h3>
                        </a>
                    </li>
                    <li>
                        <a href="">
                            <img src="<?=Yii::$app->getUrlManager()->getBaseUrl()?>/img/p3.png">
                            <h3>FPT Soft</h3>
                        </a>
                    </li>
                    <li>
                        <a href="">
                            <img src="<?=Yii::$app->getUrlManager()->getBaseUrl()?>/img/p4.png">
                            <h3>VNPT</h3>
                        </a>
                    </li>
                    <li>
                        <a href="">
                            <img src="<?=Yii::$app->getUrlManager()->getBaseUrl()?>/img/p5.png">
                            <h3>Viettel</h3>
                        </a>
                    </li>
                    <?php }?>
                </ul>
            </div>
        </div>
    </div>
</div>
<!-- end content -->

