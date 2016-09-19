<?php
namespace frontend\controllers\dropzone;

use frontend\models\Image;
use Yii;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\Response;

/**
 * Site controller
 */
class ImageController extends Controller
{
    /**
     * @inheritdoc
     */


    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'upload-product' => [
                'class' => 'frontend\widgets\dropzone\UploadAction',
            ],
        ];
    }


}
