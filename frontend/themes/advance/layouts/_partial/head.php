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
	<title><?= \frontend\helpers\UserHelper::multilanguage('Chương trình phát triển các dự án cafe','Information services for sustainable coffee farm management') ?></title>

	<?= Html::csrfMetaTags() ?>

	<title><?= Html::encode( $this->title ) ?></title>

	<?php $this->head() ?>

</head>
