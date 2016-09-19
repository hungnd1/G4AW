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
class LeadDonorForm extends Model
{
    public $lead_donor_id;

    public function rules()
    {
        return [
            [['lead_donor_id'], 'required', 'message' => 'Tên doanh nghiệp đỡ đầu không được để trống'],
            [['lead_donor_id'], 'integer'],
//            [['village_id'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'lead_donor_id' => \Yii::t('app', 'Doanh nghiệp đỡ đầu'),
        ];
    }


//    public function uniqueUsername($attribute, $params)
//    {
//        $user = User::findOne(['username' => $this->username]);
//        if ($user) {
//            $this->addError('username', Yii::t('app', 'Tên đăng nhập đã tồn tại. Vui lòng chọn tên khác.'));
//        }
//    }


}