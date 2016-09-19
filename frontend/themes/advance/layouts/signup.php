<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use common\widgets\Alert;

AppAsset::register($this);
?>
<?= $this->render('_partial/head'); ?>
<body class="bg-2">
<?php $this->beginBody() ?>
<div class="wrapper">
    <header id="header" class="header"><?= $this->render('_partial/header'); ?></header>
    <!--Begin menu-->

    <!--End menu-->

    <?= $content ?>

    <footer id="footer"><?= $this->render('_partial/footer'); ?></footer>
</div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
