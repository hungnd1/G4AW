<?php

namespace backend\controllers;

use common\auth\filters\Yii2Auth;
use common\models\Village;
use common\models\VillageSearch;
use kartik\form\ActiveForm;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\UploadedFile;

/**
 * VillageController implements the CRUD actions for Village model.
 */
class VillageController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'auth' => [
                'class' => Yii2Auth::className(),
                'autoAllow' => false,
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
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
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

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
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Village model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Village();
        $model->setScenario('create');
        if ($model->load(Yii::$app->request->post())) {
            $model->created_at = time();
            $model->updated_at = time();
            $image = UploadedFile::getInstance($model, 'image');
            if ($image) {
                $file_name = Yii::$app->user->id . '.' . uniqid() . time() . '.' . $image->extension;
                if ($image->saveAs(Yii::getAlias('@webroot') . "/" . Yii::getAlias('@village_image') . "/" . $file_name)) {
                    $model->image = $file_name;
                }
            }
            if ($model->save()) {
                Yii::$app->getSession()->setFlash('success', 'Thêm xã thành công');
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
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
        $model->establish_date ? $model->establish_date = date('d/m/Y', strtotime($model->establish_date)) : '';
        $image_old = $model->image;
        if ($model->load(Yii::$app->request->post())) {
            $model->establish_date ? $model->establish_date = date('Y-m-d', strtotime($model->establish_date)) : '';
            $image = UploadedFile::getInstance($model, 'image');
            if ($image) {
                $file_name = Yii::$app->user->id . '.' . uniqid() . time() . '.' . $image->extension;
                if ($image->saveAs(Yii::getAlias('@webroot') . "/" . Yii::getAlias('@village_image') . "/" . $file_name)) {
                    $model->image = $file_name;
                    if ($model->save()) {
                        Yii::$app->getSession()->setFlash('success', 'Cập nhật xã thành công');
                        return $this->redirect(['index']);
                    } else {
                        Yii::error($model->getErrors());
                        Yii::$app->getSession()->setFlash('error', 'Lỗi hệ thống vui lòng thử lại');
                    }
                } else {
                    Yii::error($model->getErrors());
                    Yii::$app->getSession()->setFlash('error', 'Lỗi hệ thống vui lòng thử lại');
                }
            } else {
                $model->image = $image_old;
                if ($model->save()) {
                    Yii::$app->getSession()->setFlash('success', 'Cập nhật xã thành công');
                    return $this->redirect(['index']);
                } else {
                    Yii::error($model->getErrors());
                    Yii::$app->getSession()->setFlash('error', 'Lỗi hệ thống vui lòng thử lại');
                }
            }
            return $this->redirect(['index']);
        }else{
            return $this->render('update', ['model' => $model]);
        }

    }

    /**
     * Deletes an existing Village model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public
    function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Village model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Village the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected
    function findModel($id)
    {
        if (($model = Village::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
