<?php

namespace frontend\controllers;

use common\models\Comment;
use common\models\News;
use common\models\PriceCoffee;
use common\models\Province;
use common\models\Subscriber;
use Yii;
use yii\data\Pagination;
use yii\web\NotFoundHttpException;

/**
 * DonationRequestController implements the CRUD actions for DonationRequest model.
 */
class PriceController extends BaseController
{


    /**
     * Displays a single DonationRequest model.
     * @param integer $id
     * @return mixed
     */
    public function actionIndex($date = null)
    {
        $title = "Giá";
        $model = new PriceCoffee();
        if ($model->load(Yii::$app->request->post())) {
            $date = $model->date;
        }
        if (!$date) {
            $date = date('d/m/Y', time());
        }
        $model->date = $date;
        $listPrice = $this->callCurl(Yii::$app->params['apiUrl'].'app/get-price?date='.$date);
        $listPrice = $listPrice['data'];
        $listNewRelated = News::find()
            ->andWhere(['news.status' => News::STATUS_ACTIVE])
            ->orderBy(['news.created_at' => SORT_DESC])->limit(10)->all();
        return $this->render('index', ['title' => $title, 'listPrice' => $listPrice, 'listNewRelated' => $listNewRelated,'model'=>$model,'date'=>$date]);
    }


    private function callCurl($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/xml;charset=UTF-8', 'X-Api-Key: xjunvhntdjcews3bftmvep6wu3hs62qc', 'X-Language:vi'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $ch_result = curl_exec($ch);
        curl_close($ch);
        $arr_detail = json_decode($ch_result, true);
        return $arr_detail;
    }

    public function actionDetail($date)
    {
        $title = "Giá";
        $listPrice = $this->callCurl(Yii::$app->params['apiUrl'].'app/get-price?date='.$date);
        $listPrice = $listPrice['data'];
        $model = new PriceCoffee();
        $listNewRelated = News::find()
            ->andWhere(['news.status' => News::STATUS_ACTIVE])
            ->orderBy(['news.created_at' => SORT_DESC])->limit(10)->all();
        return $this->render('index', ['title' => $title, 'listPrice' => $listPrice, 'listNewRelated' => $listNewRelated,'model'=>$model]);
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
