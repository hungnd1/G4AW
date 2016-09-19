<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%news_category_asm}}".
 *
 * @property integer $id
 * @property integer $news_id
 * @property integer $category_id
 * @property integer $created_at
 * @property integer $updated_at
 */
class NewsCategoryAsm extends \yii\db\ActiveRecord
{
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%news_category_asm}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['news_id', 'category_id'], 'required'],
            [['news_id', 'category_id', 'created_at', 'updated_at'], 'integer'],
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
            'category_id' => Yii::t('app', 'Category ID'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    public static function newNewsCategoryAsm($news_id, $category_id)
    {
        $asm = NewsCategoryAsm::findOne(['news_id' => $news_id]);
        if (!$asm) {
            $asm = new NewsCategoryAsm();
        }

        $asm->category_id = $category_id;
        $asm->news_id = $news_id;

        if (!$asm->save()) {
            Yii::error($asm->getErrors());
        }

    }
}
