<?php

namespace backend\controllers;

use common\auth\filters\Yii2Auth;
use common\models\RequestGallery;
use common\models\User;
use Yii;
use common\models\DonationRequest;
use common\models\DonationRequestSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * DonationRequestController implements the CRUD actions for DonationRequest model.
 */
class DonationRequestController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
            'auth' => [
                'class' => Yii2Auth::className(),
                'autoAllow' => false,
            ],
        ];
    }

    /**
     * Lists all DonationRequest models.
     * @return mixed
     */
    public function actionIndex()
    {

        $searchModel = new DonationRequestSearch();
        $params = Yii::$app->request->queryParams;
        /** @var User $user */
        $user = Yii::$app->user->identity;
        if ($user->type == User::TYPE_LEAD_DONOR && $user->lead_donor_id) {
            $params['CampaignSearch']['lead_donor_id'] = $user->lead_donor_id;
        }
        if ($user->type == User::TYPE_VILLAGE && $user->village_id) {
            $params['CampaignSearch']['village_id'] = $user->village_id;
        }
        $dataProvider = $searchModel->search($params);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionUpdateStatus($id, $type)
    {
        $model = $this->findModel($id);
        $model->status = $type;
        if ($model->save()) {
            Yii::$app->session->setFlash('success', 'Cập nhật trạng thái thành công!');
        } else {
            Yii::error($model->getErrors());
            Yii::$app->session->setFlash('error', 'Lỗi hệ thống, vui lòng thử lại!');
        }
        return $this->redirect(['view', 'id' => $model->id]);
    }

    /**
     * Displays a single DonationRequest model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        $gallery = RequestGallery::find()->andWhere(['donation_request_id' => $id])->all();
        return $this->render('view', [
            'model' => $model,
            'active' => 1,
            'gallery' => $gallery
        ]);
    }

    /**
     * Creates a new DonationRequest model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new DonationRequest();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
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

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    public function actionReject($id)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post())) {
            $model->status = DonationRequest::STATUS_REJECTED;
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Cập nhật trạng thái thành công!');
            } else {
                Yii::error($model->getErrors());
                Yii::$app->session->setFlash('error', 'Lỗi hệ thống, vui lòng thử lại!');
            }
        }
        return $this->redirect(['view', 'id' => $model->id]);
    }

    /**
     * Deletes an existing DonationRequest model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        Yii::$app->db->createCommand("UPDATE donation_request SET status = 4  WHERE id= $id")
            ->execute();
        Yii::$app->getSession()->setFlash('success', 'Đã xóa yêu cầu trợ giúp thành công ');
//        $this->findModel($id)->delete();
        return $this->redirect(['index']);
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
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
