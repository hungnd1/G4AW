<?php

namespace common\models;

use common\helpers\CommonUtils;
use DateTime;
use Exception;
use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "transaction".
 *
 * @property integer $id
 * @property integer $campaign_id
 * @property integer $user_id
 * @property string $username
 * @property integer $payment_type
 * @property integer $type
 * @property double $amount
 * @property double $donation_number
 * @property integer $transaction_time
 * @property integer $donation_item_id
 * @property integer $status
 * @property integer $telco
 * @property string $scratch_card_code
 * @property string $scratch_card_serial
 * @property string $shortcode
 * @property string $sms_mesage
 * @property string $bank_transaction_id
 * @property string $bank_transaction_detail
 * @property string $description
 * @property string $error_code
 * @property string $address
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property Campaign $campaign
 * @property DonationItem $donationItem
 * @property User $user
 */
class Transaction extends \yii\db\ActiveRecord
{
    const STATUS_SUCCESS = 10;
    const STATUS_FAIL = 0;

    const TYPE_DIRECT = 1;
    const TYPE_CARD = 2;
    const TYPE_SMS = 3;
    const TYPE_INTERNET_BANKING = 4;

    const PAYMENT_TYPE_CARD = 1; //The cao
    const PAYMENT_TYPE_SMS = 2; // sms
    const PAYMENT_TYPE_INTERNET_BANKING = 3; //internet banking
    const PAYMENT_TYPE_MONEY = 4; // chuyen khoan


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'transaction';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
            [
                'class' => TimestampBehavior::className(),
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['campaign_id', 'status'], 'required'],
            [['campaign_id', 'user_id', 'payment_type', 'type', 'donation_item_id',
                'transaction_time', 'status', 'telco', 'created_at', 'updated_at'], 'integer'],
            [['amount', 'donation_number'], 'number'],
            [['bank_transaction_detail'], 'string'],
            [['username'], 'string', 'max' => 64],
            [['scratch_card_code', 'scratch_card_serial', 'shortcode', 'sms_mesage', 'bank_transaction_id'], 'string', 'max' => 45],
            [['description', 'address'], 'string', 'max' => 200],
            [['error_code'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'campaign_id' => Yii::t('app', 'Campaign ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'username' => Yii::t('app', 'Cá nhân/Đơn vị ủng hộ'),
            'payment_type' => Yii::t('app', 'Payment Type'),
            'type' => Yii::t('app', 'Type'),
            'amount' => Yii::t('app', 'Amount'),
            'transaction_time' => Yii::t('app', 'Transaction Time'),
            'status' => Yii::t('app', 'Status'),
            'telco' => Yii::t('app', 'Telco'),
            'scratch_card_code' => Yii::t('app', 'Mã thẻ'),
            'scratch_card_serial' => Yii::t('app', 'Serial'),
            'shortcode' => Yii::t('app', 'Shortcode'),
            'sms_mesage' => Yii::t('app', 'Sms Mesage'),
            'bank_transaction_id' => Yii::t('app', 'Bank Transaction ID'),
            'bank_transaction_detail' => Yii::t('app', 'Bank Transaction Detail'),
            'description' => Yii::t('app', 'Description'),
            'address' => Yii::t('app', 'Địa chỉ'),
            'error_code' => Yii::t('app', 'Error Code'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCampaign()
    {
        return $this->hasOne(Campaign::className(), ['id' => 'campaign_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDonationItem()
    {
        return $this->hasOne(DonationItem::className(), ['id' => 'donation_item_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public static function listTelcoType()
    {
        return [
            '2' => 'Thẻ Vinaphone',
            '3' => 'Thẻ mobifone'
        ];
    }

    /**
     * @return array
     */
    public static function listStatus()
    {
        $lst = [
            self::STATUS_SUCCESS => 'Thành công',
            self::STATUS_FAIL => 'Thất bại',
        ];
        return $lst;
    }

    /**
     * @return int
     */
    public function getStatusName()
    {
        $lst = self::listStatus();
        if (array_key_exists($this->status, $lst)) {
            return $lst[$this->status];
        }
        return $this->status;
    }

    /**
     * @return array
     */
    public static function listType()
    {
        $lst = [
            self::TYPE_ITEM => 'Hiện vật',
            self::TYPE_MONEY => 'Tiền mặt',
        ];
        return $lst;
    }

    /**
     * @return int
     */
    public function getTypeName()
    {
        $lst = self::listType();
        if (array_key_exists($this->type, $lst)) {
            return $lst[$this->type];
        }
        return $this->type;
    }

    /**
     * @return array
     */
    public static function listPaymentType()
    {
        $lst = [
            self::PAYMENT_TYPE_CARD => 'Thẻ cào',
            self::PAYMENT_TYPE_SMS => 'SMS',
            self::PAYMENT_TYPE_INTERNET_BANKING => 'Internet banking',
            self::PAYMENT_TYPE_MONEY => 'Chuyển khoản',
        ];
        return $lst;
    }

    /**
     * @return int
     */
    public function getPaymentTypeName()
    {
        $lst = self::listPaymentType();
        if (array_key_exists($this->payment_type, $lst)) {
            return $lst[$this->payment_type];
        }
        return $this->payment_type;
    }

    public static function genReportDonation($start_day = '')
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {

            if ($start_day != '') {
                $to_day = strtotime(DateTime::createFromFormat("dmY", $start_day)->setTime(0, 0)->format('Y-m-d H:i:s'));
                $end_day = strtotime(DateTime::createFromFormat("dmY", $start_day)->setTime(23, 59, 59)->format('Y-m-d H:i:s'));
                $to_day_date = DateTime::createFromFormat("dmY", $start_day)->setTime(0, 0)->format('Y-m-d H:i:s');
            } else {
                $to_day = strtotime("midnight", time());
                $end_day = strtotime("tomorrow", $to_day) - 1;
                $to_day_date = (new DateTime('now'))->setTime(0, 0)->format('Y-m-d H:i:s');
            }

            Yii::$app->db->createCommand()->delete('report_donation', ['report_date' => $to_day_date])->execute();

            /** @var User[] $organizations */
            $organizations = User::find()->andWhere(['type' => User::TYPE_ORGANIZATION])->all();
            foreach ($organizations as $organization) {
                /** @var Campaign[] $campaigns */
                $campaigns = Campaign::find()->andWhere(['created_by' => $organization->id])->all();
                foreach ($campaigns as $campaign) {
                    $revenues = Transaction::find()
                        ->andWhere('transaction_time >= :start')->addParams([':start' => $to_day])
                        ->andWhere('transaction_time <= :end')->addParams([':end' => $end_day])
                        ->andWhere(['status' => Transaction::STATUS_SUCCESS])
                        ->andWhere(['campaign_id' => $campaign->id])
                        ->sum('amount');

                    $donation_count = Transaction::find()
                        ->andWhere('transaction_time >= :start')->addParams([':start' => $to_day])
                        ->andWhere('transaction_time <= :end')->addParams([':end' => $end_day])
                        ->andWhere(['status' => Transaction::STATUS_SUCCESS])
                        ->andWhere(['campaign_id' => $campaign->id])
                        ->count();

                    $report = new ReportDonation();
                    $report->report_date = $to_day_date;
                    $report->campaign_id = $campaign->id;
                    $report->organization_id = $organization->id;
                    $report->donate_count = $donation_count;
                    $report->revenues = $revenues ? $revenues : 0;
                    $report->save();

//                    echo "OG: $organization->user_code, CP: $campaign->campaign_code, Revenues: $revenues, Donate count: $donation_count \n";
                }
            }

            $transaction->commit();
//            print "Done \n";

        } catch (Exception $e) {
            $transaction->rollBack();
//            print "Error";
            print $e;
        }
    }

    public function preSaveInit()
    {
        $this->status = self::STATUS_SUCCESS;
        $this->type = self::TYPE_MONEY;

    }

    public function saveTransaction()
    {
        $this->preSaveInit();
        $existDonate = Transaction::find()->andWhere(['campaign_id' => $this->campaign_id])->andWhere(['user_id' => $this->user_id])->one();
        if ($this->save()) {

            $incrementUserDonate = true;
            if ($existDonate) {
                $incrementUserDonate = false;
            }
            $this->campaign->donate($this->amount, $incrementUserDonate);
            return true;
        }
        Yii::warning($this->getErrors());
        return false;
    }

    public function getCurrency()
    {
        return ' VNĐ';
    }

    /**
     * @param $user User
     * @param $campaign_id
     * @param $address
     * @param $name
     * @param int $transaction_time
     * @param int $status
     * @param int $type
     * @param string $short_code
     * @param string $sms_message
     * @return Transaction|null
     */
    public static function newTransaction($user, $name, $campaign_id, $transaction_time,$address,
                                          $status = self::STATUS_SUCCESS,
                                          $type = self::TYPE_DIRECT,
                                          $short_code = '',
                                          $sms_message = '')
    {
        $trans = new Transaction();
        $trans->campaign_id = $campaign_id;
        if ($user) {
            $trans->user_id = $user->id;
        }
        $trans->username = $name;
        $trans->type = $type;
        $trans->status = $status;
        $trans->address = $address;
        $trans->shortcode = $short_code;
        $trans->sms_mesage = $sms_message;
        $trans->transaction_time = $transaction_time;
        if ($trans->save()) {
            return $trans;
        } else {
            Yii::error($trans->getErrors());
        }
        return null;
    }


    /**
     * @param $donation_item DonationItem
     * @return string
     */
    public static function getFormDonationItem($donation_item)
    {
        $rs = '';
        $rs .= '<div class="form-group field-transaction-form-donation_items-1 has-success">';
        $rs .= '<label class="control-label col-md-2" for="transaction-form-donation_items-1">' . $donation_item->getFullname() . '</label>';
        $rs .= '<div class="col-md-10">';
        $rs .= '<input type="text" id="transaction-form-donation_items-' . $donation_item->id . '" class="form-control" name="TransactionForm[donation_items][' . $donation_item->id . ']" value="0">';
        $rs .= '<div class="col-md-offset-2 col-md-10"></div>';
        $rs .= '<div class="col-md-offset-2 col-md-10"><div class="help-block"></div></div>';
        $rs .= '</div>';
        $rs .= '</div>';
        return $rs;
    }

    public function getTransactionDetail()
    {
        $rs = '';

        /** @var TransactionDonationItemAsm[] $details */
        $details = TransactionDonationItemAsm::find()->andWhere(['transaction_id' => $this->id])->all();
        foreach ($details as $detail) {
            $rs .= $detail->donationItem ?
                "<b>" . $detail->donationItem->name . "</b>" . ": " . CommonUtils::formatNumber($detail->donation_number) . " " . $detail->donationItem->unit . " <br>" : "";
        }

        return $rs;
    }
}
