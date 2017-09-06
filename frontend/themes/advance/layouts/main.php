<?php

/* @var $this \yii\web\View */
/* @var $content string */

use common\widgets\Alert;
use frontend\assets\AppAsset;
use frontend\widgets\Header;
use yii\helpers\Url;

AppAsset::register($this);
?>
<?= $this->render('_partial/head'); ?>
<body>
<script type="text/javascript">
    let url = "<?= Yii::$app->params['apiUrl'] ?>log-data/get-list-station";
    var locations = [];


    function initMap() {
        $.ajax({
            url: url,
            type: "GET",
            crossDomain: true,
            dataType: "text",
            success: function (result) {
                var rs = JSON.parse(result);
                if (rs['success']) {
                    locations = rs['data']['items'];
                    var infoWin = new google.maps.InfoWindow();
                    var markers = locations.map(function (location, i) {

                        var lat = Math.round(parseFloat(locations[i]['latitude']) * 100) / 100;
                        var long = Math.round(parseFloat(locations[i]['longtitude']) * 100) / 100;

                        latlngset = new google.maps.LatLng(lat, long);
                        var marker = new google.maps.Marker({
                            position: latlngset
                        });
                        google.maps.event.addListener(marker, 'click', function (evt) {
                            console.log(location.id);

                            var url = '<?= Url::toRoute(['site/get-weather-detail'])?>';
                            $.ajax({
                                url: url,
                                data: {
                                    'station_id': location.id
                                },
                                type: "GET",
                                crossDomain: true,
                                dataType: "text",
                                success: function (result) {
                                    if (null != result && '' != result) {
                                        infoWin.setContent(result);
                                        infoWin.open(map, marker);
                                        return;
                                    }
                                }
                                ,
                                error: function (result) {
                                    alert(result);
                                    alert("Không thành công. Quý khách vui lòng thử lại sau ít phút.");
                                    return;
                                }
                            });//end jQuery.ajax


                        });
                        return marker;
                    });

                    // Add a marker clusterer to manage the markers.
                    var markerCluster = new MarkerClusterer(map, markers, {
                        imagePath: 'https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m'
                    });

                }
            },
            error: function (result) {
//                alert('Không thành công. Quý khách vui lòng thử lại sau ít phút.');
                return;
            }
        });//end jQuery.ajax
        var map = new google.maps.Map(document.getElementById('map'), {
            zoom: 9,
            center: {
                lat: 11.61,
                lng: 108.04
            },
        });


    }
</script>
<script type="text/javascript"
        src="https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/markerclusterer.js"></script>

<?php $this->beginBody() ?>
<?= Header::widget([]) ?>
<!-- Promo block BEGIN -->
<?= Yii::$app->session->getFlash('error'); ?>
<?= Alert::widget() ?>
<?= $content ?>

<?= $this->render('_partial/footer') ?>
<?php $this->endBody() ?>
</body>
<script type="text/javascript">
    function loadPlayer(url, subUrl, image) {

//        if (null == url || '' == url) {
//            //$('#price').css('display','none');
//            return;
//        }
        var device = browserDectect1();
        //load player
        jwplayer("player").setup({
            playlist: [{
                image: image,
                sources: [{
                    file: url
                }]
            }],
            type: 'flash',
            display: {
                icons: 'true'
            },
            dock: 'false',
            width: '100%',
            height: '400px',
            androidhls: 'true',
            autostart: 'true',
            value: "netstreambasepath",
            quality: 'false',
            stretching: "exactfit",
            'http.startparam': 'start',
            controlbar: 'over',
            screencolor: "000000",
            flashplayer: "<?= Yii::$app->request->baseUrl; ?>/advance/js/jwplayer/player.swf",
            provider: 'http',
            plugins: {
                "<?= Yii::$app->request->baseUrl; ?>/advance/js/jwplayer/captions.js": {
                    file: subUrl,
                    fontSize: 15,
                    pluginmode: "HYBRID"
                },

                "ova-jw": {
                    "player": {
                        "modes": {
                            "linear": {
                                "controls": {
                                    "enableFullscreen": true,
                                    "enablePlay": true,
                                    "enablePause": true,
                                    "enableMute": true,
                                    "enableVolume": true
                                }
                            }
                        }
                    }
                },
                '<?= Yii::$app->request->baseUrl; ?>/advance/js/overlay.js': {
                    text: null//'<img src="<?php //echo Yii::app()->theme->baseUrl; ?>/images/logo2.png"/>'
                }
            },
            captions: {
                color: '#FF0000',
                fontSize: 20,
                backgroundOpacity: 50
            }
        });
    }

    $(document).ready(function () {
        slider3 = $('.bxslider3').bxSlider({
            slideWidth: 180,
            minSlides: 2,
            maxSlides: 5,
            auto: true,
            speed: 500,
            slideMargin: 10,
            onSlideAfter: function ($slideElement, oldIndex, newIndex) {
                slider3.stopAuto();
                slider3.startAuto();
            }
        });
    });

    $(document).ready(function () {

        $(".carousel").swiperight(function () {
            $(this).carousel('prev');
        });
        $(".carousel").swipeleft(function () {
            $(this).carousel('next');
        });

    });
    /* END document ready */


    $(function () {
        var loc = window.location.href;
        $('.menu-web li').each(function () {
            $(this).removeClass('active');
            var link = $(this).find('a:first').attr('href');
            if (loc.indexOf(link) > 0)
                $(this).addClass('active');
        });
    });

</script>

</html>
<?php $this->endPage() ?>
