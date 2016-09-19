<?php
/**
 * Created by PhpStorm.
 * User: VS9 X64Bit
 * Date: 25/02/2015
 * Time: 3:45 PM
 */

namespace api\models;

use common\models\User;

class NewsItem extends \common\models\News
{
    public function fields()
    {
        return [
            'id',
            'title',
            'thumbnail' => function ($model) {
                /** @var $model News */
                return $model->getThumbnailLink();
            },
            'content' => function ($model) {
                /** @var $model News */
                return $model->getContent();
            },
            'short_description',
            'view_count',
            'like_count',
            'status',
            'created_at',
            'campaign_id',
            'campaign_name' => function ($model) {
                /** @var $model News */
                return $model->campaign ? $model->campaign->name : "";
            }
        ];
    }


}