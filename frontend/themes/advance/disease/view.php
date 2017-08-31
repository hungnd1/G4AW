<?php
/**
 * Created by PhpStorm.
 * User: Linh
 * Date: 19/03/2016
 * Time: 2:39 PM
 */
use yii\helpers\Url;

/** @var $content \common\models\GapGeneral */
/** @var $otherModels \common\models\GapGeneral[] */
?>
<!-- common page-->

<div class="l-related">
    <div class="thumb-common">
        <img class="blank-img" src="../img/blank.gif">
        <a href="<?= Url::toRoute(['disease/detail', 'id' => $content->id]) ?>"><img class="thumb-cm"
                                                                                  src="<?= $content->getImageLink() ?>"></a>
    </div>
    <div class="l-i-rl">
        <h4>
            <a href="<?= Url::toRoute(['disease/detail', 'id' => $content->id]) ?>"><?= str_replace(mb_substr($content->title, 45, strlen($content->title), 'utf-8'), '...', $content->title) ?></a>
        </h4>
    </div>
</div>
<!-- end common page-->
