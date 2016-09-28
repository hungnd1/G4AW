<?php

namespace common\models;

use Yii;
use yii\helpers\Url;

/**
 * This is the model class for table "village".
 *
 * @property integer $id
 * @property string $name
 * @property string $name_en
 * @property integer $number_code
 * @property integer $id_province
 * @property string $latitude
 * @property string $longitude
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $image
 * @property string $description
 * @property string $description_en
 * @property integer $status
 */
class Village extends \yii\db\ActiveRecord
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
        return 'village';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name','name_en', 'id_province', 'image', 'status'], 'required'],
            [['number_code', 'id_province', 'status', 'created_at', 'updated_at'], 'integer'],
            [['description','description_en'], 'string'],
            [['name','name_en'], 'string', 'max' => 200],
            [['latitude', 'longitude'], 'string', 'max' => 10],
            [['image'], 'string', 'max' => 500],
            [['image'], 'required', 'message' => 'Ảnh đại diện xã không được để trống', 'on' => 'create'],
            [['image'], 'file', 'extensions' => ['png', 'jpg', 'jpeg', 'gif'], 'maxSize' => 1024 * 1024 * 10, 'tooBig' => 'Ảnh đại diện vượt quá dung lượng cho phép!'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Tên xã',
            'name_en' => 'Tên xã tiếng anh',
            'number_code' => 'Mã code',
            'id_province' => 'Tỉnh',
            'latitude' => 'Vĩ độ',
            'longitude' => 'Kinh độ',
            'image' => 'Ảnh đại diện xã',
            'description' => 'Mô tả',
            'description_en' => 'Mô tả tiếng anh',
            'status' => 'Trạng thái',
            'created_at' => Yii::t('app', 'Ngày tạo mới'),
            'updated_at' => Yii::t('app', 'Ngày thay đổi thông tin'),
        ];
    }

    public function getImageLink()
    {
        $pathLink = Yii::getAlias('@web') . '/' . Yii::getAlias('@village_image') . '/';
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
