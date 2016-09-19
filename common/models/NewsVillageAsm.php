<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%news_village_asm}}".
 *
 * @property integer $id
 * @property integer $news_id
 * @property integer $village_id
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property News $news
 * @property Village $village
 */
class NewsVillageAsm extends \yii\db\ActiveRecord
{

    const STATUS_ACTIVE = 10;
    const STATUS_DEACTIVE = 0;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%news_village_asm}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['news_id', 'village_id'], 'required'],
            [['news_id', 'village_id', 'status', 'created_at', 'updated_at'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'news_id' => Yii::t('app', 'News ID'),
            'village_id' => Yii::t('app', 'Village ID'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }



    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNews()
    {
        return $this->hasOne(News::className(), ['id' => 'news_id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVillage()
    {
        return $this->hasOne(Village::className(), ['id' => 'village_id']);
    }
}
