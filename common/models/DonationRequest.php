<?php

namespace common\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\helpers\Url;

/**
 * This is the model class for table "{{%donation_request}}".
 *
 * @property integer $id
 * @property string $title
 * @property string $short_description
 * @property integer $type
 * @property string $content
 * @property double $expected_amount
 * @property integer $status
 * @property string $admin_note
 * @property double $current_amount
 * @property string $currency
 * @property integer $approved_at
 * @property integer $created_by
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $organization_id
 * @property integer $village_id
 * @property integer $lead_donor_id
 * @property string $expected_items
 * @property string $image
 *
 * @property Campaign[] $campaigns
 * @property User $organization
 * @property User $createdBy
 * @property LeadDonor $leadDonor
 * @property Village $village
 * @property RequestGallery[] $requestGalleries
 */
class DonationRequest extends \yii\db\ActiveRecord
{
    const STATUS_NEW = 0; // Chờ duyệt
    const STATUS_EXPIRED = 1; //
    const STATUS_INACTIVE = 2; //
    const STATUS_REJECTED = 5; // Từ chối
    const STATUS_APPROVED = 9; // Đã tạo chiến dịch
    const STATUS_ACTIVE = 10; // Đã Duyệt
    const STATUS_DELETED = 4; // Xóa

    const TYPE_MONEY = 1;
    const TYPE_ITEM = 2;

