<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "province".
 *
 * @property integer $id
 * @property string $name
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $name_en
 */
class Province extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    const STATUS_ACTIVE = 10;
    const STATUS_INACTIVE = 0;

    public function getListStatus()
    {
        $list1 = [
            self::STATUS_ACTIVE => 'Hoạt động',
            self::STATUS_INACTIVE => 'Tạm Dừng',
        ];

        return $list1;
    }

    public function getStatusName()
    {
        $lst = self::getListStatus();
        if (array_key_exists($this->status, $lst)) {
            return $lst[$this->status];
        }
        return $this->status;
    }


    public static function tableName()
    {
        return 'province';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'status', 'name_en'], 'required'],
            [['status', 'created_at', 'updated_at'], 'integer'],
            [['name', 'name_en'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Tên tỉnh',
            'status' => 'Trạng thái',
            'created_at' => 'Ngày tạo',
            'updated_at' => 'Ngày cập nhật',
            'name_en' => 'Tên tỉnh tiếng anh',
        ];
    }
}
