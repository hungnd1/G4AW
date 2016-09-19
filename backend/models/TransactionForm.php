<?php
namespace backend\models;

use common\models\User;
use Yii;
use yii\base\Model;

/**
 * Created by PhpStorm.
 * User: HuyDQ
 * Date: 07/07/2016
 * Time: 11:18 AM
 */
class TransactionForm extends Model
{
    public $donation_time;
    public $username;
    public $address;
    public $donation_items;

    public function rules()
    {
        return [
            [['donation_time', 'username'], 'required'],
            [['donation_items'], 'integer'],
            [['address'], 'string'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'donation_time' => \Yii::t('app', 'Thời gian'),
            'address' => \Yii::t('app', 'Địa chỉ'),
            'username' => \Yii::t('app', 'Cá nhân/Đơn vị ủng hộ'),
        ];
    }



}