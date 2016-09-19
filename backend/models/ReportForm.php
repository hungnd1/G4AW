<?php
namespace backend\models;

use common\models\MarketingCampaign;
use common\models\ReportCampaign;
use common\models\Transaction;
use common\models\User;
use DateTime;
use Yii;
use yii\base\Model;

/**
 * Login form
 */
class ReportForm extends Model
{
    public $to_date;
    public $from_date;
    public $dataProvider;
    public $content = null;
    public $campaign_id = null;
    public $organization_id = null;

    public function rules()
    {
        return [
            [['from_date', 'to_date', 'content', 'campaign_id', 'organization_id'], 'safe']
        ];
    }

    public function attributeLabels()
    {
        return [
            'to_date' => 'Ngày kết thúc',
            'from_date' => 'Ngày bắt đầu',
            'campaign_id' => 'Chiến dịch',
            'organization_id' => 'Tổ chức cầu nối',
        ];
    }


    /**
     * @param bool $first
     * @param $type
     * @param User $user
     */
    public function generateReport($type, $first = false, $user = null)
    {
        if ($first) {
            $to_date = (new DateTime('now'))->setTime(0, 0)->format('Y-m-d H:i:s');
            $from_date = (new DateTime('now'))->setTime(0, 0)->modify('-7 days')->format('Y-m-d H:i:s');
        } else {
            if ($this->to_date != '' && DateTime::createFromFormat("d/m/Y", $this->to_date)) {
                $to_date = DateTime::createFromFormat("d/m/Y", $this->to_date)->setTime(0, 0)->format('Y/m/d H:i:s');
            } else {
                $to_date = (new DateTime('now'))->setTime(0, 0)->format('Y-m-d H:i:s');
            }
            if ($this->from_date != '' && DateTime::createFromFormat("d/m/Y", $this->from_date)) {
                $from_date = DateTime::createFromFormat("d/m/Y", $this->from_date)->setTime(0, 0)->format('Y-m-d H:i:s');
            } else {
                $from_date = (new DateTime('now'))->setTime(0, 0)->format('Y-m-d H:i:s');
            }
        }

        $report_daily = Transaction::find()
            ->select('report_date, sum(num_click) as num_click, sum(num_click_by_ip) as num_click_by_ip,
                            sum(num_click_identity) as num_click_identity, sum(num_click_identity_by_ip) as num_click_identity_by_ip,
                            sum(num_register) as num_register, sum(num_register_free) as num_register_free,
                            sum(subscriber_register) as subscriber_register, sum(admin_cancel) as admin_cancel,
                            sum(num_renew) as num_renew, sum(view_purchase) as view_purchase,
                            sum(view_free) as view_free, sum(total_revenues) as total_revenues,
                            sum(user_cancel) as user_cancel, sum(total_mo_register) as total_mo_register,
                            sum(active_subscriber) as active_subscriber, sum(keeping_subscriber) as keeping_subscriber,
                            sum(number_cancel) as number_cancel, sum(cancel_in_free_time) as cancel_in_free_time,
                            sum(cancel_out_free_time) as cancel_out_free_time, sum(number_register_not_free) as number_register_not_free,
                            sum(subscriber_psc) as subscriber_psc, sum(subscriber_psc_success) as subscriber_psc_success,
                            sum(retry_register_revenues) as retry_register_revenues, sum(renew_revenues) as renew_revenues,
                            sum(retry_revenues) as retry_revenues, sum(redirect_gw) as redirect_gw,
                            sum(return_gw) as return_gw, sum(is_direct_subscriber) as is_direct_subscriber,
                            sum(is_direct_register) as is_direct_register')
            ->andwhere('report_date >= :p_from_date', [':p_from_date' => $from_date])
            ->andWhere('report_date <= :p_to_date', [':p_to_date' => $to_date])
            ->andWhere(['type' => $type])
            ->groupBy('report_date');

        if($user){
            if($user->subscriber_provider_id){
                $report_daily->andWhere(['subscriber_provider_id' => $user->subscriber_provider_id]);
            }
        }

        $this->content = $report_daily;

    }


}
