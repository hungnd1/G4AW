<?php

namespace common\models;

use Yii;
use yii\helpers\Url;

/**
 * This is the model class for table "unit_link".
 *
 * @property integer $id
 * @property string $name;
 * @property string $link
 * @property string $image
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 */
class UnitLink extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */

    const STATUS_ACTIVE = 10;
    CONST STATUS_INACTIVE = 0;

    public static function tableName()
    {
        return 'unit_link';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
                 [['status','name'], 'required'],
                 [['created_at', 'updated_at'], 'integer'],
                 [['link', 'image','name'], 'string', 'max' => 500],
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
            'name'=>'Tên đơn vị',
            'link' => 'Đường dẫn liên kết',
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
        $pathLink = Yii::getAlias('@web') . '/' . Yii::getAlias('@file_upload') . '/';
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
