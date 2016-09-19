<?php
namespace frontend\models;

use common\models\User;
use Yii;
use yii\base\Model;

/**
 * FilterCampaignForm form
 */
class FilterCampaignForm extends Model
{
    public $categories;
    public $partners;



    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [

            [['categories', 'partners'],'safe'],
        ];
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'categories' => Yii::t('app', 'Thể loại'),
            'partners' => Yii::t('app', 'Đơn vị tổ chức'),
        ];
    }
}
