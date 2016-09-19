<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[Campaign]].
 *
 * @see Campaign
 */
class CampaignQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return Campaign[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Campaign|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
    public function findByCategory($categories){

        if(!$categories){
            return $this;
        }
        if(!is_array($categories)){
            $categories = explode(',',$categories);
        }
        $this->innerJoin('campaign_category_asm','campaign.id = campaign_category_asm.campaign_id')
            ->andWhere([ 'campaign_category_asm.category_id'=>$categories]);
        return $this;
    }
    public function findByPartner($partners){

        if(!$partners){
            return $this;
        }
        if(!is_array($partners)){
            $partners = explode(',',$partners);
        }

        $this->andWhere(['created_by'=>$partners]);
        return $this;
    }

    /**
     * @param $sort newest | urgent | popular
     */
    public function sortBy($sort){
        $sort = strtolower($sort);
        switch($sort){
            case 'newest':
                $this->orderBy('approved_at desc');
                break;
            case 'urgent':
                $this->orderBy('finished_at desc');
                break;
            case 'popular':
                $this->orderBy('follower_count desc');
                break;
        }
        return $this;
    }
}