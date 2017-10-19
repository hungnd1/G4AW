<?php

namespace frontend\controllers;

use common\components\OwnerFilter;
use common\models\DonationRequest;
use common\models\Exchange;
use common\models\ExchangeBuy;
use common\models\PriceCoffee;
use common\models\Province;
use common\models\Sold;
use common\models\Subscriber;
use common\models\TotalQuality;
use common\models\User;
use common\models\Village;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

/**
 * DonationRequestController implements the CRUD actions for DonationRequest model.
 */
class ExchangeController extends BaseController
{

    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            [
                'class' => OwnerFilter::className(),
                'user' => $this->user,
                'field_owner_id' => 'created_by',
                'only' => ['update', 'delete']
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['get'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['create', 'update', 'delete', 'view', 'exchange-sold'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['create', 'update', 'delete', 'view', 'exchange-sold'],
                        'roles' => ['@'],
                    ],
                ],
            ],
        ]);
    }


    /**
     * Displays a single DonationRequest model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id_donate)
    {
//        $currentUser = User::findOne(Yii::$app->user->id);

        $user = User::findOne(['id' => Yii::$app->user->id]);
        return $this->render('index', [
            'model' => $this->findModel($id_donate),
            'user' => $user
        ]);
    }

    /**
     * Creates a new DonationRequest model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionIndex()
    {
        $model = new Exchange();
        $user = Subscriber::findOne(['id' => Yii::$app->user->id]);
        $model_exchange = new ExchangeBuy();
        return $this->render('index', [
            'model' => $model,
            'user' => $user,
            'model_exchange'=>$model_exchange
        ]);

    }

    public function actionExchangeSold()
    {
        $model = new Exchange();
        $model_exchange = new ExchangeBuy();
        $user = Subscriber::findOne(['id' => Yii::$app->user->id]);
        if ($model->load(Yii::$app->request->post())) {
            $totalQuanlity = TotalQuality::find()
                ->andWhere('min_total_quality <= :min', [':min' => (int)$model->total_quality_id])
                ->andWhere('max_total_quality > :max', [':max' => (int)$model->total_quality_id])
                ->one();
            if (!$totalQuanlity) {
                $totalQuanlity = TotalQuality::find()->orderBy(['max_total_quality' => SORT_DESC])->one();
            }
            $sold = Sold::find()
                ->andWhere('min_sold <= :min', [':min' => $model->sold_id])
                ->andWhere('max_sold > :max', [':max' => $model->sold_id])
                ->one();
            if (!$sold) {
                $sold = Sold::find()->orderBy(['max_sold' => SORT_DESC])->one();
            }
            $model->subscriber_id = Yii::$app->user->id;
            $model->total_quality_id = $totalQuanlity->id;
            $model->sold_id = $sold->id;
            $model->created_at = time();
            $model->updated_at = time();

            $today = strtotime('today midnight') ;
            $tomorrow = strtotime('tomorrow') ;

            $maxPrice = PriceCoffee::find()
                ->andWhere(['>=', 'price_coffee.created_at', $today ])
                ->andWhere(['<=', 'price_coffee.created_at', $tomorrow])
                ->andWhere(['not in','price_coffee.coffee_old_id',['201029','199811','199808','199807']])
                ->groupBy('price_coffee.coffee_old_id')
                ->orderBy(['price_coffee.coffee_old_id' => SORT_DESC])->max('price_average');
            $minPrice = PriceCoffee::find()
                ->andWhere(['>=', 'price_coffee.created_at', $today ])
                ->andWhere(['<=', 'price_coffee.created_at', $tomorrow])
                ->andWhere(['not in','price_coffee.coffee_old_id',['201029','199811','199808','199807']])
                ->groupBy('price_coffee.coffee_old_id')
                ->orderBy(['price_coffee.coffee_old_id' => SORT_DESC])->min('price_average');
            if($model->price >= $minPrice && $model->price <= $maxPrice ){
                if ($model->save()) {
                    Yii::$app->session->setFlash('success', 'Giao dịch thành công');
                    return $this->redirect(['/user/my-page']);
                } else {
                    Yii::$app->session->setFlash('error', 'Giao dịch không thành công');
                }
            }
            Yii::$app->session->setFlash('error', 'Giá nhập vào không được quá giá cao nhất và thấp nhất của ngày hôm nay');
            return $this->render('index', [
                'model' => $model,
                'user' => $user,
                'model_exchange'=>$model_exchange
            ]);

        }
        Yii::$app->session->setFlash('error', 'Giao dịch không thành công');
        return $this->render('index', [
            'model' => $model,
            'user' => $user,
            'model_exchange'=>$model_exchange
        ]);
    }

    public function actionExchangeBuy()
    {
        $model = new ExchangeBuy();
        $modelSold = new Exchange();
        $user = Subscriber::findOne(['id' => Yii::$app->user->id]);
        if ($model->load(Yii::$app->request->post())) {
            $totalQuantity = TotalQuality::find()
                ->andWhere('min_total_quality <= :min', [':min' => (int)$model->total_quantity])
                ->andWhere('max_total_quality > :max', [':max' => (int)$model->total_quantity])
                ->one();
            if (!$totalQuantity) {
                $totalQuantity = TotalQuality::find()->orderBy(['max_total_quality' => SORT_DESC])->one();
            }
            $model->subscriber_id = Yii::$app->user->id;
            $model->total_quantity = $totalQuantity->id;
            $model->created_at = time();
            $model->updated_at = time();

            $today = strtotime('today midnight') ;
            $tomorrow = strtotime('tomorrow') ;

            $maxPrice = PriceCoffee::find()
                ->andWhere(['>=', 'price_coffee.created_at', $today ])
                ->andWhere(['<=', 'price_coffee.created_at', $tomorrow])
                ->andWhere(['not in','price_coffee.coffee_old_id',['201029','199811','199808','199807']])
                ->groupBy('price_coffee.coffee_old_id')
                ->orderBy(['price_coffee.coffee_old_id' => SORT_DESC])->max('price_average');


            $minPrice = PriceCoffee::find()
                ->andWhere(['>=', 'price_coffee.created_at', $today ])
                ->andWhere(['<=', 'price_coffee.created_at', $tomorrow])
                ->andWhere(['not in','price_coffee.coffee_old_id',['201029','199811','199808','199807']])
                ->groupBy('price_coffee.coffee_old_id')
                ->orderBy(['price_coffee.coffee_old_id' => SORT_DESC])->min('price_average');
            if($model->price_buy >= $minPrice && $model->price_buy <= $maxPrice ){
                if ($model->save()) {
                    Yii::$app->session->setFlash('success', 'Giao dịch thành công');
                    return $this->redirect(['/user/my-page']);
                } else {
                    Yii::$app->session->setFlash('error', 'Giao dịch không thành công');
                }
            }
            Yii::$app->session->setFlash('error', 'Giá nhập vào không được quá giá cao nhất và thấp nhất của ngày hôm nay');
            return $this->render('index', [
                'model_exchange' => $model,
                'user' => $user,
                'model'=>$modelSold
            ]);

        }
        Yii::$app->session->setFlash('error', 'Giao dịch không thành công');
        return $this->render('index', [
            'model_exchange' => $model,
            'user' => $user,
            'model'=>$modelSold
        ]);
    }

    /**
     * Updates an existing DonationRequest model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $user = User::findOne(['id' => Yii::$app->user->id]);
        $image_old = $model->image;
        $village = Village::findOne(['id' => $model->village_id]);
        $province = Province::findOne(['id' => $village->district_id]);
//        $model->village_name = $village->name.'(Tỉnh '.$province->name.')';

        if ($model->load(Yii::$app->request->post())) {
            $image = UploadedFile::getInstance($model, 'image_update');
//            $arr_village = explode('(',$model->village_name);
//            $arr_village =  array_map('trim',$arr_village);
//            $village_name = $arr_village[0];
//            $province_name  = (string)str_replace(')','',str_replace('Tỉnh','',$arr_village[1]));
//            $village = Village::find()
//                ->innerJoin('province','province.id = village.district_id')
//                ->andWhere(['like','village.name',trim($village_name)])
//                ->andWhere(['like','province.name',trim($province_name)])->one();
//            $model->village_id = $village->id;

            if ($image) {
                $file_name = Yii::$app->user->id . '.' . uniqid() . time() . '.' . $image->extension;
                if ($image->saveAs(Yii::getAlias('@webroot') . "/" . Yii::getAlias('@donation_uploads') . "/" . $file_name)) {
                    $model->image = $file_name;
                }
            } else {
                $model->image = $image_old;
            }
            if ($model->save()) {
                Yii::$app->session->addFlash('success', 'Cập nhật thành công');
                return $this->redirect(['/donation-request/view', 'id_donate' => $model->id]);
            }
        }
//        $model->imageAsms = $model->loadImageAsm();
        return $this->render('index', [
            'model' => $model,
            'user' => $user
        ]);

    }

    /**
     * Deletes an existing DonationRequest model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
//        $id = Yii::$app->request->post('id');
        $model = $this->findModel($id);
        $model->status = DonationRequest::STATUS_DELETED;
        if ($model->save()) {
//            Yii::$app->session->addFlash('success', 'Xóa yêu cầu thành công');
            return Json::encode(['success' => true]);
        } else {
//            Yii::$app->session->addFlash('error', 'Xóa yêu cầu thất bại');
            return Json::encode(['success' => false]);
        }
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
        if (($model = DonationRequest::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('Nội dung không tồn tại.');
        }
    }


    public function VillageList()
    {

        $listVillage = Village::find()
            ->andWhere(['status' => Village::STATUS_ACTIVE])->all();
        $out = [];
        $i = 0;
        foreach ($listVillage as $d) {
            $out[$i] = $d->name;
            $district = Province::findOne(['id' => $d->district_id]);
            $out[$i] .= '(Tỉnh ' . $district->display_name . ')';
            $i++;
        }
        return $out;
    }
}
