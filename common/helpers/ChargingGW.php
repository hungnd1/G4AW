<?php
/**
 * Created by PhpStorm.
 * User: Thuc
 * Date: 3/9/2015
 * Time: 5:43 PM
 */

namespace common\helpers;


use common\models\Service;

class ChargingGW {
    const ACTION_REGISTER = 1;
    const ACTION_CANCEL = 2;
    const ACTION_RENEW = 3;
    const ACTION_PRESENT = 4; // tang goi cuoc
    const ACTION_CONTENT = 5; // mua noi dung le

    const MPS_OK = 0;
    const MPS_MSISDN_NOT_FOUND = 1;
    const MPS_INVALID_CLIENT_IP = 4; // IP ko nam trong dai pool 3G
    const MPS_MISSING_PARAMS = 11;
    const MPS_MISSING_AMOUNT = 13;
    const MPS_MISSING_CP_REQUEST_ID = 14;
    const MPS_MISSING_VALUE = 15;
    const MPS_MISSING_AES_KEY = 16;
    const MPS_MISSING_NAME_ITEM = 17;
    const MPS_MISSING_CATEGORY_ITEM = 18;
    const MPS_INVALID_CP_CODE = 22;
    const MPS_INVALID_PAYMENT = 23;
    const MPS_MISSING_CONFIRM_TRANSACTION = 24;
    const MPS_INVALID_CP_REQUEST_ID = 25;

    const MPS_SYSTEM_ERROR = 503;

    const MPS_BUSINESS_LOGIC_ERROR = 101;
    const MPS_REGISTER_ERROR = 102;
    const MPS_MOBILE_BALANCE_CHARGING_ERROR = 103;
    const MPS_CANCEL_ERROR = 104;

    const MPS_INVALID_SIGNATURE = 201;
    const MPS_CHARGING_ERROR = 202;

    const MPS_INVALID_PASSWORD = 202;
    const MPS_INVALID_ACCOUNT = 203;
    const MPS_ACCOUNT = 203;


    const MPS_INSUFFICIENT_BALANCE = 401;
    const MPS_SUBSCRIBER_PAYMENT_NOT_REGISTER = 402;
    const MPS_SUBSCRIBER_NOT_EXISTED = 403;
    const MPS_INVALID_MSISDN = 404;
    const MPS_MSISDN_OWNER_CHANGED = 405;
    const MPS_MSISDN_DATA_NOT_FOUND = 406;
    const MPS_PARAMS_NOT_DECLARED = 407;
    const MPS_SERVICE_ALREADY_REGISTERED = 408;
    const MPS_MSISDN_TWO_WAY_BLOCKED = 409;
    const MPS_NOT_VIETTEL_MSISDN = 410;
    const MPS_SERVICE_ALREADY_CANCELED = 411;
    const MPS_SUBSCRIBER_NOT_USING_SERVICE = 412;
    const MPS_INVALID_PRICE_PARAM = 413;
    const MPS_STILL_IN_CHARGE_CYCLE = 414;
    const MPS_INVALID_OTP = 415;
    const MPS_EXPIRED_OTP = 416;
    const MPS_SYSTEM_ERROR1 = 440;

    const MPS_MSISDN_NOT_REGISTERED = 501;


    /**
     * @param int $action
     * @param string $msisdn
     * @param Service $service
     * @param int $cost
     * @return int
     */
    public static function charge($action, $msisdn, $service, $cost = -1) {
        $rand = Misc::rand();
        if ($rand < 0.6) { // 50% thanh cong
            $error = static::MPS_OK;
        }
        else if ($rand < 0.95) { // 35% thieu tien voi giao dich mat tien, thanh cong voi giao dich huy
            if ($action == static::ACTION_CANCEL) {
                $error = static::MPS_OK;
            } else {
                $error = static::MPS_INSUFFICIENT_BALANCE;
            }
        }
        else if ($rand <0.98) { //3% loi mat dong bo
            if ($action == static::ACTION_REGISTER) {
                $error = static::MPS_SERVICE_ALREADY_REGISTERED;
            }
            else if ($action == static::ACTION_CANCEL) {
                $error = static::MPS_SERVICE_ALREADY_CANCELED;
            }
            else if ($action == static::ACTION_RENEW) {
                $error = static::MPS_STILL_IN_CHARGE_CYCLE;
            }
            else {
                $error = static::MPS_MSISDN_TWO_WAY_BLOCKED;
            }
        }
        else { // 2% loi he thong
            $error = static::MPS_SYSTEM_ERROR;
        }
        return $error;
    }
}