<?php

namespace backend\controllers;

use common\auth\filters\Yii2Auth;
use common\models\Campaign;
use common\models\CampaignSearch;
use common\models\User;
use common\models\Village;
use Yii;
use common\models\LeadDonor;
use common\models\LeadDonorSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use backend\models\VillageForm;
use common\models\VillageSearch;

/**
 * LeadDonorController implements the CRUD actions for LeadDonor model.
 */
class LeadDonorController extends Controller
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
     * Lists all LeadDonor models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new LeadDonorSearch();
        $params = Yii::$app->request->queryParams;

        /** @var User $user */
        $user = Yii::$app->user->identity;
        if ($user->lead_donor_id && $user->type == User::TYPE_LEAD_DONOR) {
            $params['LeadDonorSearch']['id'] = $user->lead_donor_id;
        }

        $dataProvider = $searchModel->search($params);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single LeadDonor model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id, $active = 1)
    {
        $searchModel1 = new CampaignSearch();
        $params1 = Yii::$app->request->queryParams;
        $params1['CampaignSearch']['lead_donor_id'] = $id;
        $dataProvider1 = $searchModel1->search($params1);
        $searchModel = new VillageSearch();
        $params = Yii::$app->request->queryParams;
        $params['VillageSearch']['lead_donor_id'] = $id;
        $dataProvider = $searchModel->search($params);
        $model1 = new Village();
        return $this->render('view', [
            'model' => $this->findModel($id),
            'model1' => $model1,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'searchModel1' => $searchModel1,
            'dataProvider1' => $dataProvider1,
            'modelcam' => Campaign::findAll(['lead_donor_id' => $id]),
            'active' => $active,
            'model2' => User::findAll(['lead_donor_id' => $id]),
        ]);
    }

    public function actionView_my_lead_donor($active = 1)
    {
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $searchModel1 = new CampaignSearch();
            $params1 = Yii::$app->request->queryParams;
            $params1['CampaignSearch']['lead_donor_id'] = $id;
            $dataProvider1 = $searchModel1->search($params1);
            $searchModel = new VillageSearch();
            $params = Yii::$app->request->queryParams;
            $params['VillageSearch']['lead_donor_id'] = $id;
            $dataProvider = $searchModel->search($params);
            $model1 = new Village();
            return $this->render('view_my_lead_donor', [
                'model' => $this->findModel($id),
                'model1' => $model1,
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'searchModel1' => $searchModel1,
                'dataProvider1' => $dataProvider1,
                'modelcam' => Campaign::findAll(['lead_donor_id' => $id]),
                'active' => $active
            ]);
        } else {
            Yii::$app->getSession()->setFlash('error', 'Hiện tại bạn chưa có doanh nghiệp');
            return $this->redirect(Yii::$app->request->referrer);
        }
    }

    /**
     * Creates a new LeadDonor model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new LeadDonor();

        if ($model->load(Yii::$app->request->post())) {

            // Xu ly anh
            $image = UploadedFile::getInstance($model, 'image');
            if ($image) {
                $file_name = Yii::$app->user->id . '.' . uniqid() . time() . '.' . $image->extension;
                if ($image->saveAs(Yii::getAlias('@webroot') . "/" . Yii::getAlias('@lead_donor_image') . "/" . $file_name)) {
                    $model->image = $file_name;
                }
            }

            //Xu ly video
            $video = UploadedFile::getInstance($model, 'video');
            if ($video) {
                $file_name = Yii::$app->user->id . '.' . uniqid() . time() . '.' . $video->extension;
                if ($video->saveAs(Yii::getAlias('@webroot') . "/" . Yii::getAlias('@lead_donor_video') . "/" . $file_name)) {
                    $model->video = $file_name;
                }
            }


            if ($model->save()) {
                Yii::$app->getSession()->setFlash('success', 'Thêm doanh nghiệp đỡ đầu thành công');
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing LeadDonor model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $image_old = $model->image;
        $video_old = $model->video;
        if ($model->load(Yii::$app->request->post())) {
            $image = UploadedFile::getInstance($model, 'image');
            if ($image) {
                $file_name = Yii::$app->user->id . '.' . uniqid() . time() . '.' . $image->extension;
                if ($image->saveAs(Yii::getAlias('@webroot') . "/" . Yii::getAlias('@lead_donor_image') . "/" . $file_name)) {
                    $model->image = $file_name;
                } else {
                    $model->image = $image_old;
                    //Yii::$app->getSession()->setFlash('error', 'Lỗi hệ thống, vui lòng thử lại');
                }
            } else {
                $model->image = $image_old;
            }

            $video = UploadedFile::getInstance($model, 'video');
            if ($video) {
                $file_name = Yii::$app->user->id . '.' . uniqid() . time() . '.' . $video->extension;
                if ($video->saveAs(Yii::getAlias('@webroot') . "/" . Yii::getAlias('@lead_donor_video') . "/" . $file_name)) {
                    $model->video = $file_name;
                } else {
                    $model->video = $video_old;
//                    Yii::$app->getSession()->setFlash('error', 'Lỗi hệ thống, vui lòng thử lại');
                }
            } else {
                $model->video = $video_old;
            }


            if ($model->save()) {
                Yii::$app->getSession()->setFlash('success', 'Cập nhật doanh nghiệp đỡ đầu thành công');
                return $this->redirect(['index']);
            } else {
                Yii::error($model->getErrors());
                Yii::$app->getSession()->setFlash('error', 'Lỗi hệ thống vui lòng thử lại');
            }

            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    public function actionUpdate_my_lead_donor($id)
    {
        $model = $this->findModel($id);
        $image_old = $model->image;
        if ($model->load(Yii::$app->request->post())) {
            $image = UploadedFile::getInstance($model, 'image');
            if ($image) {
                $file_name = Yii::$app->user->id . '.' . uniqid() . time() . '.' . $image->extension;
                if ($image->saveAs(Yii::getAlias('@webroot') . "/" . Yii::getAlias('@lead_donor_image') . "/" . $file_name)) {
                    $model->image = $file_name;
                    if ($model->save()) {
                        Yii::$app->getSession()->setFlash('success', 'Cập nhật doanh nghiệp đỡ đầu thành công');
                        return $this->redirect(['view_my_lead_donor', 'id' => $model->id, 'acitve' => 1]);
                    } else {
                        Yii::error($model->getErrors());
                        Yii::$app->getSession()->setFlash('error', 'Lỗi hệ thống vui lòng thử lại');
                    }
                } else {
                    Yii::$app->getSession()->setFlash('error', 'Lỗi hệ thống, vui lòng thử lại');
                }
            } else {
                $model->image = $image_old;
                $model->save();
                Yii::$app->getSession()->setFlash('success', 'Cập nhật doanh nghiệp đỡ đầu thành công');
            }
            return $this->redirect(['view_my_lead_donor', 'id' => $model->id, 'acitve' => 1]);
        } else {
            return $this->render('update_my_lead_donor', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing LeadDonor model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */


    public function actionChange_to_start($id)
    {
        Yii::$app->db->createCommand("UPDATE lead_donor SET status='" . LeadDonor::STATUS_ACTIVE . "' WHERE id= $id")
            ->execute();
        $searchModel1 = new CampaignSearch();
        $params1 = Yii::$app->request->queryParams;
        $params1['CampaignSearch']['lead_donor_id'] = $id;
        $dataProvider1 = $searchModel1->search($params1);
        $searchModel = new VillageSearch();
        $params = Yii::$app->request->queryParams;
        $params['VillageSearch']['lead_donor_id'] = $id;
        $dataProvider = $searchModel->search($params);
        $model1 = new Village();
        Yii::$app->getSession()->setFlash('success', 'Đã kích hoạt thành công doanh nghiệp đỡ đầu');
        return $this->render('view', [
            'model' => $this->findModel($id),
            'model1' => $model1,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'searchModel1' => $searchModel1,
            'dataProvider1' => $dataProvider1,
            'modelcam' => Campaign::findAll(['lead_donor_id' => $id]),
            'active' => 1,
            'model2' => User::findAll(['lead_donor_id' => $id]),
        ]);
    }

    public function actionDelete($id)
    {
        $searchModel1 = new CampaignSearch();
        $params1 = Yii::$app->request->queryParams;
        $params1['CampaignSearch']['lead_donor_id'] = $id;
        $dataProvider1 = $searchModel1->search($params1);
        $searchModel = new VillageSearch();
        $params = Yii::$app->request->queryParams;
        $params['VillageSearch']['lead_donor_id'] = $id;
        $dataProvider = $searchModel->search($params);
        $model1 = new Village();
        Yii::$app->db->createCommand("UPDATE lead_donor SET status='" . LeadDonor::STATUS_BLOCK . "' WHERE id= $id")
            ->execute();
        Yii::$app->getSession()->setFlash('success', 'Đã tạm dừng thành công doanh nghiệp đỡ đầu');
        return $this->render('view', [
            'model' => $this->findModel($id),
            'model1' => $model1,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'searchModel1' => $searchModel1,
            'dataProvider1' => $dataProvider1,
            'modelcam' => Campaign::findAll(['lead_donor_id' => $id]),
            'active' => 1,
            'model2' => User::findAll(['lead_donor_id' => $id]),
        ]);
    }

    public function actionAdd_village($id)
    {
        $village = new VillageForm();
//            $village->setScenario('add_village');
        if ($village->load(Yii::$app->request->post())) {

            $village_id = $village['village_id'];
            Yii::$app->db->createCommand("UPDATE village SET lead_donor_id = $id  WHERE id= $village_id")
                ->execute();
            Yii::$app->getSession()->setFlash('success', 'Đã thêm xã thành công ');
//                $model1 = new Village();
//                $searchModel = new VillageSearch();
//                $params= Yii::$app->request->queryParams;
//                $params['VillageSearch']['lead_donor_id'] = $id ;
//                $dataProvider = $searchModel->search($params);
            return $this->redirect(['view', 'id' => $id, 'active' => 2]);
        }
    }

    public function actionTrash_village($id, $id_vi)
    {
        Yii::$app->db->createCommand("UPDATE village SET lead_donor_id = 0  WHERE id= $id_vi")
            ->execute();
        Yii::$app->getSession()->setFlash('success', 'Đã xóa xã khỏi doanh nghiệp đỡ đầu thành công ');
        return $this->redirect(['view', 'id' => $id, 'active' => 2]);

    }

    /**
     * Finds the LeadDonor model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return LeadDonor the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = LeadDonor::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
