<?php
/**
 * Created by PhpStorm.
 * User: HungChelsea
 * Date: 05-Aug-16
 * Time: 1:59 PM
 */

namespace frontend\models;


class Village extends \common\models\Village
{
    public function fields()
    {
        return [
            'id',
            'name',
            'image' => function ($model) {
                /** @var $model \common\models\Village */
                return $model->getThumbnailLink();
            },
            'province_name',
            'district_id',
            'description',
        ];
    }
}