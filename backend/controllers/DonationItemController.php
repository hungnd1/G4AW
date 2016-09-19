<?php

namespace backend\controllers;

use common\auth\filters\Yii2Auth;
use common\models\DonationItem;
use common\models\DonationItemSearch;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * DonationItemController implements the CRUD actions for DonationItem model.
 */
class DonationItemController extends Controller
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
     * Lists all DonationItem models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DonationItemSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single DonationItem model.
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
     * Creates a new DonationItem model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new DonationItem();

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                Yii::$app->getSession()->setFlash('success', ' Thêm mới danh mục ủng hộ thành công');
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                Yii::$app->getSession()->setFlash('error', 'Không thành công, vui lòng thử lại');
            }

        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing DonationItem model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);


        if ($model->getUsedStatus()) {
            Yii::$app->getSession()->setFlash('error', 'Không thể cập nhật danh mục ủng hộ đã được sử dụng');
            return $this->redirect(['view', 'id' => $model->id]);
        }

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                Yii::$app->getSession()->setFlash('success', ' Cập nhật danh mục ủng hộ thành công');
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                Yii::$app->getSession()->setFlash('error', 'Không thành công, vui lòng thử lại');
            }
        }
        return $this->render('update', [
            'model' => $model,
        ]);

    }

    /**
     * Deletes an existing DonationItem model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        if ($model->getUsedStatus()) {
            Yii::$app->getSession()->setFlash('error', 'Không thể xóa danh mục ủng hộ đã được sử dụng');
            return $this->redirect(['view', 'id' => $model->id]);
        }

        if ($model->delete()) {
            Yii::$app->getSession()->setFlash('success', ' Xóa danh mục ủng hộ thành công');
        } else {
            Yii::$app->getSession()->setFlash('error', 'Không thành công, vui lòng thử lại');
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the DonationItem model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DonationItem the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DonationItem::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
