<?php
/**
 * Created by PhpStorm.
 * User: Linh
 * Date: 02/12/2015
 * Time: 3:43 PM
 */

namespace frontend\widgets\home;



use common\models\User;
use yii\base\Widget;
use yii\helpers\Html;


class OrganizationPartnerWidget extends Widget
{

    public  $title;
    public function init()
    {
        parent::run();



    }

    public function run()
    {
        echo '<div class="partner-block clearfix">';
        echo Html::tag('h2',$this->title,['class'=>'title-cm']);
        echo '<div class="container">';
        echo '<ul>';
        /** @var []User $partners */
        $partners = User::find()->andWhere(['type'=>User::TYPE_ORGANIZATION])->andWhere(['status'=>User::STATUS_ACTIVE])->all();
        if(count($partners)>0){
            foreach($partners as $partner){
                /**@var $partner User  */
                 echo '<li>';
                 echo Html::a(Html::img($partner->getAvatar()),['/user/detail', 'id' => $partner->id]);
                 echo '<br>';
                 echo Html::tag('span',$partner->fullname);
                 echo '</li>';
            }
        }
        echo '</ul>';
        echo '</div>';
        echo '</div>';




     }

}