<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 5/27/15
 * Time: 10:53 AM
 */

?>
    <div id="head-comment"></div>
<?php
if (isset($listExchangeSold) && !empty($listExchangeSold)) {
    foreach ($listExchangeSold as $item) {
        /** @var $item \common\models\Exchange */
        ?>
        <div class="comment-box-item">
            <img
                src="<?= \common\models\Subscriber::findOne($item->subscriber_id)->getImageLink() ? \common\models\Subscriber::findOne($item->subscriber_id)->getImageLink() : Yii::$app->request->baseUrl . '/img/avt_df.png' ?>"
                style="width: 140px;height: 140px;margin-right: 50px;">
            <div class="left-comment">
                <h5 class=""><?= \common\models\Subscriber::findOne($item->subscriber_id)->username ?>
                </h5>
                <p>Tổng sản
                    lượng: <?php
                    echo $item->total_quantity ." tấn"; ?>
                    <br>
                    Loại cà
                    phê: <?php $typeCoffee = \common\models\TypeCoffee::findOne($item->type_coffee);
                    /** @var $typeCoffee \common\models\TypeCoffee */
                    echo $typeCoffee->name;
                    ?><br>
                    Giá bán: <?= $item->price ?> VNĐ<br>
                    Tỉnh: <?= \common\models\Province::findOne($item->province_id)->province_name ?> <br>
                    Người bán: <?= \common\models\Subscriber::findOne($item->subscriber_id)->full_name ?> <br>
                    Thời gian: <?= date('d/m/Y H:m:s', $item->created_at) ?>
                </p>
            </div>
        </div>
    <?php }
} ?>
    <div id="last-comment"></div>
    <input type="hidden" name="page" id="page"
           value="<?= sizeof($listExchangeSold) - 1 ?>">
    <input type="hidden" name="aa" id="aa"
           value="<?= $pages->totalCount ?>">
    <input type="hidden" name="numberCount" id="numberCount"
           value="<?= sizeof($listExchangeSold) ?>">
<?php
?>