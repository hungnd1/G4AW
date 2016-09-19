<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%report_donation}}".
 *
 * @property integer $id
 * @property string $report_date
 * @property integer $organization_id
 * @property integer $campaign_id
 * @property double $revenues
 * @property integer $donate_count
 */
class ReportDonation extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%report_donation}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['report_date'], 'safe'],
            [['organization_id', 'campaign_id', 'donate_count'], 'integer'],
            [['revenues'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'report_date' => 'Report Date',
            'organization_id' => 'Organization ID',
            'campaign_id' => 'Campaign ID',
            'revenues' => 'Revenues',
            'donate_count' => 'Donate Count',
        ];
    }
}
