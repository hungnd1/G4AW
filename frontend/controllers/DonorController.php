<?php

namespace frontend\controllers;

use common\models\DonationRequest;
use common\models\LeadDonor;
use common\models\News;
use common\models\Village;
use yii\data\Pagination;
use yii\web\NotFoundHttpException;

/**
 * DonationRequestController implements the CRUD actions for DonationRequest model.
 */
class DonorController extends BaseController
{




    /**
     * Displays a single DonationRequest model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = LeadDonor::find()->andWhere(['id'=>$id])->one();
            $village = Village::find()->andwhere(['lead_donor_id' => $id])->andWhere(['status' => Village::STATUS_ACTIVE])
                ->orderBy(['lower(name)' => SORT_ASC])->all();
            $newsQuery = News::find()->andWhere(['status' => News::STATUS_ACTIVE])
                ->andWhere(['lead_donor_id' => $id])
                ->andWhere(['type' => News::TYPE_DONOR])
                ->orderBy(['published_at' => SORT_DESC, 'updated_at' => SORT_DESC, 'created_at' => SORT_DESC]);
            $countQuery = clone $newsQuery;
            $pages = new Pagination(['totalCount' => $countQuery->count()]);
            $news = $newsQuery->offset($pages->offset)
                ->limit(12)->all();
            return $this->render('view', [
                'model' => $model,
                'village' => $village,
                'pages' => $pages,
                'listNews' => $news
            ]);
    }

    protected function findModel($id)
    {
        if (($model = LeadDonor::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('Nội dung không tồn tại.');
        }
    }
}
