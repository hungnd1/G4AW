<?php

namespace backend\controllers;

use common\models\UnitLinkSearch;
use kartik\form\ActiveForm;
use Yii;
use common\models\UnitLink;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\web\UploadedFile;

/**
 * UnitLinkController implements the CRUD actions for UnitLink model.
 */
class UnitLinkController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all UnitLink models.
     * @return mixed
     */
    public function actionIndex()
    {
        $model = new UnitLink();
        $searchModel = new UnitLinkSearch();
        $params = Yii::$app->request->queryParams;
        $dataProvider = $searchModel->search($params);

        return $this->render('index', [
            'model'=>$model,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single UnitLink model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new UnitLink model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new UnitLink();
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post())) {
            $file = UploadedFile::getInstance($model, 'image');
            if ($file) {
                $file_name = uniqid() . time() . '.' . $file->extension;
                if ($file->saveAs(Yii::getAlias('@webroot') . "/" . Yii::getAlias('@file_upload') . "/" . $file_name)) {
                    $model->image = $file_name;

                } else {
                    Yii::$app->getSession()->setFlash('error', 'Lỗi hệ thống, vui lòng thử lại');
                }
            }
            $model->created_at = time();
            $model->updated_at = time();
            if($model->save()){
                Yii::$app->getSession()->setFlash('success', 'Thêm đơn vị thành công');
                return $this->redirect(['view', 'id' => $model->id]);
            }else{
                Yii::error($model->getErrors());
                Yii::$app->getSession()->setFlash('error', 'Lỗi hệ thống vui lòng thử lại');
            }

        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing UnitLink model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $image = $model->image;

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post())) {
            $file = UploadedFile::getInstance($model, 'image');
            if ($file) {
                $file_name = uniqid() . time() . '.' . $file->extension;
                if ($file->saveAs(Yii::getAlias('@webroot') . "/" . Yii::getAlias('@file_upload') . "/" . $file_name)) {
                    $model->image = $file_name;
                } else {
                    Yii::$app->getSession()->setFlash('error', 'Lỗi hệ thống, vui lòng thử lại');
                }
            } else {
                $model->image = $image;
            }
            $model->updated_at = time();
            if($model->save()){
                Yii::$app->getSession()->setFlash('success', 'Cập nhật đơn vị thành công');
                return $this->redirect(['index']);
            } else {
                Yii::error($model->getErrors());
                Yii::$app->getSession()->setFlash('error', 'Lỗi hệ thống vui lòng thử lại');
            }
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing UnitLink model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->status = UnitLink::STATUS_INACTIVE;
        if($model->save()){
            Yii::$app->session->setFlash('success', 'Xóa đơn vị thành công!');
        }else {
            Yii::error($model->getErrors());
            Yii::$app->session->setFlash('error', 'Lỗi hệ thống, vui lòng thử lại!');
        }
        return $this->redirect(['index']);
    }

    /**
     * Finds the UnitLink model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return UnitLink the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = UnitLink::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
