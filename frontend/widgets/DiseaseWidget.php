<?php
/**
 * Created by PhpStorm.
 * User: HungChelsea
 * Date: 11-Aug-16
 * Time: 10:50 AM
 */

namespace frontend\widgets;


use yii\base\Widget;

class DiseaseWidget extends Widget
{
    public $content = null;

    public function run() {
        return $this->render('//disease/view',['content'=>$this->content]);
    }
}