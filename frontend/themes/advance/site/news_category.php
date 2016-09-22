<?php
/**
 * Created by PhpStorm.
 * User: HungChelsea
 * Date: 18-Aug-16
 * Time: 2:22 PM
 */
use yii\helpers\Url;

?>

<?php if (isset($listNewest) && !empty($listNewest)) {
    /** @var \common\models\News $item */
    foreach ($listNewest as $item) {
        $thumbnail = $item->getThumbnailLink();
        ?>
        <div class="news">
            <div class="thumb-common">
                <img src="../img/blank.gif">
                <a href="<?= Url::toRoute(['news/detail','id'=>$item->id]) ?>">
                    <img class="thumb-cm" src="<?= $thumbnail ?>"><br></a>
            </div>
            <div class="if-cm-2">
                <a href="<?= Url::toRoute(['news/detail','id'=>$item->id]) ?>"><h3 class="name-1"><?= $item->title ?></h3><br></a>
                                <span class="des-cm-1"><?= str_replace(mb_substr($item->short_description, 100, strlen($item->short_description), 'utf-8'), '...', $item->short_description) ?><a href="<?= Url::toRoute(['news/detail','id'=>$item->id]) ?>" class="read-more">Đọc
                                        thêm</a></span>
            </div>
        </div>
    <?php }
} ?>
