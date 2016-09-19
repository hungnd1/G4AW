<?php
/**
 * Created by PhpStorm.
 * User: VS9 X64Bit
 * Date: 25/02/2015
 * Time: 3:45 PM
 */

namespace api\models;

use common\models\Campaign;

class CampaignItem extends Campaign
{
    public function fields()
    {
        return [
            'id',
            'donation_request_id',
            'name',
            'short_description',
            'description',
            'thumbnail' => function ($model) {
                /** @var $model Campaign */
                return $model->getThumbnailLink();
            },
            'campaign_code',
            'type',
            'tags',
            'content'=>function($model){
                /** @var $model Campaign */
                return $model->getContent();
            },
            'view_count',
            'like_count',
            'comment_count',
            'follower_count',
            'status',
            'admin_note',
            'expected_amount',
            'current_amount',
            'donor_count',
            'honor',
            'created_by_id' => function ($model) {
                /** @var $model Campaign */
                return $model->created_by;
            },
            'created_by_name' => function ($model) {
                /** @var $model Campaign */
                return $model->createdBy ? $model->createdBy->fullname : "";
            },
            'created_by_address' => function ($model) {
                /** @var $model Campaign */
                return $model->createdBy ? $model->createdBy->address : "";
            },
        ];
    }


}