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
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $status
 * @property string $image
 * @property integer $category_id
 * @property string $video_url
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
            [['title'], 'required'],
            [['description', 'content'], 'string'],
            [['created_at', 'updated_at', 'status', 'category_id', 'view_count', 'like_count', 'comment_count', 'is_slide'], 'integer'],
            [['title', 'short_description', 'image', 'video_url'], 'string', 'max' => 500],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'short_description' => 'Short Description',
            'description' => 'Description',
            'content' => 'Content',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'status' => 'Status',
            'image' => 'Image',
            'category_id' => 'Category ID',
            'video_url' => 'Video Url',
            'view_count' => 'View Count',
            'like_count' => 'Like Count',
            'comment_count' => 'Comment Count',
            'is_slide' => 'Is Slide',
        ];
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

    public function getThumbnailLink()
    {
        $pathLink = Yii::getAlias('@web') . '/' . Yii::getAlias('@uploads') . '/';
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
