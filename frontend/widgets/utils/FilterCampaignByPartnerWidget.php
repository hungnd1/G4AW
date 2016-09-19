<?php
/**
 * Created by PhpStorm.
 * User: Linh
 * Date: 02/12/2015
 * Time: 3:43 PM
 */

namespace frontend\widgets\utils;


use common\models\Product;
use common\models\User;
use frontend\models\FilterCampaignForm;
use yii\base\Widget;
use yii\helpers\Html;


class  FilterCampaignByPartnerWidget extends Widget
{
    public $defaultValue;


    public function init()
    {
        parent::run();
        $this->defaultValue = explode(',',$this->defaultValue);
    }

    public function run()
    {
        $model= new FilterCampaignForm();
        $model->partners=$this->defaultValue;
        return $this->render('_filter_by_partner',['model'=>$model]);
    }

}