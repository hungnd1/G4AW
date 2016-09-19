<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Transaction */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Transactions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="transaction-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'campaign_id',
            'user_id',
            'username',
            'payment_type',
            'type',
            'amount',
            'transaction_time:datetime',
            'status',
            'telco',
            'scratch_card_code',
            'scratch_card_serial',
            'shortcode',
            'sms_mesage',
            'bank_transaction_id',
            'bank_transaction_detail:ntext',
            'description',
            'error_code',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
