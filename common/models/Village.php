<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;


/**
 * This is the model class for table "{{%village}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $adr
 * @property string $province_name
 * @property integer $lead_donor_id
 * @property integer $district_id
 * @property integer $status
 * @property string $image
 * @property string $description
 * @property double $natural_area
 * @property double $arable_area
 * @property double $gdp
 * @property string $main_industry
 * @property string $main_product
 * @property integer $population
 * @property integer $poor_family
 * @property integer $no_house_family
 * @property integer $missing_classes
 * @property string $lighting_condition
 * @property string $water_condition
 * @property string $missing_playground
 * @property string $map_images
 * @property string $geographical_location
 * @property string $establish_date
 * @property string $file_upload
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $latitude
 * @property integer $longitude
 *
 * @property LeadDonor $leadDonor
 * @property Province $province
 */
class Village extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    //TRẠNG THÁI
    const STATUS_ACTIVE = 10;
    const STATUS_BLOCK = 1;
    const STATUS_DELETE = 4;

    public function getListStatus()
    {
        $list1 = [
            self::STATUS_ACTIVE => 'Hoạt động',
            self::STATUS_BLOCK => 'Tạm Dừng',
        ];

        return $list1;
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    public function getStatusName()
    {
        $lst = self::getListStatus();
        if (array_key_exists($this->status, $lst)) {
            return $lst[$this->status];
        }
        return $this->status;
    }

    public $maxSize = null;

    public static function tableName()
    {
        return '{{%village}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required', 'message' => 'Tên xã không được để trống'],
//            [['lead_donor_id'], 'required', 'message' => 'Doanh nghiệp đỡ đầu không được để trống'],
            [['district_id'], 'required', 'message' => 'Tỉnh không được để trống'],
            [['district_id', 'status', 'created_at', 'updated_at', 'lead_donor_id'], 'integer', 'message' => 'Vui lòng nhập số'],
            [['description', 'main_industry', 'main_product', 'geographical_location', 'map_images',
                'establish_date', 'population', 'poor_family', 'no_house_family', 'missing_classes',
                'arable_area', 'natural_area', 'gdp','adr'], 'string'],
            [['latitude', 'longitude'], 'number', 'message' => 'Vui lòng nhập số'],
            [['file_upload'], 'file', 'extensions' => ['png', 'gif', 'jpg', 'jpeg', 'doc', 'pdf', 'xlsx', 'docx', 'pptx', 'ppt', 'xls'], 'maxSize' => 1024 * 1024 * 10, 'tooBig' => 'File vượt quá dung lượng cho phép!'],
            [['image'], 'required', 'message' => 'Ảnh đại diện xã không được để trống', 'on' => 'create'],
            [['image'], 'file', 'extensions' => ['png', 'jpg', 'jpeg', 'gif'], 'maxSize' => 1024 * 1024 * 10, 'tooBig' => 'Ảnh đại diện vượt quá dung lượng cho phép!'],
            [['map_images'], 'file', 'extensions' => ['png', 'jpg', 'jpeg', 'gif'], 'maxSize' => 1024 * 1024 * 10, 'tooBig' => 'Ảnh đại diện vượt quá dung lượng cho phép!'],
            [['name', 'province_name', 'image', 'file_upload', 'lighting_condition', 'geographical_location', 'map_images', 'water_condition', 'missing_playground'], 'string', 'max' => 256],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Tên Xã'),
            'province_name' => Yii::t('app', 'Huyện'),
            'district_id' => Yii::t('app', 'Tỉnh'),
            'status' => Yii::t('app', 'Trạng thái'),
            'image' => Yii::t('app', 'Ảnh đại diện'),
            'description' => Yii::t('app', 'Mô tả'),
            'lead_donor_id' => Yii::t('app', 'Doanh nghiệp đỡ đầu'),
            'natural_area' => Yii::t('app', 'Diện tích đất tự nhiên'),
            'arable_area' => Yii::t('app', 'Diện tích đất canh tác'),
            'main_industry' => Yii::t('app', 'Ngành sản xuất chính'),
            'main_product' => Yii::t('app', 'Sản phẩm chủ lực'),
            'population' => Yii::t('app', 'Dân số'),
            'poor_family' => Yii::t('app', 'Số hộ nghèo'),
            'gdp' => Yii::t('app', 'Thu nhập bình quân trên đầu người'),
            'no_house_family' => Yii::t('app', 'Số hộ chưa có nhà kiên cố'),
            'missing_classes' => Yii::t('app', 'Số lớp học còn thiếu'),
            'lighting_condition' => Yii::t('app', 'Tình hình điện chiếu sáng'),
            'water_condition' => Yii::t('app', 'Tình hình cấp nước sinh hoạt'),
            'missing_playground' => Yii::t('app', 'Số sân chơi trẻ em, nhà văn hóa còn thiếu'),
            'created_at' => Yii::t('app', 'Ngày tạo mới'),
            'updated_at' => Yii::t('app', 'Ngày thay đổi thông tin'),
            'establish_date' => Yii::t('app', 'Ngày thành lập'),
            'map_images' => Yii::t('app', 'Bản đồ hành chính'),
            'file_upload' => Yii::t('app', 'Tệp thông tin'),
            'longitude' => Yii::t('app', 'Kinh độ'),
            'latitude' => Yii::t('app', 'Vĩ độ'),
            'geographical_location'=>Yii::t('app','Vị trí địa lý'),
            'adr'=>Yii::t('app','Nhập địa chỉ cụ thể để lấy kinh độ vĩ độ')
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLeadDonor()
    {
        return $this->hasOne(LeadDonor::className(), ['id' => 'lead_donor_id']);
    }


    public static function getArrayVillageByUser()
    {
        /** @var User $user */
        $user = Yii::$app->user->identity;
        $listVillage = Village::find();
        if ($user) {
            if ($user->lead_donor_id > 0) {
                $listVillage->andWhere(['lead_donor_id' => $user->lead_donor_id]);
            }
        }

        $rs = ArrayHelper::map($listVillage->all(), 'id', 'name');
        return $rs;
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

    public function getMapImageLink()
    {
        $pathLink = Yii::getAlias('@web') . '/' . Yii::getAlias('@village_image') . '/';
        $filename = null;

        if ($this->map_images) {
            $filename = $this->map_images;

        }
        if ($filename == null) {
            return null;
        }

        return Url::to($pathLink . $filename, true);

    }

    public function getImage()
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

    public function getFileUpload()
    {
        $pathLink = Yii::getAlias('@web') . '/' . Yii::getAlias('@file_upload') . '/';
        $filename = null;

        if ($this->file_upload) {
            $filename = $this->file_upload;

        }
        if ($filename == null) {
            $pathLink = Yii::getAlias("@web/img/");
            $filename = 'bg_df.png';
        }

        return Url::to($pathLink . $filename, true);
    }

    public static function getVillageByUser()
    {
        $village = Village::find()->andWhere(['status' => Village::STATUS_ACTIVE]);
        /** @var User $user */
        $user = Yii::$app->user->identity;
        if ($user) {
            if ($user->type == User::TYPE_LEAD_DONOR) {
                $village->andWhere(['lead_donor_id' => $user->lead_donor_id]);
            }
            if ($user->type == User::TYPE_VILLAGE) {
                $village->andWhere(['id' => $user->village_id]);
            }
        }

        return $village->all();
    }

}
