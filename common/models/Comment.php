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

    public static function listType()
    {
        $lst = [
            self::TYPE_NEW => 'Bình luận tin tức',
            self::TYPE_VILLAGE => 'Bình luận xã',
        ];
        return $lst;
    }


    public static function getNameByType($type)
    {
        $lst = self::listType();
        if (array_key_exists($type, $lst)) {
            return $lst[$type];
        }
        return $type;
    }

    public function getStatusName()
    {
        $lst = self::listStatus();
        if (array_key_exists($this->status, $lst)) {
            return $lst[$this->status];
        }
        return $this->status;
    }


    public static function listStatus()
    {
        $lst = [
            self::STATUS_DRAFT => 'Soạn thảo',
            self::STATUS_ACTIVE => 'Hoạt động',
            self::STATUS_INACTIVE => 'Tạm dừng',
        ];
        return $lst;
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

    public function spUpdateStatus($newStatus, $sp_id)
    {
        $this->status = $newStatus;
        $this->updated_at = time();
        return $this->update(false);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_new' => 'Id New',
            'content' => 'Nội dung',
            'status' => 'Trạng thái',
            'type' => 'Type',
            'user_id' => 'User ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }


}
