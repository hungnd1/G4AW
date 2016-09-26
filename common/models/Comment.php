<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "comment".
 *
 * @property integer $id
 * @property integer $id_new
 * @property string $content
 * @property integer $status
 * @property integer $type
 * @property integer $user_id
 * @property integer $created_at
 * @property integer $updated_at
 */
class Comment extends \yii\db\ActiveRecord
{

    const STATUS_ACTIVE = 10;
    const STATUS_INACTIVE = 0;
    const STATUS_DRAFT = 4;

    const TYPE_NEW = 1;
    const TYPE_VILLAGE =2;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'comment';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_new', 'status', 'type', 'user_id', 'created_at', 'updated_at'], 'integer'],
            [['content'], 'string', 'max' => 500],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_new' => 'Id New',
            'content' => 'Content',
            'status' => 'Status',
            'type' => 'Type',
            'user_id' => 'User ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
