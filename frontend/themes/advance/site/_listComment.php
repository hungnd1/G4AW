<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 5/27/15
 * Time: 10:53 AM
 */

?>
<?php if (isset($listComments) && !empty($listComments)) { ?>
    <?php if (1 == $type) { ?>
        <div id="head-comment"></div> <?php } ?>
    <?php foreach ($listComments as $item) {
        /** @var $item \common\models\Comment */ ?>
        <div class="comment-box-item">
            <img
                src="<?= $item->user->getAvatar() ? $item->user->getAvatar() : Yii::$app->request->baseUrl . '/img/avt_df.png' ?>">
            <div class="left-comment">
                <h5 class=""><?= str_replace(substr($item->user->username,8),'***',$item->user->username) ?> <span
                        class="time-up"><?= date('d/m/Y H:i:s', $item->updated_at) ?></span></h5>

                <p><?= $item->content ?></p>
            </div>
        </div>
    <?php }
} ?>
<?php if ($type == 1) { ?>
    <div id="last-comment"></div>
    <input type="hidden" name="page" id="page"
           value="<?= sizeof($listComments) - 1 ?>">
    <input type="hidden" name="aa" id="aa"
           value="<?= $pages->totalCount ?>">
    <input type="hidden" name="numberCount" id="numberCount"
           value="<?= sizeof($listComments) ?>">
    <?php
    if ($pages->totalCount > sizeof($listComments)) { ?>
        <div class="text-center" style="    padding-top: 20px;">
            <a id="more" onclick="readMore();" class="more-2"><?= \frontend\helpers\UserHelper::multilanguage('Xem thêm','Read more') ?></a>
        </div>
    <?php }
}
?>
