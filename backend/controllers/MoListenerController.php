<?php
/**
 * Created by PhpStorm.
 * User: Hoan
 * Date: 3/23/2016
 * Time: 10:48 AM
 */

namespace backend\controllers;

use common\helpers\Brandname;
use common\helpers\CommonUtils;
use common\helpers\CUtils;
use common\models\Campaign;
use common\models\Transaction;
use common\models\User;
use yii\web\Controller;

class MoListenerController extends Controller
{
    const MT_DONATED = "Chan thanh cam on nha hao tam {msisdn} da ung ho 15.000VND cho chien dich tu thien {campaign_id}. Mat khau truy cap he thong cua ban la {password}."; //Vui long truy cap http://vndonor.vivas.vn/ de xem chi tiet.
    const MT_INFO = "Chien dich {campaign_id} da quyen gop duoc {current_amount}VND tren tong so {expected_amount}VND, chien dich se ket thuc vao ngay {expired_at}";
    const MT_THANKS = "Chan thanh cam on nha hao tam {username} da ung ho {money}VND cho chien dich tu thien {campaign_id}";


    const CONTENT_TYPE_TEXT = "0";
    const MESSAGE_TYPE_VALID = "71";
    const MESSAGE_TYPE_INVALID = "72";
    const USERNAME = "hcmtest";
    const PASSWORD = 'LfMUzmQyrXxQtYwgk7VjfU7DOds=';
    const BRANDNAME = 'VNPT.Tech';
    const SHAREKEY = '123456';
    const TYPE = '1';

    public function actionReceiveSms($userId, $serviceNumber, $commandCode, $info, $user, $password)
    {
        if ($user == "vivas" && $password == "1234567") {
            $words = $keyword = $message = $message1 = "";
            //Check content, extract ra $keyword & $message
            $words = preg_split("/\s/", $info, 2);
            $keyword = count($words) >= 1 ? trim($words[0]) : '';
            $message = count($words) >= 2 ? trim($words[1]) : '';

            if (strtolower($keyword) == "ungho") {
                /** @var Campaign $campaign */
                $campaign = Campaign::findOne(['campaign_code' => strtoupper($message)]);
                if ($campaign) {
                    $user = User::findByUsername($userId);
                    if (!$user) {
                        $user = User::newUser($userId, CUtils::generateRandomNumber(), User::TYPE_DONOR, $userId, $userId, '', $userId . "@vivas.vn");
                    }
                    $existDonate = Transaction::find()->andWhere(['campaign_id' => $campaign->id])->andWhere(['user_id' => $user->id])->one();
                    $incrementUserDonate = true;
                    if ($existDonate) {
                        $incrementUserDonate = false;
                    }

                    $mt_msg = self::replaceParamMT(self::MT_DONATED, ['msisdn', 'campaign_id', 'password'],
                        [$user->getName(), $campaign->campaign_code, $user->password_reset_token]);

                    Transaction::newTransaction($user, $campaign, 15000, Transaction::STATUS_SUCCESS,
                        Transaction::PAYMENT_TYPE_SMS, Transaction::TYPE_MONEY, $serviceNumber, '');

                    $campaign->donate(15000, $incrementUserDonate);

                    $message = self::CONTENT_TYPE_TEXT . ";" . self::MESSAGE_TYPE_VALID . ";" . $mt_msg;
                    \Yii::info($message);
                    echo $message;
                } else {
                    \Yii::error('Khong tim thay campaign');
                }
            } else if (strtolower($keyword) == "nu") {
                /** @var Campaign $campaign */
                $campaign = Campaign::findOne(['campaign_code' => strtoupper($message)]);
                if ($campaign) {
                    $mt_msg = self::replaceParamMT(self::MT_INFO, ['campaign_id', 'current_amount', 'expected_amount', 'expired_at'],
                        [$campaign->campaign_code, CommonUtils::formatNumber($campaign->current_amount),
                            CommonUtils::formatNumber($campaign->expected_amount), date("d/m/Y", $campaign->finished_at)]);
                    $message = self::CONTENT_TYPE_TEXT . ";" . self::MESSAGE_TYPE_VALID . ";" . $mt_msg;
                    \Yii::info($message);
                    echo $message;
                } else {
                    \Yii::error('Khong tim thay campaign');
                }
            } else {
                \Yii::error('Khong tim thay campaign');
            }
        } else {
            \Yii::error('Sai ten dang nhap hoac mat khau');
        }
    }

    public static function replaceParamMT($message, $params, $values)
    {
        if (is_array($params)) {
            $cnt = count($params);
            for ($i = 0; $i < $cnt; $i++) {
                $message = str_replace('{' . $params[$i] . '}', $values[$i], $message);
            }
        }
        return $message;
    }

    /**
     * @param $phone
     * @param $transaction Transaction
     */
    public function actionSendSms($phone){
        Brandname::send('Test MT',$phone);
    }

}