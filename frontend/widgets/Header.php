<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 5/26/15
 * Time: 11:02 AM
 */

namespace  frontend\widgets;


use app\helpers\ApiHelper;
use app\models\ListCategory;
use common\models\LeadDonor;
use common\models\Village;
use yii\base\Exception;
use yii\base\Widget;

class Header extends Widget {
    const _FILTER = 0;
    public static $leadDonor = null;
    public $route;
    public $id;
    public function init(){
        $id = \Yii::$app->request->get('id_village',0);

    }

    public function run() {
        return $this->render('/layouts/_partial/header',['id'=>$this->id]);
    }
} 