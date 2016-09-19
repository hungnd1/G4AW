<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\helpers\Url;

/**
 * This is the model class for table "{{%lead_donor}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $address
 * @property string $website
 * @property integer $status
 * @property string $image
 * @property string $video
 * @property string $description
 * @property string $phone
 * @property string $email
 * @property integer $created_at
 * @property integer $updated_at
 */
class LeadDonor extends \yii\db\ActiveRecord
{
    //TRẠNG THÁI
    const STATUS_ACTIVE = 10;
    const STATUS_BLOCK = 1;
    const STATUS_DELETE = 4;


    public static function getListStatus()
    {
        $list1 = [
            self::STATUS_ACTIVE => 'Hoạt động',
            self::STATUS_BLOCK => 'Tạm Dừng',
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
        return '{{%lead_donor}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required', 'message' => 'Tên doanh nghiệp đỡ đầu không được để trống '],
            [['status', 'created_at', 'updated_at'], 'integer',],
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            [['description'], 'string'],
            [['image'], 'file', 'extensions' => ['png', 'jpeg', 'jpg', 'gif'], 'maxSize' => 1024 * 1024 * 10, 'tooBig' => 'Ảnh đại diện vượt quá dung lượng cho phép!'],
//            [['video'], 'file', 'extensions' => ['png', 'jpeg', 'jpg', 'gif'], 'maxSize' => 1024 * 1024 * 10, 'tooBig' => 'Ảnh đại diện vượt quá dung lượng cho phép!'],
            [['video'], 'file', 'extensions' => ['mp4', 'avi'], 'maxSize' => 1024 * 1024 * 100, 'tooBig' => 'Video giới thiệu vượt quá dung lượng cho phép!'],
            [['name', 'address', 'website', 'image'], 'string', 'max' => 256],
            [['phone'], 'string', 'max' => 20],
            [['email'], 'string', 'max' => 64],
            [['email'], 'email', 'message' => 'Địa chỉ email không hợp lệ!']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Tên'),
            'address' => Yii::t('app', 'Địa chỉ'),
            'website' => Yii::t('app', 'Website'),
            'status' => Yii::t('app', 'Trạng thái'),
            'image' => Yii::t('app', 'Ảnh đại diện'),
            'description' => Yii::t('app', 'Mô tả'),
            'phone' => Yii::t('app', 'Số điện thoại'),
            'email' => Yii::t('app', 'Email'),
            'video' => Yii::t('app', 'Video giới thiệu'),
            'created_at' => Yii::t('app', 'Ngày tạo'),
            'updated_at' => Yii::t('app', 'Ngày thay đổi thông tin'),
        ];
    }

    public function getImageLink()
    {
        $pathLink = Yii::getAlias('@web') . '/' . Yii::getAlias('@lead_donor_image') . '/';
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

    public static function getLeadDonorByUser()
    {
        $lead_donor = LeadDonor::find();
        /** @var User $user */
        $user = Yii::$app->user->identity;
        if ($user) {
            if ($user->type == User::TYPE_LEAD_DONOR) {
                $lead_donor->andWhere(['id' => $user->lead_donor_id]);
            }
        }

        return $lead_donor->all();
    }

    public function _substr($str, $length, $minword = 3)
    {
        $sub = '';
        $len = 0;
        foreach (explode(' ', $str) as $word) {
            $part = (($sub != '') ? ' ' : '') . $word;
            $sub .= $part;
            $len += strlen($part);
            if (strlen($word) > $minword && strlen($sub) >= $length) {
                break;
            }
        }
        return $sub . (($len < strlen($str)) ? '...' : '');
    }

    public function getVideoUrl()
    {
        return Yii::getAlias('@web') . '/' . Yii::getAlias('@lead_donor_video') . '/' . $this->video;
    }
}
