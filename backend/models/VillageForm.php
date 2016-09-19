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
class VillageForm extends Model
{
    public $village_id;

    public function rules()
    {
        return [
            [['village_id'], 'required', 'message' => 'Tên xã muốn được bảo trợ không được để trống'],
            [['village_id'], 'integer'],
//            [['village_id'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'village_id' => \Yii::t('app', 'Tên xã muốn bảo trợ'),
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