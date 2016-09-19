<?php

namespace frontend\controllers;

use common\components\OwnerFilter;
use common\models\Province;
use common\models\User;
use common\models\Village;
use Yii;
use common\models\DonationRequest;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * DonationRequestController implements the CRUD actions for DonationRequest model.
 */
class DonationRequestController extends BaseController
{

    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            [
                'class' => OwnerFilter::className(),
                'user' => $this->user,
                'field_owner_id' => 'created_by',
                'model_relation_owner' => function ($action, $params) {
                    return DonationRequest::findOne($params['id']);
                },
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
                'only' => ['create', 'update', 'delete','view'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['create', 'update', 'delete','view'],
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

        $user = User::findOne(['id'=>Yii::$app->user->id]);
        return $this->render('index', [
            'model' => $this->findModel($id_donate),
            'user'=>$user
        ]);
    }

    /**
     * Creates a new DonationRequest model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionIndex()
    {
        $model = new DonationRequest();
        $user = User::findOne(['id'=>Yii::$app->user->id]);
        if ($model->load(Yii::$app->request->post())  ) {
//            $arr_village = explode('(',$model->village_name);
//            $arr_village =  array_map('trim',$arr_village);
//            $village_name = $arr_village[0];
//            $province_name  = (string)str_replace(')','',str_replace('Tỉnh','',$arr_village[1]));
//            $village = Village::find()
//                ->innerJoin('province','province.id = village.district_id')
//                ->andWhere(['like','village.name',trim($village_name)])
//                ->andWhere(['like','province.name',trim($province_name)])->one();
//            $model->village_id = $village->id;
            $model->status = DonationRequest::STATUS_NEW;
            $image = UploadedFile::getInstance($model, 'image');
            if ($image) {
                $file_name = Yii::$app->user->id . '.' . uniqid() . time() . '.' . $image->extension;
                if ($image->saveAs(Yii::getAlias('@webroot') . "/" . Yii::getAlias('@donation_uploads') . "/" . $file_name)) {
                    $model->image = $file_name;
                }
            }
            if ($model->save()) {
                Yii::$app->session->addFlash('success', 'Tạo yêu cầu thành công');
                return $this->redirect(['/donation-request/view', 'id_donate' => $model->id]);
            } else {
                Yii::$app->session->addFlash('error', 'Tạo yêu cầu không thành công');
            }

        }

        return $this->render('index', [
            'model' => $model,
            'user'=>$user
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
        $user = User::findOne(['id'=>Yii::$app->user->id]);
        $image_old = $model->image;
        $village = Village::findOne(['id'=>$model->village_id]);
        $province = Province::findOne(['id'=>$village->district_id]);
//        $model->village_name = $village->name.'(Tỉnh '.$province->name.')';

        if ($model->load(Yii::$app->request->post())) {
            $image  = UploadedFile::getInstance($model, 'image_update');
//            $arr_village = explode('(',$model->village_name);
//            $arr_village =  array_map('trim',$arr_village);
//            $village_name = $arr_village[0];
//            $province_name  = (string)str_replace(')','',str_replace('Tỉnh','',$arr_village[1]));
//            $village = Village::find()
//                ->innerJoin('province','province.id = village.district_id')
//                ->andWhere(['like','village.name',trim($village_name)])
//                ->andWhere(['like','province.name',trim($province_name)])->one();
//            $model->village_id = $village->id;

            if ($image){
                $file_name = Yii::$app->user->id . '.' . uniqid() . time() . '.' . $image->extension;
                if ($image->saveAs(Yii::getAlias('@webroot') . "/" . Yii::getAlias('@donation_uploads') . "/" . $file_name)) {
                    $model->image = $file_name;
                }
            }else{
                $model->image = $image_old;
            }
            if($model->save()){
                Yii::$app->session->addFlash('success', 'Cập nhật thành công');
                return $this->redirect(['/donation-request/view', 'id_donate' => $model->id]);
            }
        }
//        $model->imageAsms = $model->loadImageAsm();
        return $this->render('index', [
            'model' => $model,
            'user'=>$user
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
            return  Json::encode(['success'=>true]);
        } else {
//            Yii::$app->session->addFlash('error', 'Xóa yêu cầu thất bại');
            return  Json::encode(['success'=>false]);
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


    public function VillageList() {

        $listVillage = Village::find()
            ->andWhere(['status'=>Village::STATUS_ACTIVE])->all();
        $out = [];
        $i=0;
        foreach ($listVillage as $d) {
            $out[$i] = $d->name;
            $district = Province::findOne(['id'=>$d->district_id]);
            $out[$i] .='(Tỉnh '.$district->display_name.')';
            $i++;
        }
        return $out;
    }
}
