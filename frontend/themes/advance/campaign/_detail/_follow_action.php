<?php
/**
 * Created by PhpStorm.
 * User: Linh
 * Date: 14/03/2016
 * Time: 11:35 AM
 */
/** @var $model \common\models\Campaign */
$active='';
$text="Theo dõi";
if(!Yii::$app->user->isGuest){
    $checkModel =$model->getCampaignFollowings()->andWhere(['user_id'=>Yii::$app->user->id])->one();
    if($checkModel){
        $active ='active-fl';
        $text="Đang theo dõi";
    }
}
?>
<div class="block-act">
    <a href="" class="bt-common-2 bt-follow <?=$active?>" data-id="<?=$model->id?>"><i class="fa fa-heart"></i><?=$text?></a>
    <a href="" class="bt-common-2 bt-share"><i class="fa fa-share"></i>Chia sẻ</a>
</div>

