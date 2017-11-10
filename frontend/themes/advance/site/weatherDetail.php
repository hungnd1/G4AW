<?php
/** @var $weather \common\models\WeatherDetail */
?>
<div class="infowindow">
    <div class="content1">
        <div class="left-content1">
            <h3>
                <?= $weather->station_name ?><br><?= $weather->province_name ?>
            </h3>
            <!-- <span class="time">9:20 Am</span> -->
            <img width="64" height="46" src="<?= $weather->image ?>"
                 class="attachment-post-thumbnail size-post-thumbnail wp-post-image" alt="">
            <div class="temperature">
                <p><?= $weather->t_average ?></p>
                <p><?= $weather->wtxt ?></p>
                <p><?= date('d/m/Y', $weather->timestamp) ?></p>
            </div>
        </div>
        <div class="right-content1">
            <ul>
                <li>Nhiệt độ: <span><?= $weather->tmin ?>⁰C - <?= $weather->tmax ?>⁰C</span></li>
                <li>Nhiệt độ cảm nhận: <span><?= $weather->RFTMIN ?>⁰C - <?= $weather->RFTMAX ?>⁰C</span></li>
                <li>Lượng mưa : <span><?= $weather->precipitation_average . $weather->precipitation_unit ?></span></li>
                <li>Tốc độ gió: <span><?= $weather->wndspd_km_h ?></span></li>
                <li>Hướng gió: <span><?= $weather->wnddir ?></span></li>
                <li>
                    <a href="<?= \yii\helpers\Url::toRoute(['site/weather-detail','station_id'=>$weather->station_id]) ?>">Xem chi tiết</a>
                    <a href="<?= \yii\helpers\Url::toRoute(['site/detail','temp'=>floor(($weather->tmax + $weather->tmin) / 2),'pre'=>$weather->precipitation,'wind'=>$weather->wndspd]) ?>">Khuyến cáo</a>
                </li>
            </ul>
        </div>
    </div>
</div>