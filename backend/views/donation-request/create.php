<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\DonationRequest */

$this->title = 'Create Donation Request';
$this->params['breadcrumbs'][] = ['label' => 'Donation Requests', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="donation-request-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
