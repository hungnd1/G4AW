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
 * @property string $title_en
 * @property string $content_en
 * @property integer $area_id
 * @property string $short_description_en
 * @property string $source_name
 * @property string $source_url
 * @property integer $status
 * @property integer $created_user_id
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $published_at
 * @property integer $user_id
 * @property integer $category_id
 *
 * @property User $user
 * @property Category $category
 * @property NewsCategoryAsm $newsCategoryAsms
 */
class News extends \yii\db\ActiveRecord
{
    const STATUS_NEW = 1;
    const STATUS_ACTIVE = 10;
    const STATUS_INACTIVE = 0;
    const STATUS_DELETED = 2;

    const LIST_EXTENSION = '.jpg,.png';

    const TYPE_KNOW = 1;
    const TYPE_MARKET = 2;
    const TYPE_HEALTH = 3;
    const TYPE_NEW = 5;

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



    public function spStatus($newStatus, $sp_id)
    {
        $this->status = $newStatus;
        $this->updated_at = time();
        return $this->update(false);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [[ 'type', 'view_count', 'like_count', 'comment_count', 'favorite_count', 'honor',
                'status', 'created_user_id', 'created_at', 'updated_at', 'user_id','area_id', 'category_id', 'published_at'], 'integer'],
            [['title','title_en', 'user_id'], 'required'],
            [['thumbnail'], 'required', 'on' => 'create'],
            [['content','content_en', 'description'], 'string'],
            [['title', 'title_ascii', 'thumbnail'], 'string', 'max' => 512],
            [['tags', 'source_name', 'source_url'], 'string', 'max' => 200],
            [['short_description','short_description_en', 'video_url'], 'string', 'max' => 500],
            [['thumbnail'], 'image', 'extensions' => 'png,jpg,jpeg,gif',
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
            'id' => Yii::t('app', 'ID'),
            'title' => Yii::t('app', 'Tiêu đề'),
            'title_en' => Yii::t('app', 'Tiêu đề tiếng anh'),
            'title_ascii' => Yii::t('app', 'Title Ascii'),
            'content' => Yii::t('app', 'Nội dung'),
            'content_en' => Yii::t('app', 'Nội dung tiếng anh'),
            'thumbnail' => Yii::t('app', 'Ảnh đại diện'),
            'type' => Yii::t('app', 'Loại bài viết'),
            'area_id' => Yii::t('app', 'Vùng'),
            'tags' => Yii::t('app', 'Tags'),
            'short_description' => Yii::t('app', 'Mô tả ngắn'),
            'short_description_en' => Yii::t('app', 'Mô tả ngắn tiếng anh'),
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
            self::TYPE_NEW => 'Tin tức',
            self::TYPE_HEALTH => 'Sức khỏe đời sống',
            self::TYPE_MARKET => 'Chợ nhà nông',
            self::TYPE_KNOW => 'Nhà nông nên biết',
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


    public function getCategory()
    {
        /** @var NewsCategoryAsm[] $asm */
        $asm = NewsCategoryAsm::find()->andWhere(['news_id' => $this->id])->all();
        $rs = '';
        foreach ($asm as $item) {
            $category = Category::findOne(['id'=>$item->category_id]);
            $rs .= $category->display_name . ',';
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
