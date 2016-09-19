<?php

namespace backend\controllers;

use common\auth\filters\Yii2Auth;
use common\models\Category;
use common\models\CategorySearch;
use common\models\News;
use kartik\form\ActiveForm;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\UploadedFile;

/**
 * CategoryController implements the CRUD actions for Category model.
 */
class CategoryController extends Controller
{
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
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Category models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CategorySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Category model.
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
     * Creates a new Category model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Category();

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post())) {
            $check = Category::find()->andWhere("status != :status")->addParams([':status'=>Category::STATUS_DELETED])->andWhere(['display_name'=> $model->display_name])->one();
            if(isset($check)) {
                Yii::$app->getSession()->setFlash('error', 'Tên danh mục đã tồn tại. Vui lòng chọn tên khác!');
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
            $image = UploadedFile::getInstance($model, 'images');
            if ($image) {
                $file_name = Yii::$app->user->id . '.' . uniqid() . time() . '.' . $image->extension;
                if ($image->saveAs(Yii::getAlias('@webroot') . "/" . Yii::getAlias('@cat_image') . "/" . $file_name)) {
                    $model->images = $file_name;
                }
            }
            $model->child_count = 0;
            $model->order_number = $model->order_number != null ? $model->order_number : 0;
            if ($model->save()) {
                if ($model->parent_id != null) {
                    $modelParent = $model->parent;
                    $modelParent->child_count++;
                    $model->order_number = $modelParent->child_count;
                    $model->level = $modelParent->level + 1;
                    $modelParent->save();
                } else {
                    $model->level = 0;
                    $model->child_count = 0;
                    $maxOrder = Category::find()
                        ->select(['max(order_number) as `order`'])
                        ->where('level=0')
                        ->andWhere(['type' => $model->type])->scalar();
                    $model->order_number = $maxOrder + 1;
                }
                if ($model->save()) {
                    Yii::$app->getSession()->setFlash('success', 'Tạo danh mục tin tức thành công');
                    return $this->redirect(['index']);
                } else {
                    Yii::$app->getSession()->setFlash('success', 'Lỗi hệ thống, vui lòng thử lại');
                    Yii::info($model->getErrors());
                }
            } else {
                Yii::info($model->getErrors());
                Yii::$app->getSession()->setFlash('error', 'Lỗi lưu danh mục');
            }

        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Category model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post())){
            $check = Category::find()->andWhere("status != :status")->addParams([':status'=>Category::STATUS_DELETED])->andWhere(['display_name'=> $model->display_name])->one();
            if(isset($check)) {
                Yii::$app->getSession()->setFlash('error', 'Tên danh mục đã tồn tại. Vui lòng chọn tên khác!');
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
            if($model->save()) {
                Yii::$app->session->setFlash('success', 'Cập nhật danh mục tin tức thành công!');
                return $this->redirect(['index']);
            }
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Category model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        if ($model->status == Category::STATUS_ACTIVE) {
            Yii::$app->getSession()->setFlash('error', 'Bạn không thể xóa danh mục tin tức ở trạng thái Hoạt động');
            return $this->redirect(['index']);
        }

        $news = News::find()->joinWith('newsCategoryAsms')->andFilterWhere(['!=', 'news.status', News::STATUS_DELETED])
            ->andWhere(['news_category_asm.category_id' => $model->id])->all();
        if ($news) {
            Yii::$app->getSession()->setFlash('error', 'Bạn không thể xóa danh mục tin tức đang chứa bài viết');
            return $this->redirect(['index']);
        }

        $model->status = Category::STATUS_DELETED;
        if ($model->save()) {
            Yii::$app->getSession()->setFlash('success', 'Xóa danh mục tin tức thành công');
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the Category model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Category the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Category::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
