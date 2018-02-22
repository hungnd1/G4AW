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
        $listPrice = $this->callCurl(Yii::$app->params['apiUrl'].'app/get-price-web?date='.$date);
        $listPrice1 = $this->callCurl(Yii::$app->params['apiUrl'].'app/get-price-web?date='.$date.'&coffee=1');
        $listPrice2 = $this->callCurl(Yii::$app->params['apiUrl'].'app/get-price-web?date='.$date.'&coffee=2');
        $listPrice3 = $this->callCurl(Yii::$app->params['apiUrl'].'app/get-price-web?date='.$date.'&coffee=3');
        $listPrice4 = $this->callCurl(Yii::$app->params['apiUrl'].'app/get-price-web?date='.$date.'&coffee=4');
        $listPrice = isset($listPrice['data']) ? $listPrice['data'] : null ;
        $listPrice1 = isset($listPrice1['data']) ? $listPrice1['data'] : null ;
        $listPrice2 = isset($listPrice2['data']) ? $listPrice2['data'] : null ;
        $listPrice3 = isset($listPrice3['data']) ? $listPrice3['data'] : null ;
        $listPrice4 = isset($listPrice4['data']) ? $listPrice4['data'] : null ;
        $listNewRelated = News::find()
            ->andWhere(['news.status' => News::STATUS_ACTIVE])
            ->orderBy(['news.created_at' => SORT_DESC])->limit(10)->all();
        return $this->render('index', ['title' => $title,
            'listPrice' => $listPrice,
            'listPrice1' => $listPrice1,
            'listPrice2' => $listPrice2,
            'listPrice3' => $listPrice3,
            'listPrice4' => $listPrice4,
            'listNewRelated' => $listNewRelated,
            'model'=>$model,
            'date'=>$date
        ]);
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
