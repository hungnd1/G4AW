<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;

/**
 * This is the model class for table "{{%news}}".
 *
 * @property integer $id
 * @property integer $campaign_id
 * @property string $title
 * @property string $title_ascii
 * @property string $content
 * @property string $thumbnail
 * @property integer $type
 * @property string $tags
 * @property string $short_description
 * @property string $description
 * @property string $video_url
 * @property integer $view_count
 * @property integer $like_count
 * @property integer $comment_count
 * @property integer $favorite_count
 * @property integer $honor
 * @property string $source_name
 * @property string $source_url
 * @property integer $status
 * @property integer $created_user_id
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $published_at
 * @property integer $user_id
 * @property integer $all_village
 * @property integer $lead_donor_id
 * @property string $price
 * @property integer $category_id
 *
 * @property Campaign $campaign
 * @property LeadDonor $leadDonor
 * @property User $user
 * @property NewsCategoryAsm $newsCategoryAsms
 */
class News extends \yii\db\ActiveRecord
{
    const STATUS_NEW = 1;
    const STATUS_ACTIVE = 10;
    const STATUS_INACTIVE = 0;
    const STATUS_DELETED = 2;

    const LIST_EXTENSION = '.jpg,.png';

    const TYPE_IDEA = 1;
    const TYPE_TRADE = 2;
    const TYPE_DONOR = 3;
    const TYPE_VILLAGE = 4;
    const TYPE_COMMON = 5;
    const TYPE_CAMPAIGN = 6;

