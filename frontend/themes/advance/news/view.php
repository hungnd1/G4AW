<?php
/**
 * Created by PhpStorm.
 * User: Linh
 * Date: 19/03/2016
 * Time: 2:39 PM
 */
use yii\helpers\Html;
use yii\helpers\Url;
/** @var $content \common\models\News */
/** @var $otherModels \common\models\News[] */
?>
<!-- common page-->

<div class="l-related">
    <div class="thumb-common">
        <img class="blank-img" src="../img/blank.gif">
        <a href=""><img class="thumb-cm" src="<?= $content->getThumbnailLink() ?>"></a>
    </div>
    <div class="l-i-rl">
        <h4><a href=""><?= $content->title ?></a></h4>
        <p><?= str_replace(mb_substr($content->short_description, 100, strlen($content->short_description), 'utf-8'), '...', $content->short_description) ?></p>
    </div>
</div>
<!-- end common page-->
