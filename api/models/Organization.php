<?php
/**
 * Created by PhpStorm.
 * User: VS9 X64Bit
 * Date: 25/02/2015
 * Time: 3:45 PM
 */

namespace api\models;

use common\models\User;

class Organization extends User
{
    public function fields()
    {
        return [
            'id',
            'name'=> function ($model) {
                /** @var $model User */
                return $model->getName();
            },
            'avatar'=> function($model){
                /** @var $model User */
                return $model->getAvatar();
            },
            'address',
            'phone_number',
            'email',
            'user_code',
            'status',
        ];
    }


}