    public $image_update;
    public $imageAsms;
    public $village_name;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%donation_request}}';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
            [
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => 'created_by',
                'updatedByAttribute' => false
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title','village_id','image'], 'required'],
            [['type', 'status', 'organization_id', 'approved_at', 'created_by',
                'created_at', 'updated_at', 'village_id', 'lead_donor_id'], 'integer'],
            [['content','village_name'], 'string'],
            ['expected_items', 'default', 'value' => '0'],
            ['expected_amount', 'default', 'value' => '0'],
            [['expected_amount', 'current_amount'], 'number'],
            [['title'], 'string', 'max' => 1024],
            [['short_description','expected_items'], 'string', 'max' => 500],
            [['admin_note'], 'string', 'max' => 4000],
            [['currency'], 'string', 'max' => 20],
            [['image'], 'safe'],
            [['image','image_update'], 'file', 'extensions' => ['png', 'jpg', 'gif','jpeg'], 'maxSize' => 1024 * 1024 * 10, 'tooBig' => 'Ảnh upload vượt quá dung lượng cho phép!'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'title' => Yii::t('app', 'Tiêu đề'),
            'short_description' => Yii::t('app', 'Mô tả ngắn'),
            'type' => Yii::t('app', 'Loại yêu cầu'),
            'content' => Yii::t('app', 'Nội dung hoàn cảnh'),
            'expected_amount' => Yii::t('app', 'Số tiền cần hỗ trợ'),
            'status' => Yii::t('app', 'Trạng thái'),
            'admin_note' => Yii::t('app', 'Lý do từ chối'),
            'current_amount' => Yii::t('app', 'Current Amount'),
            'currency' => Yii::t('app', 'Currency'),
            'approved_at' => Yii::t('app', 'Approved At'),
            'created_by' => Yii::t('app', 'Created By'),
            'image' => Yii::t('app', 'Ảnh đại diện'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'village_id' => Yii::t('app', 'Xã bạn sinh sống'),
            'expected_items' => Yii::t('app', 'Vật phẩm cần hỗ trợ'),
            'image_update' => Yii::t('app', 'Ảnh đại diện'),
        ];
    }

    /**
     * @return array
     */
    public static function listStatus()
    {
        $lst = [
//            self::STATUS_ACTIVE => 'Đã Duyệt',
            self::STATUS_NEW => 'Chờ duyệt',
            self::STATUS_REJECTED => 'Từ chối',
//            self::STATUS_EXPIRED => 'Hết hạn',
            self::STATUS_APPROVED => 'Đã tạo chiến dịch',
//            self::STATUS_DELETED => 'Đã xóa'
        ];
        return $lst;
    }

    /**
     * @return int
     */

    public function getStatusName()
    {
        $lst = self::listStatus();
        if (array_key_exists($this->status, $lst)) {
            return $lst[$this->status];
        }
        return $this->status;
    }

//    /**
//     * @return array
//     */
//    public function getVillage()
//    {
//        return $this->hasOne(Village::className(), ['id' => 'village_id']);
//    }

    public static function listType()
    {
        $lst = [
            self::TYPE_ITEM => 'Cá nhân',
            self::TYPE_MONEY => 'Cộng đồng',
        ];
//        echo "<pre>";
//        print_r($lst);
//        die();
        return $lst;
    }

    /**
     * @return int
     */
    public function getTypeName()
    {
        $lst = self::listType();
        if (array_key_exists($this->type, $lst)) {
            return $lst[$this->type];
        }
        return $this->type;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCampaigns()
    {
        return $this->hasMany(Campaign::className(), ['donation_request_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVillage()
    {
        return $this->hasOne(Village::className(), ['id' => 'village_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrganization()
    {
        return $this->hasOne(User::className(), ['id' => 'organization_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLeadDonor()
    {
        return $this->hasOne(LeadDonor::className(), ['id' => 'lead_donor_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRequestGalleries()
    {
        return $this->hasMany(RequestGallery::className(), ['donation_request_id' => 'id']);
    }

    public function getImagesForDropzone()
    {
        if ($this->requestGalleries && count($this->requestGalleries) > 0) {
            $data = [];
            $requestImage = Yii::$app->params['upload_images'];
            $pathLink = Yii::getAlias("@web/" . $requestImage . "/");
            foreach ($this->requestGalleries as $item) {
                $row = [];
                $row['name'] = $item->name;
                $row['thumbnail'] = $pathLink . $item->name;
                $data[] = $row;
            }
        } else {
            return [];
        }

        return $data;
    }

    public function assignImages()
    {
        if (!is_array($this->imageAsms) && trim($this->imageAsms) !== '') {
            $images = explode(',', $this->imageAsms);
            $deleteImageGalleries = RequestGallery::find()->andWhere(['donation_request_id' => $this->id])->andFilterWhere([
                'not in',
                'name',
                $images
            ])->all();
            foreach ($deleteImageGalleries as $item) {
                $item->delete();
            }
            foreach ($images as $imageName) {
                $oldAsm = RequestGallery::find()->andWhere(['donation_request_id' => $this->id])->andWhere(['name' => $imageName])->one();
                if ($oldAsm) {
                    continue;
                }
                $requestGallery = new RequestGallery();
                $requestGallery->donation_request_id = $this->id;
                $requestGallery->name = $imageName;
                $requestGallery->alt = $imageName;
                if (!$requestGallery->save()) {
                    return false;
                }
            }

            return true;
        }

        return true;
    }

    public function loadImageAsm()
    {
        if ($this->requestGalleries && count($this->requestGalleries) > 0) {
            $data = [];

            foreach ($this->requestGalleries as $item) {


                $data[] = $item->name;
            }
        } else {
            $data = [];
        }

        return implode(',', $data);
    }

    public static function getRequestFrom($userId,$organizationId=null,$create=true)
    {
        $query = DonationRequest::find()->select(['id', 'title as  name'])
            ->andWhere(['created_by' => $userId]);


        $query->andFilterWhere(['organization_id'=>$organizationId]);
        if($create){
            $query->andWhere(['status' => self::STATUS_NEW])
                ->andWhere(' not EXISTS (select * from campaign where campaign.donation_request_id = donation_request.id)');
        }
        $data = $query->asArray()->all();
        return $data;
    }

    public function getThumbnailLink()
    {
        $pathLink = Yii::getAlias('@web') . '/' . Yii::getAlias('@donation_uploads') . '/';
        $filename = null;

        if ($this->image) {
            $filename = $this->image;

        }
        if ($filename == null) {
            $pathLink = Yii::getAlias("@web/img/");
            $filename = 'bg_df.png';
        }

        return Url::to($pathLink . $filename, true);
//        return $this->image ? Yii::getAlias('@web') . "/" . Yii::getAlias('@uploads') . "/" . $this->image : '';
//        $imagePath = Yii::$app->params['upload_images'];
//        $pathLink = Yii::getAlias("@web/" . $imagePath . "/");
//        $filename = null;
//
//        /** @var RequestGallery $gallery */
//        foreach ($this->requestGalleries as $gallery) {
//            $filename = $gallery->name;
//            break;
//        }
//        if ($filename == null) {
//            $pathLink = Yii::getAlias("@web/img/");
//            $filename = 'bg_df.png';
//        }
//
//        return $pathLink . $filename;
    }
    public function approveHandler(){
        if($this->status == self::STATUS_NEW){
            $this->status= self::STATUS_APPROVED;
            $this->approved_at = time();
        } else{
            return 1;
        }

        return $this->save();
    }

    public function rejectHandler(){
        if($this->status == self::STATUS_NEW){
            $this->status= self::STATUS_REJECTED;
        } else{
            return 1;
        }

        return $this->save();
    }

}
