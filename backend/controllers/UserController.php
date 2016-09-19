<?php

namespace backend\controllers;

use common\components\ActionProtectSuperAdmin;
use kartik\widgets\ActiveForm;
use Yii;
use common\models\User;
use common\models\UserSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\AuthAssignment;
use yii\web\Response;
use common\auth\filters\Yii2Auth;
use common\models\AuthItem;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
{
    public function behaviors()
    {
        return [
            'auth' => [
                'class' => Yii2Auth::className(),
                'autoAllow' => false,
//                'authManager' => 'authManager',
                'except' => ['info', 'update-owner', 'owner-change-password'],
            ],
            [
                'class' => ActionProtectSuperAdmin::className(),
                'user' => Yii::$app->user,
                'update_user' => function ($action, $params) {
                    return $model = User::findOne($params['id']);
                },
                'only' => ['update', 'delete', 'view']
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post', 'get'],
                ],
            ],
        ];
    }

    /**
     * Lists all User models.
     * @param $type
     * @return string
     */
    public function actionIndex($type = User::TYPE_ADMIN)
    {
        $searchModel = new UserSearch();
        $searchModel->type = $type;
        $params = Yii::$app->request->queryParams;
        $dataProvider = $searchModel->search($params);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            "type" => $type,
        ]);
    }

    /**
     * Displays a single User model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id, $active = 1)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
            'active' => $active
        ]);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     *  * @param $type
     */
    public function actionCreate($type = User::TYPE_ADMIN)
    {
//        if ($type != User::TYPE_ADMIN && $type != User::TYPE_ORGANIZATION) {
//            Yii::$app->session->setFlash('error', 'Không có quyền tạo!');
//            return $this->redirect(['index', "type" => $type]);
//        }
        $model = new User();
        $model->type = $type;
        $model->setScenario('create');

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post())) {
            $model->setPassword($model->password);
            $model->generateAuthKey();
            $model->type = $type;
            if ($model->save()) {
                $model->setUserCode();
                $model->save();
                User::getItemNameFromAuthAssignment($model->id,$type);
                Yii::$app->session->setFlash('success', 'Tạo tài khoản thành công!');
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                Yii::$app->session->setFlash('error', 'Tạo tài khoản không thành công!');
                Yii::error($model->getErrors());
                return $this->redirect(['index', "type" => $type]);
            }
        } else {
            return $this->render('create', [
                'model' => $model,
                'type' => $type
            ]);
        }
    }

    /**
     * Updates an existing User model.
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

        if ($model->load(Yii::$app->request->post())) {
            if($model->save()){
                Yii::$app->session->setFlash('success', 'Cập nhật thành công thông tin người dùng!');
                return $this->redirect(['view', 'id' => $model->id]);
            }
            else
            {
                Yii::$app->session->setFlash('error', 'Cập nhật tài khoản không thành công!');
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        }else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
//        $model = $this->findModel($id);
//
//        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
//            Yii::$app->response->format = Response::FORMAT_JSON;
//            return ActiveForm::validate($model);
//        }
//
//        if ($model->load(Yii::$app->request->post()) && $model->update()) {
//            Yii::$app->session->setFlash('success', 'Cập nhật thành công thông tin người dùng!');
//            return $this->redirect(['view', 'id' => $model->id]);
//        } else {
//            Yii::$app->session->setFlash('error', 'Lỗi hệ thống');
//            Yii::error($model->getErrors());
//            return $this->render('update', [
//                'model' => $model,
//            ]);
//        }
    }

    public function actionInfo()
    {
        $user = Yii::$app->user->identity;
        return $this->render('info', ['model' => $user]);
    }

    public function actionUpdateOwner()
    {
        /**
         * @var $model User
         */
        $model = Yii::$app->user->identity;

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Cập nhật thành công thông tin người dùng!');
            return $this->redirect(['info']);
        } else {
            Yii::$app->session->setFlash('error', 'Cập nhật không thành công thông tin người dùng!');
            return $this->redirect(['info']);
        }
    }

    public function actionChangePassword($id)
    {
        $model = $this->findModel($id);
        $model->setScenario('change-password');

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->setPassword($model->new_password);
            $model->generateAuthKey();
            $model->old_password = $model->new_password;
            if ($model->update()) {
                Yii::$app->session->setFlash('success', 'Thay đổi mật khẩu người dùng ' . $model->username . ' thành công!');
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                Yii::error($model->getErrors());
            }
        } else {
            Yii::info($model->getErrors());
            return $this->redirect(['view', 'id' => $model->id]);
        }
    }

    public function actionOwnerChangePassword()
    {
        /**
         * @var $model User
         */
        $model = Yii::$app->user->identity;
        $model->setScenario('change-password');

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->setPassword($model->new_password);
            $model->generateAuthKey();
            $model->old_password = $model->new_password;
            if ($model->update()) {
                Yii::$app->session->setFlash('success', 'Thay đổi mật khẩu user "' . $model->username . '" thành công!');
                return $this->redirect(['info']);
            } else {
                Yii::error($model->getErrors());
            }
        } else {
            Yii::$app->session->setFlash('error', 'Thay đổi mật khẩu user "' . $model->username . '" không thành công!');
            return $this->redirect(['info']);
        }
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
//        $this->findModel($id)->delete();
//
//        return $this->redirect(['index']);

        $model = $this->findModel($id);
        if ($model->id == Yii::$app->user->getId()) {
            Yii::$app->session->setFlash('error', 'Bạn không thể thực hiện chức năng này!');
            return $this->redirect(['index']);
        }
        $model->status = User::STATUS_DELETED;
        if ($model->save()) {
            Yii::$app->session->setFlash('success', 'Xóa User thành công!');
            return $this->redirect(['index','type'=>$model->type]);
        }

    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function actionRevokeAuthItem()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $post = Yii::$app->request->post();

        $success = false;
        $message = "Tham số không đúng";

        if (isset($post['user']) && isset($post['item'])) {
            $user = $post['user'];
            $item = $post['item'];

            $mapping = AuthAssignment::find()->andWhere(['user_id' => $user, 'item_name' => $item])->one();
            if ($mapping) {
                if ($mapping->delete()) {
                    $success = true;
                    $message = "Đã xóa quyền '$item' khỏi user '$user'!";
                } else {
                    $message = "Lỗi hệ thống, vui lòng thử lại sau";
                }
            } else {
                $message = "Quyền '$item' chưa được cấp cho user '$user'!";
            }

        }

        return [
            'success' => $success,
            'message' => $message
        ];
    }

    /**
     * add items to user
     * @param  $id - id of user
     * @return mixed
     */
    public function actionAddAuthItem($id)
    {
        /* @var $model User */
        $model = User::findOne(['id' => $id]);

        Yii::$app->response->format = Response::FORMAT_JSON;

        $success = false;
        $message = "User/nhóm quyền không tồn tại'";

        if ($model) {
            $post = Yii::$app->request->post();

            if (isset($post['addItems'])) {
                $items = $post['addItems'];

                $count = 0;

                foreach ($items as $item) {
                    $role = AuthItem::findOne(['name' => $item]);
                    $mapping = new AuthAssignment();
                    $mapping->item_name = $item;
                    $mapping->user_id = $id;
                    if ($mapping->save()) {
                        $count++;
                    }
                }


                if ($count > 0) {
                    $success = true;
                    $message = "Đã thêm $count nhóm quyền cho người dùng '$model->username'";

                }
            }
        }

        return [
            'success' => $success,
            'message' => $message
        ];
    }


    public function actionUpdateStatus($id, $type)
    {
        $model = $this->findModel($id);
        if ($model->type == User::TYPE_LEAD_DONOR) {
            $model->status = $type;
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Cập nhật trạng thái thành công!');
            } else {
                Yii::error($model->getErrors());
                Yii::$app->session->setFlash('error', 'Lỗi hệ thống, vui lòng thử lại!');
            }
        } else {
            Yii::$app->session->setFlash('error', 'Chỉ được duyệt tài khoản tổ chức cầu nối!');
        }
        return $this->redirect(['view', 'id' => $model->id]);
    }
}
