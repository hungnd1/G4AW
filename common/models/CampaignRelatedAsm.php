<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "campaign_related_asm".
 *
 * @property integer $id
 * @property integer $source_id
 * @property integer $destination_id
 * @property integer $relation_type
 * @property string $description
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property Campaign $source
 * @property Campaign $destination
 */
class CampaignRelatedAsm extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'campaign_related_asm';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['source_id', 'destination_id'], 'required'],
            [['source_id', 'destination_id', 'relation_type', 'created_at', 'updated_at'], 'integer'],
            [['description'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'source_id' => Yii::t('app', 'Source ID'),
            'destination_id' => Yii::t('app', 'Destination ID'),
            'relation_type' => Yii::t('app', 'Relation Type'),
            'description' => Yii::t('app', 'Description'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSource()
    {
        return $this->hasOne(Campaign::className(), ['id' => 'source_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDestination()
    {
        return $this->hasOne(Campaign::className(), ['id' => 'destination_id']);
    }
}
