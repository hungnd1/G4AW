<?php
/**
 * Created by PhpStorm.
 * User: Linh
 * Date: 12/03/2016
 * Time: 3:27 PM
 */

namespace frontend\controllers;


use common\helpers\Brandname;
use common\models\Campaign;
use common\models\DonationRequest;
use common\models\Transaction;
use common\models\User;
use frontend\models\LoginForm;
use yii\helpers\Html;
use yii\web\Controller;
use yii\web\Response;

class AjaxController extends Controller
{
    public function actionLoadSigninContent()
    {

        $success = true;
        $data = null;
        $model = new LoginForm();
        $data = $this->renderAjax('_signin_content', ['model' => $model]);

        \Yii::$app->response->format = Response::FORMAT_JSON;
        return ['data' => $data, 'success' => $success];

    }

    public function actionFollowCampaign()
    {

        $success = true;
        $message = null;
        $type = 1; // 1: unfollow, 2: follow
        $campaignId = \Yii::$app->request->post('campaign_id');
        /** @var User $user */
        $user = User::findOne(\Yii::$app->user->id);
        /** @var Campaign $campaign */
        $campaign = Campaign::findOne($campaignId);
        if (!$campaign || !$user) {
            $message = 'Không tìm thấy dữ liệu';
            $success = false;
        } else {
            $saveResult = $user->followCampaign($campaign);
            if ($saveResult) {
                if ($saveResult === 1) {
                    $message = Html::tag('i', '', ['class' => 'fa fa-heart']) . 'Theo dõi';
                    $type = 1;
                } else {
                    $message = Html::tag('i', '', ['class' => 'fa fa-heart']) . 'Đang theo dõi';
                    $type = 2;
                }

                $success = true;
            } else {
                $message = 'Theo dõi thất bại';
                $success = false;
            }
        }
        \Yii::$app->response->format = Response::FORMAT_JSON;
        return ['message' => $message, 'success' => $success, 'type' => $type];

    }

    /**
     * duyet request
     * @return array
     */
    public function actionApprove()
    {

        $success = true;
        $message = null;
        $type = 1; // 1: unapprove, 2: approve
        $requestId = \Yii::$app->request->post('request_id');
        /** @var User $user */
        $user = User::findOne(\Yii::$app->user->id);
        /** @var DonationRequest $request */
        $request = DonationRequest::findOne($requestId);
        if (!$request || !$user) {
            $message = 'Không tìm thấy dữ liệu';
            $success = false;
        } else {
            $saveResult = $request->approveHandler();
            if ($saveResult) {
                if ($saveResult === 1) {

                    $message = 'Dữ liệu không hợp lệ';
                    $success = false;
                } else if ($saveResult) {
                    $message = '<i class="fa fa-check"></i>Đã duyệt';
                    $success = true;
                }
            } else {
                $message = 'Duyệt thất bại';
                $success = false;
            }
        }
        \Yii::$app->response->format = Response::FORMAT_JSON;
        return ['message' => $message, 'success' => $success, 'type' => $type];

    }

    /**
     * duyet request
     * @return array
     */
    public function actionRejectRequest()
    {

        $success = true;
        $message = null;
        $type = 1; // 1: unapprove, 2: approve
        $requestId = \Yii::$app->request->post('request_id');
        /** @var User $user */
        $user = User::findOne(\Yii::$app->user->id);
        /** @var DonationRequest $request */
        $request = DonationRequest::findOne($requestId);
        if (!$request || !$user) {
            $message = 'Không tìm thấy dữ liệu';
            $success = false;
        } else {
            $saveResult = $request->rejectHandler();
            if ($saveResult) {
                if ($saveResult === 1) {

                    $message = 'Dữ liệu không hợp lệ';
                    $success = false;
                } else if ($saveResult) {
                    $message = 'Đã hủy';
                    $success = true;
                }


            } else {
                $message = 'Hủy thất bại';
                $success = false;
            }
        }
        \Yii::$app->response->format = Response::FORMAT_JSON;
        return ['message' => $message, 'success' => $success, 'type' => $type];

    }

    public function actionFollowUser()
    {

        $success = true;
        $message = null;
        $type = 1; // 1: unfollow, 2: follow
        $followId = \Yii::$app->request->post('follow_id');
        /** @var User $user */
        $user = User::findOne(\Yii::$app->user->id);
        $followedUser = User::findOne($followId);

        if (!$followedUser || !$user) {
            $message = 'Không tìm thấy dữ liệu';
            $success = false;
        } else {
            $saveResult = $user->followUser($followedUser);
            if ($saveResult) {
                if ($saveResult === 1) {
                    $message = 'Theo dõi';
                    $type = 1;
                } else {
                    $message = 'Đang theo dõi';
                    $type = 2;
                }

                $success = true;
            } else {
                $message = 'Theo dõi thất bại';
                $success = false;
            }
        }
        \Yii::$app->response->format = Response::FORMAT_JSON;
        return ['message' => $message, 'success' => $success, 'type' => $type];

    }

    public function actionDonateByCard()
    {
        $model = new Transaction();
        $request = \Yii::$app->getRequest();
        $success = true;
        $message = null;
        if ($request->isPost && $model->load($request->post())) {
            $model->amount=100000;
            if($model->saveTransaction()){

                $message= $this->renderPartial('/site/donate_msg/success');

                Brandname::sendSms($model);

                $success=true;
            }else{
                $message= $this->renderPartial('/site/donate_msg/error');
                $success=true;
            }

        }
        \Yii::$app->response->format = Response::FORMAT_JSON;
        return ['message' => $message, 'success' => $success];
    }
}