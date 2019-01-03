<?php

namespace frontend\controllers;

use common\models\Comment;
use common\models\GapGeneral;
use common\models\News;
use common\models\Subscriber;
use Yii;
use yii\data\Pagination;
use yii\web\NotFoundHttpException;

/**
 * DonationRequestController implements the CRUD actions for DonationRequest model.
 */
class DiseaseController extends BaseController
{


    /**
     * Displays a single DonationRequest model.
     * @param integer $id
     * @return mixed
     */
    public function actionIndex()
    {
        $title = "Sâu bệnh, tin tức và sự kiện";
        $listNews = GapGeneral::find()
            ->andWhere(['status' => GapGeneral::STATUS_ACTIVE])
            ->andWhere(['type' => GapGeneral::GAP_GENERAL])
            ->andWhere(['category_id' => GapGeneral::CATEGORY_SAUBENH]);
        $listNews->orderBy(['order' => SORT_DESC, 'created_at' => SORT_DESC]);
        $countQuery = clone $listNews;
        $pages = new Pagination(['totalCount' => $countQuery->count()]);
        $pageSize = Yii::$app->params['page_size'];
        $pages->setPageSize($pageSize);
        $models = $listNews->offset($pages->offset)
            ->limit(10)->all();

        //tin tuc
        $listNew = GapGeneral::find()
            ->andWhere(['status' => GapGeneral::STATUS_ACTIVE])
            ->andWhere(['type' => GapGeneral::GAP_GENERAL])
            ->andWhere(['category_id' => GapGeneral::CATEGORY_TINTUC]);
        $listNew->orderBy(['order' => SORT_DESC, 'created_at' => SORT_DESC]);
        $countQuerys = clone $listNew;
        $pages1 = new Pagination(['totalCount' => $countQuerys->count()]);
        $pageSize1 = Yii::$app->params['page_size'];
        $pages1->setPageSize($pageSize1);
        $modelsNew = $listNew->offset($pages1->offset)
            ->limit(10)->all();

        //sự kiện
        $listNew1 = GapGeneral::find()
            ->andWhere(['status' => GapGeneral::STATUS_ACTIVE])
            ->andWhere(['type' => GapGeneral::GAP_GENERAL])
            ->andWhere(['category_id' => GapGeneral::CATEGORY_SUKIEN]);
        $listNew1->orderBy(['order' => SORT_DESC, 'created_at' => SORT_DESC]);
        $countQuery1 = clone $listNew1;
        $pages2 = new Pagination(['totalCount' => $countQuery1->count()]);
        $pageSize2 = Yii::$app->params['page_size'];
        $pages2->setPageSize($pageSize2);
        $modelsNew1 = $listNew1->offset($pages2->offset)
            ->limit(10)->all();

        $listNewRelated = GapGeneral::find()
            ->andWhere(['status' => GapGeneral::STATUS_ACTIVE])
            ->andWhere(['type' => GapGeneral::GAP_GENERAL])
            ->orderBy(['order' => SORT_DESC, 'created_at' => SORT_DESC])
            ->offset($pages->offset + 10)->limit(5)->all();
        return $this->render('index',
            [
                'title' => $title,
                'listdisease' => $models,
                'modelsNew' => $modelsNew,
                'pages' => $pages,
                'listdiseaseRelated' => $listNewRelated,
                'pages1' => $pages1,
                'modelsNew1' => $modelsNew1,
                'pages2' => $pages2,
            ]);
    }


    public function actionDetail($id)
    {

        $model = $this->findModel($id);
        /** @var  $model GapGeneral */
        $listComment = null;

        $query = Comment::find()
            ->andWhere(['id_disease' => $id])
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


        $otherModels = GapGeneral::find()
            ->andWhere('id <> :id', [':id' => $model->id])
            ->andWhere(['type' => GapGeneral::GAP_GENERAL])
            ->orderBy(['created_at' => SORT_DESC])->limit(6)->all();


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
        if (($model = GapGeneral::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('Nội dung không tồn tại.');
        }
    }
}
