<?php
/**
 * @var $video_url
 * @var $player_type int
 * @var $protocol int
 * @var $this \yii\web\View
 * @var $width
 * @var $height
 */
use common\assets\JWPlayerAsset;


JWPlayerAsset::register($this);

$js = 'jwplayer.key="Tl/cGRKD5+mHxuBA9abJoeWYGnxLoRlF9Xt8VQHJS2nHMmlibF4GZ6FPp4Zk0206";';
$this->registerJs($js, \yii\web\View::POS_END);
$id = uniqid();
$config = "jwplayer('player_" . $id . "').setup({
            playlist: [{

			    file: '" . $video_url . "',
			    provider:'" . Yii::getAlias('@web') . "/js/player/HLSProvider6.swf',
			    type:'mp4'
			}],
			width: '" . $width . "',
			height: '" . $height . "',
			primary: 'flash',
			aspectratio: '16:9',
			skin: 'five',
            abouttext: 'About clip-hai player',
            aboutlink: 'http://hai.tapchigame.net/',
    });";
$this->registerJs($config, \yii\web\View::POS_END);
echo \yii\helpers\Html::tag('div', '', ['id' => 'player_' . $id]);