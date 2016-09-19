<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 11/8/2016
 * Time: 5:26 PM
 */
use common\models\LeadDonor;
use common\models\Village;
use yii\helpers\Url;

?>
<div class="content">
    <div class="container">
        <div class="cr-page-link">
            <a href="<?= Url::toRoute(['site/index']) ?>">Trang chủ</a>
            <span>/</span>
            <a href="<?= Url::toRoute(['campaign/list-campaign']) ?>">Chương trình phát triển</a>
        </div>
    </div>
    <div class="container">
        <div class="main-cm-1">
            <div class="main-ct">
                <h1>Chương trình phát triển</h1>
                <div class="list-item list-item-sp">
                    <?php
                    if(isset($listCampaignDonor)) {
                        foreach ($listCampaignDonor as $item) {
                            ?>
                            <div class="out-card">
                                <div class="card-item">
                                    <div class="thumb-common">
                                        <img src="../img/blank.gif">
                                        <a href="<?= Url::toRoute(['campaign/view','id'=>$item->id])?>"><img class="thumb-cm" src="<?= $item->thumbnail ?>"><br></a>
                                    </div>
                                    <div class="if-cm-1">
                                        <div class="top-cp">
                                            <a href="<?= Url::toRoute(['campaign/view','id'=>$item->id])?>"><h3 class="name-1"><?= $item->name ?></h3><br></a>
                                        </div>
                                        <a href="<?= Url::toRoute(['village/view','id_village'=>$item->village_id]) ?>" class="by-xa">
                                            <span>
                                                <?= $item->village_name ?>
                                            </span>
                                        </a>
                                        <div class="bt-cp">
                                            <a href="<?= Url::toRoute(['donor/view', 'id'=>$item->lead_id ])?>" class="logo-cp"><img src="<?= $item->image ?>"></a>
                                            <a href="<?= Url::toRoute(['donor/view', 'id'=>$item->lead_id ])?>"><h4><?= $item->lead_name ?></h4></a>
                                            <span class="add-cp"><?= $item->address ?></span>
                                            <div class="bar">
                                                <div class="bar-status" style="width:<?= $item->status ?>%;"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                    }
                    ?>
                </div>
                <?php
                $pagination = new \yii\data\Pagination(['totalCount' => $pages->totalCount,'pageSize' =>10]);
                echo \yii\widgets\LinkPager::widget([
                    'pagination' => $pagination,
                ]);
                ?>
            </div>
        </div>
    </div>
</div>