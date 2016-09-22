<?php

namespace common\models;

use Yii;
use yii\helpers\Url;

/**
 * This is the model class for table "area".
 *
 * @property integer $id
 * @property string $name
 * @property integer $status
 * @property string $image
 * @property integer $created_at
 * @property integer $updated_at
 */
class Area extends \yii\db\ActiveRecord
{

    const STATUS_ACTIVE = 10;
    CONST STATUS_INACTIVE = 0;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'area';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name','status'], 'required'],
            [['created_at', 'updated_at'], 'integer'],
            [['name', 'image'], 'string', 'max' => 500],
            [['image'], 'required', 'on' => 'create'],
            [['image'], 'image', 'extensions' => 'png,jpg,jpeg,gif',
                'maxSize' => 1024 * 1024 * 10, 'tooBig' => 'Ảnh upload vượt quá dung lượng cho phép!'
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Tên vùng',
            'image' => 'Ảnh đại diện',
            'created_at' => 'Ngày tạo',
            'updated_at' => 'Ngày cập nhật',
        ];
    }

    public static function listStatus()
    {
        $lst = [
            self::STATUS_ACTIVE => 'Hoạt động',
            self::STATUS_INACTIVE => 'Tạm dừng',
        ];
        return $lst;
    }

    public function getStatusName()
    {
        $lst = self::listStatus();
        if (array_key_exists($this->status, $lst)) {
            return $lst[$this->status];
        }
        return $this->status;
    }

    public function getThumbnailLink()
    {
        $pathLink = Yii::getAlias('@web') . '/' . Yii::getAlias('@area_image') . '/';
        $filename = null;

        if ($this->image) {
            $filename = $this->image;

        }
        if ($filename == null) {
            $pathLink = Yii::getAlias("@web/img/");
            $filename = 'bg_df.png';
        }

        return Url::to($pathLink . $filename, true);

    }

}
