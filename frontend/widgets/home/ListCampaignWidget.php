<?php
/**
 * Created by PhpStorm.
 * User: Linh
 * Date: 02/12/2015
 * Time: 3:43 PM
 */

namespace frontend\widgets\home;



use common\models\Campaign;
use common\models\User;
use yii\base\Widget;
use yii\helpers\Html;


class ListCampaignWidget extends Widget
{

    public  $title;
    /** @var  $data []Campaign */
    public $data;
    public function init()
    {
        parent::run();



    }

    public function run()
    {
        echo '<div class="campain-block clearfix">';
        echo '<div class="container">';

        echo Html::tag('h2',$this->title,['class'=>'title-cm']);

        echo '<div class="list-item">';
        /** @var []User $partners */
        if(count($this->data)>0){
            foreach($this->data as $campaign){
                /**@var $campaign Campaign  */
                 echo $this->render('/campaign/_item',['model'=>$campaign]);
            }
        }
        echo '</div>';
        echo '<div class="o-bt">';
        echo Html::a('Xem tất cả chiến dịch',['/campaign/index'],['class'=>'more-cm']);
        echo '</div>';

        echo '</div>';
        echo '</div>';




     }

}