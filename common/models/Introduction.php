<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "introduction".
 *
 * @property integer $id
 * @property string $content
 * @property string $content_en
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 */
class Introduction extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'introduction';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['content','content_en'], 'string'],
            [['status'], 'required'],
            [['status', 'created_at', 'updated_at'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'content' => 'Nội dung giới thiệu',
            'content_en' => 'Nội dung giới thiệu tiếng anh',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
