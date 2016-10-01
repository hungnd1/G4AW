<?php

/* @var $this \yii\web\View */
/* @var $content string */

use frontend\widgets\Header;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use common\widgets\Alert;

AppAsset::register($this);
?>
<?= $this->render('_partial/head'); ?>
<body >
<?php $this->beginBody() ?>
<?= Header::widget([]) ?>
<!-- Promo block BEGIN -->
<?= Yii::$app->session->getFlash('error'); ?>
<?= Alert::widget() ?>
<?=$content?>

<?= $this->render('_partial/footer')?>
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

    $(document).ready(function(){
        slider3 = $('.bxslider3').bxSlider({
            slideWidth: 180,
            minSlides: 2,
            maxSlides: 5,
            auto:true,
            speed: 500,
            slideMargin: 10,
            onSlideAfter : function($slideElement, oldIndex, newIndex) {
                slider3.stopAuto();
                slider3.startAuto();
            }
        });
    });

    $(document).ready(function() {

        $(".carousel").swiperight(function() {
            $(this).carousel('prev');
        });
        $(".carousel").swipeleft(function() {
            $(this).carousel('next');
        });

    }); /* END document ready */


    $(function() {
        var loc = window.location.href;
        $('.menu-web li').each(function() {
            $(this).removeClass('active');
            var link = $(this).find('a:first').attr('href');
            if(loc.indexOf(link) > 0)
                $(this).addClass('active');
        });
    });

</script>
</html>
<?php $this->endPage() ?>
