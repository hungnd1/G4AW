<?php

namespace backend\controllers;

use common\auth\filters\Yii2Auth;
use common\models\Campaign;
use common\models\CampaignSearch;
use common\models\User;
use common\models\Village;
use common\models\VillageSearch;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;


/**
 * VillageController implements the CRUD actions for Village model.
 */
class VillageController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'auth' => [
                'class' => Yii2Auth::className(),
                'autoAllow' => false,
            ],
        ];
    }

    /**
     * Lists all Village models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new VillageSearch();
        $params = Yii::$app->request->queryParams;

        /** @var User $user */
        $user = Yii::$app->user->identity;
        if ($user->village_id && $user->type == User::TYPE_VILLAGE) {
            $params['VillageSearch']['id'] = $user->village_id;
        }
        if ($user->lead_donor_id && $user->type == User::TYPE_LEAD_DONOR) {
            $params['VillageSearch']['lead_donor_id'] = $user->lead_donor_id;
        }

        $dataProvider = $searchModel->search($params);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Village model.
     * @param integer $id
     * @return mixed
     */

    public function actionView($id)
    {
        $searchModel = new CampaignSearch();
        $params = Yii::$app->request->queryParams;
        $params['CampaignSearch']['village_id'] = $id;
        $dataProvider = $searchModel->search($params);
        return $this->render('view', [
            'model' => $this->findModel($id),
            'model1'=> User::findAll(['village_id'=>$id]),
            'modelcam' => Campaign::findAll(['village_id'=>$id]),
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView_my_village($active=1)
    {
        if(isset($_GET['id'])) {
            $id = $_GET['id'];
            $searchModel = new CampaignSearch();
            $params = Yii::$app->request->queryParams;
            $params['CampaignSearch']['village_id'] = $id;
            $dataProvider = $searchModel->search($params);
            return $this->render('view_my_village', [
                'model' => $this->findModel($id),
                'modelcam' => Campaign::findAll(['village_id'=>$id]),
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        }else
        {
            Yii::$app->getSession()->setFlash('error', 'Hiện tại bạn chưa có xã');
            return $this->redirect(Yii::$app->request->referrer);
        }
    }

    /**
     * Creates a new Village model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($type = User::TYPE_MANAGER)
    {
        $model = new Village();
        $model->setScenario('create');

        if ($model->load(Yii::$app->request->post())) {
//            $map_image = UploadedFile::getInstance($model, 'map_images');
//            if ($map_image) {
//                $file_name = Yii::$app->user->id . '.' . uniqid() . time() . '.' . $map_image->extension;
//                if ($map_image->saveAs(Yii::getAlias('@webroot') . "/" . Yii::getAlias('@village_image') . "/" . $file_name)) {
//                    $model->map_images = $file_name;
//                }
//            }
            $image = UploadedFile::getInstance($model, 'image');
            if ($image) {
                $file_name = Yii::$app->user->id . '.' . uniqid() . time() . '.' . $image->extension;
                if ($image->saveAs(Yii::getAlias('@webroot') . "/" . Yii::getAlias('@village_image') . "/" . $file_name)) {
                    $model->image = $file_name;
                }
            }
            if($model->save()) {
                Yii::$app->getSession()->setFlash('success', 'Thêm xã thành công');
                return $this->redirect(['view', 'id' => $model->id]);
            }else {
                Yii::$app->getSession()->setFlash('success', 'Thêm xã không thành công');
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }
    /**
     * Updates an existing Village model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
//        $model->establish_date = date('dd/m/yyyy',strtotime($model->establish_date));
        $model->establish_date = date('d/m/Y',strtotime($model->establish_date));
        $image_old = $model->image;
        $image_maps_old = $model->map_images;
        $file_upload_old = $model->file_upload;
        if ($model->load(Yii::$app->request->post())) {
            $model->establish_date = date('Y-m-d',strtotime($model->establish_date));
//            echo "<pre>";
//            print_r($model->establish_date);
//            die();
            $image  = UploadedFile::getInstance($model, 'image');
            $map_image = UploadedFile::getInstance($model, 'map_images');
            $file_upload = UploadedFile::getInstance($model, 'file_upload');
            if ($map_image) {
                $file_name_maps = Yii::$app->user->id . '.' . uniqid() . time() . '.' . $map_image->extension;
                if ($map_image->saveAs(Yii::getAlias('@webroot') . "/" . Yii::getAlias('@village_image') . "/" . $file_name_maps)) {
                    $model->map_images = $file_name_maps;
                    if ($image) {
                        $file_name = Yii::$app->user->id . '.' . uniqid() . time() . '.' . $image->extension;
                        if ($image->saveAs(Yii::getAlias('@webroot') . "/" . Yii::getAlias('@village_image') . "/" . $file_name)) {
                            $model->image = $file_name;
                            if($file_upload){
                                $file_upload_name = Yii::$app->user->id . '.' . uniqid() . time() . '.' . $file_upload->extension;
                                if ($file_upload->saveAs(Yii::getAlias('@webroot') . "/" . Yii::getAlias('@file_upload') . "/" . $file_upload_name)) {
                                    $model->file_upload = $file_upload_name;
                                    if ($model->save()) {
                                        Yii::$app->getSession()->setFlash('success', 'Cập nhật xã thành công');
                                        return $this->redirect(['index']);
                                    } else {
                                        Yii::error($model->getErrors());
                                        Yii::$app->getSession()->setFlash('error', 'Lỗi hệ thống vui lòng thử lại');
                                    }
                                }else {
                                    Yii::error($model->getErrors());
                                    Yii::$app->getSession()->setFlash('error', 'Lỗi hệ thống vui lòng thử lại');
                                }
                            }
                            else{
                                if ($model->save()) {
                                    Yii::$app->getSession()->setFlash('success', 'Cập nhật xã thành công');
                                    return $this->redirect(['index']);
                                } else {
                                    Yii::error($model->getErrors());
                                    Yii::$app->getSession()->setFlash('error', 'Lỗi hệ thống vui lòng thử lại');
                                }
                            }
                        } else {
                            Yii::$app->getSession()->setFlash('error', 'Lỗi hệ thống, vui lòng thử lại');
                        }
                    }else {
                        $model->image = $image_old;
                        if($file_upload){
                            $file_upload_name = Yii::$app->user->id . '.' . uniqid() . time() . '.' . $file_upload->extension;
                            if ($file_upload->saveAs(Yii::getAlias('@webroot') . "/" . Yii::getAlias('@file_upload') . "/" . $file_upload_name)) {
                                $model->file_upload = $file_upload_name;
                                if ($model->save()) {
                                    Yii::$app->getSession()->setFlash('success', 'Cập nhật xã thành công');
                                    return $this->redirect(['index']);
                                } else {
                                    Yii::error($model->getErrors());
                                    Yii::$app->getSession()->setFlash('error', 'Lỗi hệ thống vui lòng thử lại');
                                }
                            }else {
                                Yii::error($model->getErrors());
                                Yii::$app->getSession()->setFlash('error', 'Lỗi hệ thống vui lòng thử lại');
                            }
                        }
                        else
                        {
                            if ($model->save()) {
                                Yii::$app->getSession()->setFlash('success', 'Cập nhật xã thành công');
                                return $this->redirect(['index']);
                            } else {
                                Yii::error($model->getErrors());
                                Yii::$app->getSession()->setFlash('error', 'Lỗi hệ thống vui lòng thử lại');
                            }
                        }
                    }
                    return $this->redirect(['index']);
                } else {
                    Yii::$app->getSession()->setFlash('error', 'Lỗi hệ thống, vui lòng thử lại');
                }
            }else {
                $model->map_images = $image_maps_old;
                if ($image) {
                    $file_name = Yii::$app->user->id . '.' . uniqid() . time() . '.' . $image->extension;
                    if ($image->saveAs(Yii::getAlias('@webroot') . "/" . Yii::getAlias('@village_image') . "/" . $file_name)) {
                        $model->image = $file_name;
                        if($file_upload){
                            $file_upload_name = Yii::$app->user->id . '.' . uniqid() . time() . '.' . $file_upload->extension;
                            if ($file_upload->saveAs(Yii::getAlias('@webroot') . "/" . Yii::getAlias('@file_upload') . "/" . $file_upload_name)) {
                                $model->file_upload = $file_upload_name;
                                if ($model->save()) {
                                    Yii::$app->getSession()->setFlash('success', 'Cập nhật xã thành công');
                                    return $this->redirect(['index']);
                                } else {
                                    Yii::error($model->getErrors());
                                    Yii::$app->getSession()->setFlash('error', 'Lỗi hệ thống vui lòng thử lại');
                                }
                            }else {
                                Yii::error($model->getErrors());
                                Yii::$app->getSession()->setFlash('error', 'Lỗi hệ thống vui lòng thử lại');
                            }
                        }
                        else
                        {
                            if ($model->save()) {
                                Yii::$app->getSession()->setFlash('success', 'Cập nhật xã thành công');
                                return $this->redirect(['index']);
                            } else {
                                Yii::error($model->getErrors());
                                Yii::$app->getSession()->setFlash('error', 'Lỗi hệ thống vui lòng thử lại');
                            }
                        }
                    } else {
                        Yii::$app->getSession()->setFlash('error', 'Lỗi hệ thống, vui lòng thử lại');
                    }
                }else {
                    $model->image = $image_old;
                    if($file_upload){
                        $file_upload_name = Yii::$app->user->id . '.' . uniqid() . time() . '.' . $file_upload->extension;
//                        echo "<pre>";
//                        print_r($file_upload_name);
//                        die();
                        if ($file_upload->saveAs(Yii::getAlias('@webroot') . "/" . Yii::getAlias('@file_upload') . "/" . $file_upload_name)) {
                            $model->file_upload = $file_upload_name;
                            if ($model->save()) {
                                Yii::$app->getSession()->setFlash('success', 'Cập nhật xã thành công');
                                return $this->redirect(['index']);
                            } else {
                                Yii::error($model->getErrors());
                                Yii::$app->getSession()->setFlash('error', 'Lỗi hệ thống vui lòng thử lại');
                            }
                        }else {
                            Yii::error($model->getErrors());
                            Yii::$app->getSession()->setFlash('error', 'Lỗi hệ thống vui lòng thử lạiv');
                        }
                    }
                    else
                    {
                        if ($model->save()) {
                            Yii::$app->getSession()->setFlash('success', 'Cập nhật xã thành công');
                            return $this->redirect(['index']);
                        } else {
                            Yii::error($model->getErrors());
                            Yii::$app->getSession()->setFlash('error', 'Lỗi hệ thống vui lòng thử lại');
                        }
                    }
                }
                return $this->redirect(['index']);
            }
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    public function actionUpdate_my_village($id)
    {
        $model = $this->findModel($id);
        $image_old = $model->image;
        if ($model->load(Yii::$app->request->post())) {
            $image  = UploadedFile::getInstance($model, 'image');
            if ($image) {
                $file_name = Yii::$app->user->id . '.' . uniqid() . time() . '.' . $image->extension;
                if ($image->saveAs(Yii::getAlias('@webroot') . "/" . Yii::getAlias('@village_image') . "/" . $file_name)) {
                    $model->image = $file_name;
                    if ($model->save()) {
                        Yii::$app->getSession()->setFlash('success', 'Cập nhật xã thành công');
                        return $this->redirect(['view_my_village','id'=>$model->id,'active'=>1]);
                    } else {
                        Yii::error($model->getErrors());
                        Yii::$app->getSession()->setFlash('error', 'Lỗi hệ thống vui lòng thử lại');
                    }
                } else {
                    Yii::$app->getSession()->setFlash('error', 'Lỗi hệ thống, vui lòng thử lại');
                }
            }else {
                $model->image = $image_old;
                $model->save();
                Yii::$app->getSession()->setFlash('success', 'Cập nhật xã thành công');
                return $this->redirect(['view_my_village','id'=>$model->id,'active'=>1]);
            }
            return $this->redirect(['view_my_village','id'=>$model->id,'active'=>1]);
        } else {
            return $this->render('update_my_village', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Village model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {

        Yii::$app->db->createCommand("UPDATE village SET status = 4  WHERE id= $id")
            ->execute();
        Yii::$app->getSession()->setFlash('success', 'Đã xóa xã thành công ');
//        $this->findModel($id)->delete();
        return $this->redirect(['index']);
    }

    /**
     * Finds the Village model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Village the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Village::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
