<?php
/**
 * Created by PhpStorm.
 * User: HungChelsea
 * Date: 11-Aug-16
 * Time: 11:36 AM
 */
use dosamigos\highcharts\HighCharts;

/** @var $weather_current  \common\models\WeatherDetail */
?>

<!-- content -->
<script src="<?= Yii::$app->request->baseUrl ?>/js/jwplayer/jwplayer.js"></script>
<script src="<?= Yii::$app->request->baseUrl ?>/js/ng_player.js"></script>
<script src="<?= Yii::$app->request->baseUrl ?>/js/ng_swfobject.js"></script>
<script src="<?= Yii::$app->request->baseUrl ?>/js/ParsedQueryString.js"></script>
<script>jwplayer.key = "tOf3A+hW+N76uJtefCw6XMUSRejNvQozOQIaBw==";</script>
<div class="content">
    <div class="main-cm-2">
        <div class="container">
            <div class="left-content" style="width: 100%;">
                <table>
                    <?php if (isset($weather_current) && $weather_current) { ?>
                        <tr style="width: 100%">
                            <td valign="top" style="padding-bottom: 50px;">
                                <div class="tinh-tp"><h3><strong>Thời
                                            tiết <?= $weather_current->station_name ?></strong>
                                    </h3><br>(Hôm nay, cập nhật lúc <?= date('H:i', $weather_current->timestamp) ?>)<br><b
                                        style="font-size: 25px;"><?= $weather_current->content ?></b>
                                </div>
                                <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                    <tbody>
                                    <tr>
                                        <td width="100"><img src="<?= $weather_current->image ?>"
                                                             alt="" width="90" height="70"><br>
                                            <b style="text-align: center;font-size: 25px;"><?= $weather_current->t_average ?></b>
                                        </td>
                                        <td width="400" style="padding-left: 30px;"><span class="nhietdo-big">
                                            Nhiệt độ: <b><?= $weather_current->tmin ?><sup>o</sup>C
                                                    - <?= $weather_current->tmax ?><sup>o</sup>C</b><br>
                                            Tổng lượng mưa: <b><?= $weather_current->precipitation . ' ' . $weather_current->precipitation_unit ?></b><br>
                                            Tốc độ gió: <b><?= $weather_current->wndspd_km_h ?></b><br>
                                            Hướng gió: <b><?= $weather_current->wnddir ?><br>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    <?php } ?>
                    <tr>
                        <?php if (sizeof($weather_next_week) > 0) {
                            $i = 0;
                            ?>
                            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                <tbody>
                                <tr class="tb-top">
                                    <?php for ($i = 0; $i < sizeof($weather_next_week); $i++) {
                                        /** @var $weather \common\models\WeatherDetail */
                                        ?>
                                        <td width="<?= 100 / sizeof($weather_next_week) ?>%">
                                            <b><?= \common\helpers\CUtils::sw_get_current_weekday($weather_next_week[$i]['timestamp']) ?></b><br><br>
                                            Dự báo ngày <?= date('d-m',$weather_next_week[$i]['timestamp']) ?>
                                        </td>
                                    <?php } ?>

                                </tr>
                                <tr>
                                    <?php foreach ($weather_next_week as $weather) {
                                        /** @var $weather \common\models\WeatherDetail */
                                        $weather = (object)$weather;
                                        ?>
                                        <td class="thoitiet-cell" valign="top">
                                            <div class="tt-sub-pic"><img
                                                    src="<?= $weather->image ?>"
                                                    alt="" width="80" height="60"></div>
                                            <div class="tt-sub-text">
                                            <span class="nhietdo-small"><?= $weather->tmin ?>
                                                <sup>o</sup>C-<?= $weather->tmax ?><sup>o</sup>C</span>
                                                <br>
                                                <span class="tt-sub-small"><?= $weather->content ?></span>
                                            </div>
                                        </td>
                                    <?php } ?>
                                </tr>
                                </tbody>
                            </table>
                        <?php } ?>
                    </tr>
                </table>
                <br>
                <br>
                <br>
                <br>
                <br>
                <?php if (sizeof($weather_week_ago) > 0) { ?>
                    <?= HighCharts::widget([
                        'clientOptions' => [
                            'chart' => [
                                'type' => 'line'
                            ],
                            'height' => 500,
                            'title' => [
                                'text' => 'Biểu đồ nhiệt độ từ ngày ' . date('d/m/Y', $weather_week_ago[0]['timestamp']) . ' đến ngày ' . date('d/m/Y', $weather_week_ago[sizeof($weather_week_ago) - 1]['timestamp']),
                            ],
                            'xAxis' => [
                                'categories' => $dataCharts[0]
                            ],
                            'yAxis' => [
                                'min' => 0,
                                'allowDecimals' => false,
                                'title' => [
                                    'text' => Yii::t('app', 'Nhiệt độ')
                                ]
                            ],
                            'series' => [
                                ['name' => Yii::t('app', 'Nhiệt độ nhỏ nhất'), 'data' => $dataCharts[1]],
                                ['name' => Yii::t('app', 'Nhiệt độ lớn nhất'), 'data' => $dataCharts[2]],
                            ]
                        ]
                    ]);
                    ?>
                    <br>
                    <br>
                    <br>
                    <br>
                    <?= HighCharts::widget([
                        'clientOptions' => [
                            'chart' => [
                                'type' => 'column'
                            ],
                            'height' => 500,
                            'title' => [
                                'text' => 'Biểu đồ lượng mưa từ ngày ' . date('d/m/Y', $weather_week_ago[0]['timestamp']) . ' đến ngày ' . date('d/m/Y', $weather_week_ago[sizeof($weather_week_ago) - 1]['timestamp']),
                            ],
                            'xAxis' => [
                                'categories' => $dataPrecipitation[0]
                            ],
                            'hAxis.direction' => -1,
                            'yAxis' => [
                                'min' => 0,
                                'allowDecimals' => false,
                                'title' => [
                                    'text' => Yii::t('app', 'Lượng mưa (mm)')
                                ]
                            ],
                            'series' => [
                                ['name' => Yii::t('app', 'Lượng mưa trung bình'), 'data' => $dataPrecipitation[1]],
                            ]
                        ]
                    ]);
                }
                ?>
            </div>
        </div>
    </div>
</div>
<!-- end content -->


