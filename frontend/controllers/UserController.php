<?php
namespace frontend\controllers;

use common\helpers\Brandname;
use common\models\Campaign;
use common\models\CampaignDonationItemAsm;
use common\models\DonationItem;
use common\models\DonationRequest;
use common\models\LeadDonor;
use common\models\LoginForm;
use common\models\User;
use DateTime;
use frontend\helpers\UserHelper;
use frontend\models\Muser;
use yii\db\mssql\PDO;
use frontend\models\SignupForm;
use kartik\alert\Alert;
use Yii;

use yii\bootstrap\ActiveForm;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\UploadedFile;


/**
 * Site controller
 */
class UserController extends Controller
{

    public function actionSetting($active = 1)
    {
        $id = Yii::$app->user->id;
        /** @var User $model */
        $model = User::findOne($id);

        if (!$model) {
            throw  new BadRequestHttpException('Người dùng không tồn tại');
        }
        $oldAvatar = $model->avatar;
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post())) {
            $avatar = UploadedFile::getInstance($model, 'avatar');
            if ($avatar) {
                $file_name = Yii::$app->user->id . time() . '.' . $avatar->extension;
                if ($avatar->saveAs(Yii::getAlias('@webroot') . "/" . Yii::$app->params['avatar'] . "/" . $file_name)) {
                    $model->avatar = $file_name;
                }
            } else {
                $model->avatar = $oldAvatar;
            }
            if ($model->save()) {
                Yii::$app->getSession()->setFlash('success', Yii::t('app', 'Lưu thay đổi thành công'));
            }
        }
        return $this->render('setting', ['model' => $model, 'active' => $active]);
    }
    public function actionChangeMyPassword(){
        $id = Yii::$app->user->identity->id;
        /** @var User $model */
        $model = User::findOne($id);
        if (!$model) {
            throw  new BadRequestHttpException('Người dùng không tồn tại');
        }

        $model->setScenario('user-setting');

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
//            echo "<pre>";
//            print_r($model);
//            die();
            $model->setPassword($model->setting_new_password);
            $model->password_reset_token = $model->setting_new_password;
            if ($model->save(false)) {
                Yii::$app->getSession()->setFlash('success',UserHelper::multilanguage('Đổi mật khẩu thành công','Change password success'));
                return $this->redirect(['my-page','id'=>$id]);
            } else {
                Yii::warning($model->getErrors());
                Yii::$app->getSession()->setFlash('danger', UserHelper::multilanguage('Đổi mật khẩu không thành công','Change password unsuccess'));
                return $this->redirect(['my-page','id'=>$id]);
            }
        }
        return $this->render('change-my-password',[
            'model'=>$model,
        ]);
    }

    public function actionChangePassword()
    {
        $id = Yii::$app->user->id;
        /** @var User $model */
        $model = User::findOne($id);
        if (!$model) {
            throw  new BadRequestHttpException('Người dùng không tồn tại');
        }
        $model->setScenario('user-setting');
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;

            return ActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->setPassword($model->setting_new_password);
            $model->password_reset_token = $model->setting_new_password;
            if ($model->save(false)) {
                Yii::$app->getSession()->setFlash('success', Yii::t('app', 'Đổi mật khẩu thành công'));
            } else {

                Yii::warning($model->getErrors());
            }
        }
        return $this->redirect(['setting', 'active' => 2]);
    }

    public function actionIndex($active = 1)
    {
        $view = 'index';
        /** @var User $model */
        $model = $this->findModel(Yii::$app->user->id);
        if ($model->type == User::TYPE_ORGANIZATION) {
            // create post
            $view = 'index/partner_index';
        } elseif ($model->type == User::TYPE_DONOR) {
            $view = 'index/donor_index';
        } else {
            $view = 'index/donee_index';
        }
        return $this->render($view, ['model' => $model, 'active' => $active]);
    }

    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('Nội dung không tồn tại.');
        }
    }

    public function actionDetail($id,$active = 1)
    {
        $currentUser=null;
        if(!Yii::$app->user->isGuest){
            $currentUser = $this->findModel(Yii::$app->user->id);
        }

        /** @var User $model */
        $model = $this->findModel($id);

        $view = 'partner_view';

        return $this->render($view, ['model' => $model,'currentModel'=>$currentUser, 'active' => $active]);
    }

    // add by TuanPham 20160810

    public function actionMyPage($id){
        $model = User::findOne(['id'=>$id]);
        return $this->render('my-page',[
            'model'=>$model,
        ]);
    }


    public function actionUpdate($id){
        $model = Muser::findOne(['id'=>$id, 'status' => User::STATUS_ACTIVE]);
        if(!$model){
            throw  new BadRequestHttpException('Người dùng không tồn tại');
        }
//        $model->setScenario('update');

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        $avatar_old = $model->avatar;


        if ($model->load(Yii::$app->request->post())) {
            $model->birthday = $model->birthday?date('d/m/Y',strtotime($model->birthday)):'';
            $avatar  = UploadedFile::getInstance($model, 'avatar');
            if ($avatar) {
                $avatar_name = Yii::$app->user->id . '.' . uniqid() . time() . '.' . $avatar->extension;
                if ($avatar->saveAs(Yii::getAlias('@webroot') . "/" . Yii::getAlias('@avatar') . "/" . $avatar_name)) {
                    $model->avatar = $avatar_name;
                    $model->birthday = $model->birthday?date('Y-m-d H:i:s',strtotime(DateTime::createFromFormat("d/m/Y", $model->birthday)->setTime(0,0)->format('Y-m-d H:i:s'))):'';

                    if ($model->save()) {
                        Yii::$app->session->setFlash('success', 'Cập nhật thành công thông tin người dùng!');
                        return $this->redirect(['my-page', 'id' => $model->id]);
                    } else {
                        Yii::error($model->getErrors());
                        Yii::$app->getSession()->setFlash('error', 'Lỗi hệ thống vui lòng thử lại');
                    }
                } else {
                    Yii::$app->getSession()->setFlash('error', 'Lỗi hệ thống, vui lòng thử lại');
                }
            }else {
                $model->avatar = $avatar_old;
                $model->birthday = $model->birthday?date('Y-m-d H:i:s',strtotime(DateTime::createFromFormat("d/m/Y", $model->birthday)->setTime(0,0)->format('Y-m-d H:i:s'))):'';
                $model->save();
                Yii::$app->getSession()->setFlash('success', 'Cập nhật thành công thông tin người dùng');
                return $this->redirect(['my-page', 'id' => $model->id]);
            }
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }
}
