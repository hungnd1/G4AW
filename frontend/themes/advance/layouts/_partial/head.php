<?php
/**
 * Created by PhpStorm.
 * User: Linh
 * Date: 17/12/2015
 * Time: 3:38 PM
 */
use yii\helpers\Html;

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
	<meta name="description" content="">
	<meta name="author" content="">
	<title><?= \frontend\helpers\UserHelper::multilanguage('DỊCH VỤ THÔNG TIN GREENCOFFEE','Information services for sustainable coffee farm management') ?></title>

	<?= Html::csrfMetaTags() ?>

	<title><?= Html::encode( $this->title ) ?></title>
	<script type="text/javascript"
			src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBR7YxhX_ugreNUsW_CbeHOaE45w7rObgw&libraries=places&sensor=false"></script>
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>

	<style>
		/* Always set the map height explicitly to define the size of the div
         * element that contains the map. */
		#map {
			height: 100%;
		}
		.infowindow {
			font-family: "Open Sans", sans-serif;
		}

		.infowindow .content1 .left-content1 {
			width: 120px;
			float: left;
			text-align: center;
			margin-right: 20px;
		}

		.infowindow .content1 .left-content1 h3 {
			margin: 0 0 10px 0;
			padding-top: 15px;
			font-size: 14px;
			text-transform: uppercase;
			font-weight: 700;
			color: #222;
			font-family: "Open Sans", sans-serif;
		}

		.infowindow .content1 .left-content1 .time {
			font-size: 13px;
			color: #aaa;
			text-transform: uppercase;
			font-family: "Open Sans", sans-serif;
			display: block;
		}

		.infowindow .content1 .left-content1 .temperature {
			margin-top: 10px;
		}

		.infowindow .content1 .left-content1 .temperature p:first-child {
			font-size: 40px;
			font-weight: 600;
			color: #333;
			margin: 0;
		}

		.infowindow .content1 .left-content1 .temperature p:first-child span {
			font-size: 24px;
			color: #888;
		}

		.infowindow .content1 .left-content1 .temperature p:last-child {
			font-weight: 400;
			font-size: 14px;
			color: #333;
		}

		.infowindow .content1 .right-content1 {
			overflow: hidden;
			padding-top: 15px;
		}

		.infowindow .content1 .right-content1 ul {
			padding: 0;
			margin: 0;
		}

		.infowindow .content1 .right-content1 ul li {
			list-style: none;
			font-size: 13px;
			font-weight: 600;
			line-height: 24px;
			color: #888;
		}

		.infowindow .content1 .right-content1 ul li:last-child {
			margin-top: 30px;
		}

		.infowindow .content1 .right-content1 ul li span {
			color: #333;
		}

		.infowindow .content1 .right-content1 ul li a {
			color: #fff;
			font-size: 13px;
			font-weight: 600;
			padding: 5px 10px;
			border-radius: 20px;
			display: inline-block;
		}

		.infowindow .content1 .right-content1 ul li a:first-child {
			background-color: #d93674;
			margin-right: 5px;
		}

		.infowindow .content1 .right-content1 ul li a:last-child {
			background-color: #058c84;
		}

		.infor ul {
			-webkit-column-count: 2;
			-moz-column-count: 2;
			column-count: 2;
		}

		.infor ul li {
			list-style: none;
			line-height: 30px;
			margin: 0;
		}
	</style>
	<?php $this->head() ?>

</head>
