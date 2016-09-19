<?php

namespace backend\controllers;

use common\helpers\CommonUtils;
use Yii;
use common\models\Bank;
use common\models\BankSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * BankController implements the CRUD actions for Bank model.
 */
class BankController extends Controller
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
        ];
    }

    /**
     * Lists all Bank models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BankSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Bank model.
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
     * Creates a new Bank model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Bank();

        if ($model->load(Yii::$app->request->post())) {

            $image = UploadedFile::getInstance($model, 'image');
            if ($image) {
                $file_name = CommonUtils::removeSpace(CommonUtils::removeSign($model->name)) . '.' . $image->extension;
                if ($image->saveAs(Yii::getAlias('@webroot') . "/" . Yii::getAlias('@bank_image') . "/" . $file_name)) {
                    $model->image = $file_name;
                } else {
                    Yii::$app->getSession()->setFlash('error', 'Không thành công, vui lòng thử lại');
                }
            } else {
                Yii::$app->getSession()->setFlash('error', 'Không được để trống ảnh đại diện');
            }
            if ($model->save()) {
                Yii::$app->getSession()->setFlash('success', 'Thành công');
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                Yii::$app->getSession()->setFlash('error', 'Thất bại');
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Bank model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $thumbnail = UploadedFile::getInstance($model, 'image');
            if ($thumbnail) {
                $file_name = CommonUtils::removeSpace(CommonUtils::removeSign($model->name)) . '.' . $thumbnail->extension;
                if ($thumbnail->saveAs(Yii::getAlias('@webroot') . "/" . Yii::getAlias('@bank_image') . "/" . $file_name)) {
                    $model->image = $file_name;
                } else {
                    Yii::$app->getSession()->setFlash('error', 'Không thành công, vui lòng thử lại');
                }
            } else {
                Yii::$app->getSession()->setFlash('error', 'Không được để trống ảnh đại diện');
            }
            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }

        }
        return $this->render('update', [
            'model' => $model,
        ]);

    }

    /**
     * Deletes an existing Bank model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Bank model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Bank the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Bank::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
