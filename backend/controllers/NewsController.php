<?php

namespace backend\controllers;

use common\auth\filters\NewsAuth;
use common\auth\filters\Yii2Auth;
use common\models\NewsCategoryAsm;
use common\models\NewsVillageAsm;
use common\models\User;
use Exception;
use Yii;
use common\models\News;
use common\models\NewsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * NewsController implements the CRUD actions for News model.
 */
class NewsController extends Controller
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
            [
                'class' => NewsAuth::className(),
            ],
        ];
    }

    /**
     * Lists all News models.
     * @return mixed
     */
    public function actionIndex($type = News::TYPE_COMMON)
    {
        $searchModel = new NewsSearch();
        $params = Yii::$app->request->queryParams;

        /** @var User $user */
        $user = Yii::$app->user->identity;
        if ($user->lead_donor_id) {
            $params['NewsSearch']['lead_donor_id'] = $user->lead_donor_id;
        }

        $params['NewsSearch']['type'] = $type;

        $dataProvider = $searchModel->search($params);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'type' => $type,
        ]);
    }

    /**
     * Displays a single News model.
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
     * Creates a new News model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($type = News::TYPE_COMMON)
    {
        /** @var User $user */
        $user = Yii::$app->user->identity;
        $model = new News();
        $model->setScenario('create');
        $model->type = $type;

        if ($model->load(Yii::$app->request->post())) {
            $db_transaction = Yii::$app->db->beginTransaction();
            try {
                $file = UploadedFile::getInstance($model, 'thumbnail');
                if ($file) {
                    $file_name = uniqid() . time() . '.' . $file->extension;
                    if ($file->saveAs(Yii::getAlias('@webroot') . "/" . Yii::getAlias('@uploads') . "/" . $file_name)) {
                        $model->thumbnail = $file_name;

                    } else {
                        Yii::$app->getSession()->setFlash('error', 'Lỗi hệ thống, vui lòng thử lại');
                    }
                }

                $model->user_id = $user->id;
                $model->created_user_id = $user->id;
                if ($user->lead_donor_id) {
                    $model->lead_donor_id = $user->lead_donor_id;
                }
                if($model->status == News::STATUS_ACTIVE){
                    $model->published_at = time();
                }

                if ($model->save()) {
                    if ($model->type == News::TYPE_TRADE || $model->type == News::TYPE_IDEA) {
                        if (isset($model->village_array)) {
                            foreach ($model->village_array as $village_id) {
                                $asm = new NewsVillageAsm();
                                $asm->village_id = $village_id;
                                $asm->news_id = $model->id;
                                if (!$asm->save()) {
                                    Yii::error($asm->getErrors());
                                }
                            }
                        }
                    }

                    NewsCategoryAsm::newNewsCategoryAsm($model->id, $model->category_id);

                    $db_transaction->commit();
                    Yii::$app->getSession()->setFlash('success', 'Thêm bài viết thành công');
                    return $this->redirect(['view', 'id' => $model->id]);
                } else {
                    Yii::error($model->getErrors());
                    Yii::$app->getSession()->setFlash('error', 'Lỗi hệ thống vui lòng thử lại');
                }
            } catch (Exception $e) {
                $db_transaction->rollBack();
                Yii::error($e);
                Yii::$app->getSession()->setFlash('error', 'Không thành công, vui lòng thử lại');
            }
        }
        return $this->render('create', [
            'model' => $model,
            'type' => $type,
        ]);
    }

    /**
     * Updates an existing News model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $thumbnail = $model->thumbnail;

        /** @var NewsCategoryAsm $asm */
        $asm = NewsCategoryAsm::findOne(['news_id' => $model->id]);
        if ($asm) {
            $model->category_id = $asm->category_id;
        }

        $model->village_array = $model->getListVillageSelect2();
        $model->setScenario('update');

        if ($model->load(Yii::$app->request->post())) {
            $db_transaction = Yii::$app->db->beginTransaction();
            try {
                $file = UploadedFile::getInstance($model, 'thumbnail');
                if ($file) {
                    $file_name = uniqid() . time() . '.' . $file->extension;
                    if ($file->saveAs(Yii::getAlias('@webroot') . "/" . Yii::getAlias('@uploads') . "/" . $file_name)) {
                        $model->thumbnail = $file_name;
                    } else {
                        Yii::$app->getSession()->setFlash('error', 'Lỗi hệ thống, vui lòng thử lại');
                    }
                } else {
                    $model->thumbnail = $thumbnail;
                }

                if ($model->save()) {
                    if ($model->type == News::TYPE_TRADE || $model->type == News::TYPE_IDEA) {
                        if (isset($model->village_array)) {
                            Yii::$app->db->createCommand()->delete('news_village_asm', ['news_id' => $model->id])->execute();
                            foreach ($model->village_array as $village_id) {
                                $asm = new NewsVillageAsm();
                                $asm->village_id = $village_id;
                                $asm->news_id = $model->id;
                                if (!$asm->save()) {
                                    Yii::error($asm->getErrors());
                                }
                            }
                        }
                    }
                    NewsCategoryAsm::newNewsCategoryAsm($model->id, $model->category_id);
                    $db_transaction->commit();
                    Yii::$app->getSession()->setFlash('success', 'Cập nhật bài viết thành công');
                    return $this->redirect(['index', 'type' => $model->type]);
                } else {
                    Yii::error($model->getErrors());
                    Yii::$app->getSession()->setFlash('error', 'Lỗi hệ thống vui lòng thử lại');
                }
                return $this->redirect(['view', 'id' => $model->id]);
            } catch (Exception $e) {
                $db_transaction->rollBack();
                Yii::error($e);
                Yii::$app->getSession()->setFlash('error', 'Không thành công, vui lòng thử lại');
            }
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing News model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        if ($model->status == News::STATUS_ACTIVE) {
            Yii::$app->session->setFlash('error', 'Bạn không thể xóa bài viết ở trạng thái Hoạt động!');
        } else {
            $model->status = News::STATUS_DELETED;
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Xóa bài viết thành công!');
            } else {
                Yii::error($model->getErrors());
                Yii::$app->session->setFlash('error', 'Lỗi hệ thống, vui lòng thử lại!');
            }
        }

        return $this->redirect(['index', 'type' => $model->type]);
    }

    /**
     * Finds the News model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return News the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = News::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


    public function actionUpdateStatus($id, $status)
    {
        $model = $this->findModel($id);
        $model->status = $status;
        if($model->status == News::STATUS_ACTIVE){
            $model->published_at = time();
        }
        if ($model->save()) {
            Yii::$app->session->setFlash('success', 'Cập nhật trạng thái thành công!');
        } else {
            Yii::$app->session->setFlash('error', 'Lỗi hệ thống, vui lòng thử lại!');
        }
        return $this->redirect(['view', 'id' => $model->id]);
    }
}
