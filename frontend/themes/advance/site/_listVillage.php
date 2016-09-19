<?php
/**
 * Created by PhpStorm.
 * User: HungChelsea
 * Date: 18-Aug-16
 * Time: 2:27 PM
 */
?>

<?php if (isset($listVillage) && !empty($listVillage)) {
    /** @var \common\models\Village $item */
    foreach ($listVillage as $item) {
        $image = $item->getImageLink(); ?>

        <div class="x-in-list">
            <div class="thumb-common">
                <img src="../img/blank.gif">
                <a href="<?= \yii\helpers\Url::toRoute(['village/view', 'id_village' => $item->id]) ?>"><img
                        class="thumb-cm" src="<?= $image ?>"><br></a>
            </div>
            <h4><?= $item->name ?></h4>
        </div>
    <?php }
    ?>
    <div id="last-comment"></div>
    <input type="hidden" name="page" id="page"
           value="<?= sizeof($listVillage) - 1 ?>">
    <input type="hidden" name="numberCount" id="numberCount" value="<?= sizeof($listVillage) ?>">
    <input type="hidden" name="total" id="total" value="<?= $pages->totalCount ?>">
    <?php if (count($listVillage) >= 10) { ?>
        <div class="text-center">
            <a id="more" onclick="loadMore();" class="more-2">Xem thêm xã</a>
        </div>
    <?php } ?>
<?php } ?>
