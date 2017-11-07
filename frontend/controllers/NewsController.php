<?php

namespace frontend\controllers;

use common\models\Campaign;
use common\models\CampaignDonationItemAsm;
use common\models\Category;
use common\models\Comment;
use common\models\DonationItem;
use common\models\DonationRequest;
use common\models\News;
use common\models\Subscriber;
use Yii;
use yii\data\Pagination;
use yii\web\NotFoundHttpException;

/**
 * DonationRequestController implements the CRUD actions for DonationRequest model.
 */
class NewsController extends BaseController
{


    /**
     * Displays a single DonationRequest model.
     * @param integer $id
     * @return mixed
     */
    public function actionIndex($active = 1)
    {
        $title = "GAPs";
//            $listNews = News::find()
//                ->andWhere(['news.status' => News::STATUS_ACTIVE]);
//            $listNews->orderBy(['news.created_at' => SORT_DESC]);
//            $countQuery = clone $listNews;
//            $pages = new Pagination(['totalCount' => $countQuery->count()]);
//            $pageSize = Yii::$app->params['page_size'];
//            $pages->setPageSize($pageSize);
//            $models = $listNews->offset($pages->offset)
//                ->limit(10)->all();
        $listNewRelated = News::find()
            ->andWhere(['news.status' => News::STATUS_ACTIVE])
            ->orderBy(['news.created_at' => SORT_DESC])->offset(10)->limit(5)->all();
//            return $this->render('index', ['title' => $title, 'listNews' => $models, 'pages' => $pages, 'listNewRelated' => $listNewRelated]);
        $listCategory = Category::find()->andWhere(['status' => Category::STATUS_ACTIVE])
            ->orderBy(['order_number' => SORT_ASC])->all();
        return $this->render('index', ['active'=>$active,'title' => $title, 'listCategory' => $listCategory, 'listNewRelated' => $listNewRelated]);
    }


    public function actionDetail($id)
    {

        $model = $this->findModel($id);
        /** @var  $model News */
        $listComment = null;

        $query = Comment::find()
            ->andWhere(['id_new' => $id])
//            ->andWhere(['type'=>Comment::TYPE_NEW])
            ->andWhere(['status' => Comment::STATUS_ACTIVE])
            ->orderBy(['updated_at' => SORT_DESC]);
        $countQuery = clone  $query;
        $pages = new Pagination(['totalCount' => $countQuery->count()]);
        $pageSize = Yii::$app->params['page_size'];
        $pages->setPageSize($pageSize);
        $comment = $query->offset($pages->offset)->limit(10)->all();

        $j = 0;
        foreach ($comment as $item) {
            $listComment[$j] = new \stdClass();
            $listComment[$j]->content = $item->content;
            $listComment[$j]->user = Subscriber::findOne(['id' => $item->user_id]);
            $listComment[$j]->updated_at = $item->updated_at;
            $j++;
        }


        $otherModels = News::find()
            ->andWhere('news.id <> :id', [':id' => $model->id])
            ->orderBy(['news.created_at' => SORT_DESC])->limit(10)->all();


        return $this->render('detail', ['model' => $model,
            'otherModels' => $otherModels, 'listComment' => $listComment,
            'pages' => $pages]);
    }

    public function actionView($id)
    {
        /** @var News $model */
        $model = $this->findModel($id);
        $model->view_count++;
        $model->save(false);
        $otherModels = News::find()->andWhere(['campaign_id' => $model->campaign_id])->andFilterWhere(['!=', 'id', $model->id])->limit(5)->all();
        return $this->render('view', [
            'model' => $model,
            'otherModels' => $otherModels
        ]);
    }


    /**
     * Finds the DonationRequest model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DonationRequest the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = News::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('Nội dung không tồn tại.');
        }
    }
}