    public $village_array;
    public $category_id;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%news}}';
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
    public function rules()
    {
        return [
            [['campaign_id', 'type', 'view_count', 'like_count', 'comment_count', 'favorite_count', 'honor',
                'status', 'created_user_id', 'created_at', 'updated_at', 'user_id',
                'all_village', 'lead_donor_id', 'category_id', 'published_at'], 'integer'],
            [['title', 'user_id'], 'required'],
            [['thumbnail'], 'required', 'on' => 'create'],
            [['content', 'description'], 'string'],
            [['title', 'title_ascii', 'thumbnail'], 'string', 'max' => 512],
            [['tags', 'source_name', 'source_url', 'price'], 'string', 'max' => 200],
            [['short_description', 'video_url'], 'string', 'max' => 500],
            [['thumbnail'], 'image', 'extensions' => 'png,jpg,jpeg,gif',
                'maxSize' => 1024 * 1024 * 10, 'tooBig' => 'Ảnh upload vượt quá dung lượng cho phép!'
            ],

            [['village_array', 'price'], 'required', 'when' => function ($model) {
                return $model->type == self::TYPE_TRADE;
            },
                'whenClient' => "function (attribute, value) {
                    return $('#type').val() == '" . self::TYPE_TRADE . "';
                }",
                'on' => ['create', 'update']
            ],

            [['village_array'], 'required', 'when' => function ($model) {
                return $model->type == self::TYPE_IDEA;
            },
                'whenClient' => "function (attribute, value) {
                    return $('#type').val() == " . self::TYPE_IDEA . ";
                }",
                'on' => ['create', 'update']
            ],

            [['campaign_id'], 'required', 'when' => function ($model) {
                return $model->type == self::TYPE_CAMPAIGN;
            },
                'whenClient' => "function (attribute, value) {
                    return $('#type').val() == " . self::TYPE_CAMPAIGN . ";
                }",
                'on' => ['create', 'update']
            ],

            [['lead_donor_id'], 'required', 'when' => function ($model) {
                return $model->type == self::TYPE_DONOR;
            },
                'whenClient' => "function (attribute, value) {
                    return $('#type').val() == " . self::TYPE_DONOR . ";
                }", 'on' => ['create', 'update']
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'campaign_id' => Yii::t('app', 'Chiến dịch'),
            'title' => Yii::t('app', 'Tiêu đề'),
            'title_ascii' => Yii::t('app', 'Title Ascii'),
            'content' => Yii::t('app', 'Nội dung'),
            'thumbnail' => Yii::t('app', 'Ảnh đại diện'),
            'type' => Yii::t('app', 'Loại bài viết'),
            'tags' => Yii::t('app', 'Tags'),
            'short_description' => Yii::t('app', 'Mô tả ngắn'),
            'description' => Yii::t('app', 'Mô tả'),
            'video_url' => Yii::t('app', 'Video Url'),
            'view_count' => Yii::t('app', 'View Count'),
            'like_count' => Yii::t('app', 'Like Count'),
            'comment_count' => Yii::t('app', 'Comment Count'),
            'favorite_count' => Yii::t('app', 'Favorite Count'),
            'honor' => Yii::t('app', 'Honor'),
            'source_name' => Yii::t('app', 'Source Name'),
            'source_url' => Yii::t('app', 'Source Url'),
            'status' => Yii::t('app', 'Trạng thái'),
            'created_user_id' => Yii::t('app', 'Created User ID'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'user_id' => Yii::t('app', 'User ID'),
            'price' => Yii::t('app', 'Giá'),
            'village_array' => Yii::t('app', 'Xã'),
            'lead_donor_id' => Yii::t('app', 'Danh nghiệp đỡ đầu'),
            'category_id' => Yii::t('app', 'Danh mục'),
        ];
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNewsCategoryAsms()
    {
        return $this->hasMany(NewsCategoryAsm::className(), ['news_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCampaign()
    {
        return $this->hasOne(Campaign::className(), ['id' => 'campaign_id']);
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
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'created_user_id']);
    }

    public function getThumbnailLink()
    {
        $pathLink = Yii::getAlias('@web') . '/' . Yii::getAlias('@uploads') . '/';
        $filename = null;

        if ($this->thumbnail) {
            $filename = $this->thumbnail;

        }
        if ($filename == null) {
            $pathLink = Yii::getAlias("@web/img/");
            $filename = 'bg_df.png';
        }

        return Url::to($pathLink . $filename, true);

    }

    /**
     * @return array
     */
    public static function listStatus()
    {
        $lst = [
            self::STATUS_NEW => 'Soạn thảo',
            self::STATUS_ACTIVE => 'Hoạt động',
            self::STATUS_INACTIVE => 'Tạm dừng',
        ];
        return $lst;
    }

    /**
     * @return array
     */
    public static function listType()
    {
        $lst = [
            self::TYPE_COMMON => 'Tin tức chung',
            self::TYPE_IDEA => 'Tin tức ý tưởng',
            self::TYPE_DONOR => 'Tin tức DN đỡ đầu',
            self::TYPE_TRADE => 'Tin tức giao thương',
            self::TYPE_CAMPAIGN => 'Tin tức chiến dịch',
        ];
        return $lst;
    }

    public function getTypeName()
    {
        $lst = self::listType();
        if (array_key_exists($this->type, $lst)) {
            return $lst[$this->type];
        }
        return $this->type;
    }

    public static function getNameByType($type)
    {
        $lst = self::listType();
        if (array_key_exists($type, $lst)) {
            return $lst[$type];
        }
        return $type;
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

    public function getImage()
    {
        $image = $this->thumbnail;
        if ($image) {
            return Url::to(Yii::getAlias('@web') . '/' . Yii::getAlias('@uploads') . '/' . $image, true);
        }
    }

    public function getContent()
    {
        $content = str_replace("/uploads/ckeditor/", Yii::$app->params['ApiAddress'] . "/uploads/ckeditor/", $this->content);
        return $content;
    }

    public static function listNews($campaign_id = 0)
    {
        $query = static::find()->andWhere(['status' => News::STATUS_ACTIVE]);

        if ($campaign_id > 0) {
            $query->andWhere(['campaign_id' => $campaign_id]);
        }

        $provider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'defaultPageSize' => 20,
            ],
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_DESC,
                ]
            ],
        ]);
        return $provider;
    }

    public function getListVillage()
    {
        /** @var NewsVillageAsm[] $asm */
        $asm = NewsVillageAsm::find()->andWhere(['news_id' => $this->id])->all();
        $rs = '';
        foreach ($asm as $item) {
            $rs .= $item->village->name . ',';
        }
        $rs = $rs ? substr($rs, 0, strlen($rs) - 1) : $rs;
        return $rs;
    }

    public function getListVillageSelect2()
    {
        /** @var NewsVillageAsm[] $asm */
        $asm = NewsVillageAsm::find()->andWhere(['news_id' => $this->id])->all();
        $lst = [];
        foreach ($asm as $item) {
            $lst[] = $item->village_id;
        }

        return $lst;
    }
}
