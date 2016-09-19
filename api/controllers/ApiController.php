<?php
namespace api\controllers;

use api\helpers\authentications\IdentifyMsisdn;
use common\models\ServiceProvider;
use common\models\ServiceProviderApiCredential;
use common\models\SubscriberTransaction;
use common\models\UserAccessToken;
use Yii;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\QueryParamAuth;
use yii\rest\Controller;
use yii\web\BadRequestHttpException;
use yii\web\Response;
use yii\web\UnauthorizedHttpException;

/**
 * Base controller for API app
 * @author Nguyen Chi Thuc (gthuc.nguyen@gmail.com)
 */
class ApiController extends Controller
{
    const HEADER_API_KEY = "X-VnDonor-App-Key";
    const HEADER_SECRET_KEY = "X-VnDonor-Secret-Key";
    const HEADER_PACKAGE_NAME = "X-VnDonor-PackageName";

    const API_KEY_IOS = "o7m7c5u7hv1n";
    const SECRET_KEY_IOS = "7DA9D76DC7124091";

    const API_KEY_ANDROID = "tsydhbk1foq2";
    const PACKAGE_NAME_ANDROID = "vn.vivas.vndonor";

    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items',
    ];

    public $is_ios = false;
    public $is_android = false;
//    public $channel = SubscriberTransaction::CHANNEL_TYPE_MOBILE_PORTAL;

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => CompositeAuth::className(),
            'authMethods' => [
                HttpBearerAuth::className(),
                // them tham so 'access-token' vao query
                QueryParamAuth::className(),
            ],
        ];
        $behaviors['contentNegotiator']['formats'] = ['application/json' => Response::FORMAT_JSON];
        $behaviors['corsFilter'] = ['class' => \yii\filters\Cors::className(),];

        return $behaviors;
    }

    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        $api_key = Yii::$app->request->headers->get(static::HEADER_API_KEY);
        //throw new UnauthorizedHttpException($api_key);
        $lang = $this->getParameter("l", "vi-VN");
        \Yii::$app->language = $lang;
        Yii::info($lang);

        if (!$api_key) {
            throw new UnauthorizedHttpException('Missing api key');
        } else if ($api_key == static::API_KEY_IOS) {
            $this->is_ios = true;
            $secret_key = Yii::$app->request->headers->get(static::HEADER_SECRET_KEY);
            if (!$secret_key || ($secret_key != static::SECRET_KEY_IOS)) {
                throw new UnauthorizedHttpException('Invalid secret key');
            }
        } else if ($api_key == static::API_KEY_ANDROID) {
            $this->is_android = true;
            $packageName = Yii::$app->request->headers->get(static::HEADER_PACKAGE_NAME);
//            $fingerprint = Yii::$app->request->headers->get(static::HEADER_FINGERPRINT);
            if (!$packageName || ($packageName != static::PACKAGE_NAME_ANDROID)
//                || !$fingerprint || ($fingerprint != $credential->certificate_fingerprint)
            ) {
                throw new UnauthorizedHttpException('Invalid package name or certificate fingerprint');
            }
        } else {
            throw new UnauthorizedHttpException('Invalid api key');
        }

        // goi cai nay truoc de trigger event EVENT_BEFORE_ACTION
        $res = parent::beforeAction($action);
        return $res;
    }

    /**
     * @inheritdoc
     */
    protected function verbs()
    {
        return [
            'index' => ['GET', 'HEAD'],
        ];
    }

    /**
     * Checks the privilege of the current user.
     *
     * This method should be overridden to check whether the current user has the privilege
     * to run the specified action against the specified data model.
     * If the user does not have access, a [[ForbiddenHttpException]] should be thrown.
     *
     * @param string $action the ID of the action to be executed
     * @param object $model the model to be accessed. If null, it means no specific model is being accessed.
     * @param array $params additional parameters
     * @throws ForbiddenHttpException if the user does not have access
     */
    public function checkAccess($action, $model = null, $params = [])
    {
    }

    /**
     * replace message
     *
     * @param $message
     * @param $params
     * @return mixed
     */
    public static function replaceParam($message, $params)
    {
        if (is_array($params)) {
            $cnt = count($params);
            for ($i = 1; $i <= $cnt; $i++) {
                $message = str_replace('{' . $i . '}', $params[$i - 1], $message);
            }
        }
        return $message;
    }

    /**
     * get value of parameter
     *
     * @param $param_name
     * @param null $default
     * @return mixed
     */
    public function getParameter($param_name, $default = null)
    {
        return \Yii::$app->request->get($param_name, $default);
    }

    /**
     * get value of parameter
     *
     * @param $param_name
     * @param null $default
     * @return mixed
     */
    public function getParameterPost($param_name, $default = null)
    {
        return \Yii::$app->request->post($param_name, $default);
    }

    /**
     * set status code response
     *
     * @param $code
     */
    public function setStatusCode($code)
    {
        Yii::$app->response->setStatusCode($code);
    }

    /**
     * @param $params array
     * @return bool
     */
    public function checkRequireParams($params)
    {
        foreach ($params as $param) {
            if (empty($param)) {
                throw new BadRequestHttpException("Invalid parameters");
            }
        }
        return true;
    }

}
