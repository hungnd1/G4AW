<?php
/**
 * Created by PhpStorm.
 * User: Linh
 * Date: 19/03/2016
 * Time: 8:45 PM
 * @var $model \common\models\Campaign
 * @var $pages \yii\data\Pagination
 * @var $transactions \common\models\Transaction[]
 */
use \common\helpers\StringUtils;
$this->params['breadcrumbs'][] = ['label'=>'Chiến dịch','url'=>['/campaign/index']];
$this->params['breadcrumbs'][] = ['label'=>$model->name,'url'=>['/campaign/view','id'=>$model->id] ];
$this->params['breadcrumbs'][] = 'Danh sách ủng hộ';
$this->title = 'Danh sách ủng hộ';
?>
<!-- common page-->
<div class="content-common">
    <div class="container">
        <?= \frontend\widgets\Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <div class="history">
            <h2>Lịch sử ủng hộ</h2>
            <table class="table">
                <thead>
                <tr>
                    <th>Số thứ tự</th>
                    <th>Họ và tên</th>
                    <th>Số tiền ủng hộ</th>
                    <th>Thời gian</th>
                </tr>
                </thead>
                <tbody>
                 <?php
                 /**
                  * @var  $key
                  * @var  $item \common\models\Transaction
                  */
                 foreach ($transactions as $key=>$item):?>
                     <tr>
                         <td><?= ++$key ?></td>
                         <td><?=  $item->user->getName() ?></td>
                         <td><?= \common\helpers\CommonUtils::formatNumber($item->amount) . $item->getCurrency()?></td>
                         <td><?= date('d-m-Y',$item->created_at)?></td>
                     </tr>
                 <?php endforeach;?>
                </tbody>
            </table>
        </div>
        <div class="page-link-common">
            <?= \frontend\widgets\LinkPager::widget(['pagination' => $pages]) ?>
        </div>
    </div>
</div>
<!-- end common page-->
