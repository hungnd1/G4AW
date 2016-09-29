<?php
/**
 * Created by PhpStorm.
 * User: Linh
 * Date: 19/03/2016
 * Time: 2:39 PM
 */
use frontend\helpers\UserHelper;
use yii\helpers\Html;
use yii\helpers\Url;
/** @var $content \common\models\News */
/** @var $otherModels \common\models\News[] */
?>
<!-- common page-->

<div class="l-related">
    <div class="thumb-common">
        <img class="blank-img" src="../img/blank.gif">
        <a href="<?= Url::toRoute(['news/detail','id'=>$content->id]) ?>"><img class="thumb-cm" src="<?= $content->getThumbnailLink() ?>"></a>
    </div>
    <div class="l-i-rl">
        <h4><a href="<?= Url::toRoute(['news/detail','id'=>$content->id]) ?>"><?= UserHelper::multilanguage($content->title,$content->title_en) ?></a></h4>
        <p><?= str_replace(mb_substr(UserHelper::multilanguage($content->short_description,$content->short_description_en), 150, strlen(UserHelper::multilanguage($content->short_description,$content->short_description_en)), 'utf-8'), '...', UserHelper::multilanguage($content->short_description,$content->short_description_en)) ?></p>
    </div>
</div>
<!-- end common page-->
