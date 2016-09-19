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

<?=$content?>

<?php $this->endBody() ?>
</body>
<?php $this->endPage() ?>
