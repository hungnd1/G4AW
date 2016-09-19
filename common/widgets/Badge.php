<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace common\widgets;

use yii\base\Widget;

class Badge extends Widget
{
    public $data = [];


    public function init()
    {
        parent::init();
        if (\Yii::$app->user->id) {
            //TODO get list notification
            $this->data = [
                [
                    'title' => "Next day",
                    'deadline_at' => 1438834902
                ],
                [
                    'title' => "Next Week",
                    'deadline_at' => 1439353302
                ]
            ];
        }


    }


}
