<?php
/**
 * Created by PhpStorm.
 * User: Linh
 * Date: 14/03/2016
 * Time: 11:35 AM
 */
/** @var $model \common\models\Campaign */
$newsRelated=$model->news;
foreach($newsRelated as $news){
    echo \yii\bootstrap\Html::tag('li',\yii\bootstrap\Html::a($news->title,['/news/view','id'=>$news->id]));
}
?>

