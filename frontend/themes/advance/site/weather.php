<?php
/**
 * Created by PhpStorm.
 * User: Linh
 * Date: 19/03/2016
 * Time: 4:40 PM
 */
use yii\helpers\Html;
/** @var $news \common\models\News[] */
/** @var $pages \yii\data\Pagination */
$this->title ='Tin tức';
?>
<!-- common page-->
<div class="content-common">
    <div class="container">
        <div class="news-block cm-block">
            <h2>Thông tin thời tiết</h2>
            <div class="google-map">
                <div id="map"></div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    google.maps.event.addDomListener(window, "load", initMap);

</script>
<!-- end common page-->