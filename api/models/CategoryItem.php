<?php
/**
 * Created by PhpStorm.
 * User: VS9 X64Bit
 * Date: 25/02/2015
 * Time: 3:45 PM
 */

namespace api\models;


use common\models\Category;
use yii\helpers\Html;

class CategoryItem extends Category
{
    public function fields()
    {
        return [
            'id',
            'display_name',
            'type',
            'order_number',
//            'level'
        ];
    }



}