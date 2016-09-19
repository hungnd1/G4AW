<?php
/**
 * Created by PhpStorm.
 * User: VS9 X64Bit
 * Date: 22/05/2015
 * Time: 2:28 PM
 */
namespace api\controllers;

use api\models\CampaignItem;
use api\models\CategoryItem;
use api\models\News;
use api\models\NewsItem;
use common\models\Campaign;
use common\models\User;
use yii\web\NotFoundHttpException;

class CampaignController extends ApiController
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator']['except'] = [
            'index',
            'view',
            'news',
            'view-news',
        ];

        return $behaviors;
    }

    protected function verbs()
    {
        return [
            'index' => ['GET'],
            'view' => ['GET'],
            'news' => ['GET'],
            'view-news' => ['GET'],
        ];
    }

    public function actionIndex($category_id = 0, $created_by = 0, $honor = 0, $user_type = User::TYPE_DONOR)
    {
        return CampaignItem::getCampaignList($category_id, $created_by, $honor, $user_type);
    }

    public function actionView($id)
    {
        /** @var Campaign $campaign */
        $campaign = CampaignItem::findOne(['id' => $id]);
        if ($campaign) {
            $campaign->view_count++;
            $campaign->save();
            return $campaign;
        } else {
            throw new NotFoundHttpException("Không tìm thấy chiến dịch");
        }
    }

    public function actionNews($campaign_id = 0){
        return NewsItem::listNews($campaign_id);
    }

    public function actionViewNews($id){
        /** @var \common\models\News $news */
       $news = NewsItem::findOne(['id'=>$id]);
        if ($news) {
            $news->view_count++;
            $news->save();
            return $news;
        } else {
            throw new NotFoundHttpException("Không tìm thấy bài viết");
        }
    }
}