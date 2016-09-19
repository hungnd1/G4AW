<?php
/**
 * Created by PhpStorm.
 * User: Linh
 * Date: 19/03/2016
 * Time: 2:39 PM
 */
use yii\helpers\Html;
use yii\helpers\Url;
/** @var $model \common\models\News */
/** @var $otherModels \common\models\News[] */
?>
<!-- common page-->
<div class="l-related">
    <div class="thumb-common">
        <img class="blank-img" src="../img/blank.gif">
        <a href="<?= Url::toRoute(['campaign/view','id'=>$content->id]) ?>"><img class="thumb-cm" src="<?= $content->thumbnail ?>"></a>
    </div>
    <div class="l-i-rl">
        <h4><a href="<?= Url::toRoute(['campaign/view','id'=>$content->id]) ?>"><?= $content->name ?></a></h4>
        <p>Thuộc xã: <span><?= $content->village_name ?></span></p>
        <p>Tỉ lệ đóng góp: <span><?= $content->status ?>%</span></p>
    </div>
</div>
<!-- end common page-->
