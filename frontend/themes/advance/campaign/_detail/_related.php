<?php
use yii\helpers\Html;
/**
 * Created by PhpStorm.
 * User: Linh
 * Date: 14/03/2016
 * Time: 11:35 AM
 */
/** @var $model \common\models\Campaign */
$relateds=$model->campaignRelatedAsms;
?>
<div class="block-related">
    <h3>Có thể bạn quan tâm</h3>
<?php
foreach($relateds as $campaign){?>

        <div class="l-related">
            <div class="thumb-common">
                <?=Html::img('@web/img/blank.gif',['class'=>'blank-img'])?>
                <?= Html::a(Html::img($campaign->source->getThumbnailLink(),['class'=>'thumb-cm']),['/campaign/index','id'=>$campaign->source->id])?>

            </div>
            <h4><?= Html::a($campaign->source->name ,['/campaign/index','id'=>$campaign->source->id])?></h4>
        </div>


<?php } ?>
</div>
