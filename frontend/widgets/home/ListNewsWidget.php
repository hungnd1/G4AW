<?php
/**
 * Created by PhpStorm.
 * User: Linh
 * Date: 02/12/2015
 * Time: 3:43 PM
 */

namespace frontend\widgets\home;



use common\models\Campaign;
use common\models\News;
use common\models\User;
use yii\base\Widget;
use yii\helpers\Html;


class ListNewsWidget extends Widget
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
        echo '<div class="news-block clearfix">';
        echo '<div class="container">';
        echo Html::tag('h2',$this->title. Html::a('Tất cả'.Html::tag('i','',['class'=>'fa fa-chevron-right']),['/site/news']) );

        echo '<div class="list-item">';
        /** @var []User $partners */
        if(count($this->data)>0){
            foreach($this->data as $news){
                /**@var $news News  */
                 echo $this->render('/news/_item',['model'=>$news]);
            }
        }
        echo '</div>';
        echo '</div>';
        echo '</div>';




     }

}