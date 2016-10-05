<?php
namespace backend\controllers;

use common\models\User;
use kartik\form\ActiveForm;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use common\models\LoginForm;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\web\UploadedFile;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['change-password', 'index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                    'change-password'=>['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionLogin()
    {

        $this->layout = '@backend/views/layouts/login';

        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionUpload()
    {

        $uploadedFile = UploadedFile::getInstanceByName('uploads');

        $mime = \yii\helpers\FileHelper::getMimeType($uploadedFile->tempName);
        $file = time()."_".$uploadedFile->name;

        $url = Url::to('@web/uploads/ckeditor/'.$file);
        $uploadPath = Yii::getAlias('@webroot').'/uploads/ckeditor/'.$file;
        Yii::info($mime);
        //extensive suitability check before doing anything with the file…
        if ($uploadedFile==null)
        {
            $message = "No file uploaded.";
        }
        else if ($uploadedFile->size == 0)
        {
            $message = "The file is of zero length.";
        }
//        else if ($mime!="image/jpeg" && $mime!="image/png")
//        {
//            $message = "The image must be in either JPG or PNG format. Please upload a JPG or PNG instead.";
//        }
        else if ($uploadedFile->tempName==null)
        {
            $message = "You may be attempting to hack our server. We're on to you; expect a knock on the door sometime soon.";
        }
        else {
            $message = "";
            $move = $uploadedFile->saveAs($uploadPath);
            if(!$move)
            {
                $message = "Error moving uploaded file. Check the script is granted Read/Write/Modify permissions.";
            }
        }
        $funcNum = $_GET['CKEditorFuncNum'] ;
        echo "<script type='text/javascript'>window.parent.CKEDITOR.tools.callFunction($funcNum, '$url', '$message');</script>";

    }

    public function actionChangePassword(){
        $id = Yii::$app->user->id;
        $model = User::findOne($id);

        if (!$model) {
            throw  new BadRequestHttpException('Người dùng không tồn tại');
        }

        $model->setScenario('user-setting');

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        $pass1 = $model->pass1;
        $pass2 = $model->pass2;
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
//            $model->password_reset_token = $model->setting_new_password;
            $model->setPassword($model->setting_new_password);

            $model->pass1=$model->password_hash;
            $model->pass2 = $pass1;
            $model->pass3 = $pass2;
            if ($model->save(false)) {
                Yii::$app->getSession()->setFlash('success', Yii::t('app', 'Đổi mật khẩu thành công'));
                return $this->redirect(['index']);
            } else {
                Yii::warning($model->getErrors());
                Yii::$app->getSession()->setFlash('danger', Yii::t('app', 'Đổi mật khẩu không thành công'));
                return $this->redirect(['index']);
            }
        }
        return $this->render('change-my-password',[
            'model'=>$model,
        ]);
    }
}
