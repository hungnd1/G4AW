<?php

namespace common\models;

use Yii;
use yii\helpers\Url;

/**
 * This is the model class for table "news".
 *
 * @property integer $id
 * @property string $title
 * @property string $short_description
 * @property string $description
 * @property string $content
 * @property string $video_url
 * @property string $image
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $status
 * @property integer $view_count
 * @property integer $like_count
 * @property integer $comment_count
 * @property integer $is_slide
 */
class News extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'news';
    }

    const STATUS_ACTIVE = 10;
    const STATUS_INACTIVE = 0;

    const SLIDE = 1;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'short_description'], 'required'],
            [['image'], 'required', 'on' => 'admin_create_update'],
            [['description', 'image', 'content'], 'string'],
            [['created_at', 'updated_at', 'status', 'category_id', 'is_slide', 'comment_count', 'like_count', 'view_count'], 'integer'],
            [['title', 'video_url', 'short_description'], 'string', 'max' => 500],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Tiêu đề',
            'image' => 'Ảnh đại diện',
            'short_description' => 'Mô tả ngắn',
            'description' => 'Mô tả',
            'content' => 'Nội dung',
            'created_at' => 'Ngày tạo',
            'updated_at' => 'Updated At',
            'status' => 'Trạng thái',
            'category_id' => 'Danh mục',
            'is_slide' => 'Là slide',
        ];
    }

    public static function listStatus()
    {
        $lst = [
            self::STATUS_ACTIVE => \Yii::t('app', 'Kích hoạt'),
            self::STATUS_INACTIVE => \Yii::t('app', 'Tạm dừng'),
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

    public static function getListStatusNameByStatus($status)
    {
        $lst = self::listStatus();
        if (array_key_exists($status, $lst)) {
            return $lst[$status];
        }
        return $status;
    }

    public function getImageLink()
    {
        return $this->image ? Url::to(Yii::getAlias('@web') . DIRECTORY_SEPARATOR . Yii::getAlias('@news_image') . DIRECTORY_SEPARATOR . $this->image, true) : '';
        // return $this->images ? Url::to('@web/' . Yii::getAlias('@cat_image') . DIRECTORY_SEPARATOR . $this->images, true) : '';
    }

    public function getVideoUrl()
    {
        $pathLink = Yii::getAlias('@web') . '/' . Yii::getAlias('@news_video') . '/';
        $filename = null;
        if ($this->video_url) {
            $filename = $this->video_url;
        }
        return Url::to($pathLink . $filename, true);
    }

}
