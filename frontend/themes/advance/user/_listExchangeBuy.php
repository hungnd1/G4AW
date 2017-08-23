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
if (isset($listExchangeBuy) && !empty($listExchangeBuy)) {
    foreach ($listExchangeBuy as $item) {
        /** @var $item \common\models\ExchangeBuy */
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
                    echo $item->total_quantity . " tấn"; ?>
                    <br>
                    Loại cà
                    phê: <?php $typeCoffee = \common\models\TypeCoffee::findOne($item->type_coffee_id);
                    /** @var $typeCoffee \common\models\TypeCoffee */
                    echo $typeCoffee->name;
                    ?><br>
                    Giá: <?= $item->price_buy ?> VNĐ<br>
                    Vị
                    trí: <?= $item->location_name ? $item->location_name : 'Chưa xác định' ?>
                    <br>
                    Thời gian: <?= date('d/m/Y H:m:s', $item->created_at) ?>
                </p>
            </div>
        </div>
    <?php }
} ?>
    <div id="last-exchange-buy"></div>
    <input type="hidden" name="pageBuy" id="pageBuy"
           value="<?= sizeof($listExchangeBuy) - 1 ?>">
    <input type="hidden" name="aa" id="aa"
           value="<?= $pages_buy->totalCount ?>">
    <input type="hidden" name="numberCountBuy" id="numberCountBuy"
           value="<?= sizeof($listExchangeBuy) ?>">
<?php
?>