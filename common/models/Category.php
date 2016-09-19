<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%category}}".
 *
 * @property integer $id
 * @property string $display_name
 * @property integer $type
 * @property string $description
 * @property integer $status
 * @property integer $order_number
 * @property integer $parent_id
 * @property string $path
 * @property integer $level
 * @property integer $child_count
 * @property string $images
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property CampaignCategoryAsm[] $campaignCategoryAsms
 * @property Category $parent
 * @property Category[] $categories
 */
class Category extends \yii\db\ActiveRecord
{
    const STATUS_ACTIVE = 10;
    const STATUS_INACTIVE = 0;
    const STATUS_DELETED = 2;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%category}}';
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
            [['display_name'], 'required'],
//            [['display_name'], 'unique', 'message' => 'Tên danh mục đã tồn tại. Vui lòng chọn tên khác!'],
            [['id', 'type', 'status', 'order_number', 'parent_id', 'level',
                'child_count', 'created_at', 'updated_at'], 'integer'],
            [['description'], 'string'],
            [['display_name', 'path'], 'string', 'max' => 200],
            [['images'], 'string', 'max' => 500]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'display_name' => Yii::t('app', 'Tên danh mục'),
            'type' => Yii::t('app', 'Type'),
            'description' => Yii::t('app', 'Mô tả'),
            'status' => Yii::t('app', 'Trạng thái'),
            'order_number' => Yii::t('app', 'Order Number'),
            'parent_id' => Yii::t('app', 'Parent ID'),
            'path' => Yii::t('app', 'Path'),
            'level' => Yii::t('app', 'Level'),
            'child_count' => Yii::t('app', 'Child Count'),
            'images' => Yii::t('app', 'Images'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCampaignCategoryAsms()
    {
        return $this->hasMany(CampaignCategoryAsm::className(), ['category_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(Category::className(), ['id' => 'parent_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategories()
    {
        return $this->hasMany(Category::className(), ['parent_id' => 'id']);
    }

    /**
     * @return array
     */
    public static function listStatus()
    {
        $lst = [
            self::STATUS_ACTIVE => 'Hoạt động',
            self::STATUS_INACTIVE => 'Tạm dừng',
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

    public static function getSubCategories()
    {
        return static::find()
            ->andWhere(['status' => Category::STATUS_ACTIVE])
            ->orderBy(['order_number' => SORT_ASC])->all();
    }
}
