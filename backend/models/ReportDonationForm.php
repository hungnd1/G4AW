<?php
namespace backend\models;

use common\models\ReportCampaign;
use common\models\ReportDonation;
use common\models\ReportRevenues;
use common\models\User;
use DateTime;
use Yii;
use yii\base\Model;
use yii\db\Transaction;

/**
 * Login form
 */
class ReportDonationForm extends Model
{
    public $to_date;
    public $from_date;
    public $dataProvider;
    public $content = null;
    public $campaign_id = 0;
    public $organization_id = 0;

    public function rules()
    {
        return [
            [['from_date', 'to_date', 'content', 'organization_id', 'campaign_id'], 'safe']
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

    public function generateReport()
    {
        if ($this->from_date == '' || $this->to_date == '') {
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

        \common\models\Transaction::genReportDonation();

        $report_daily = ReportDonation::find()
            ->select('report_date, sum(revenues) as revenues, sum(donate_count) as donate_count')
            ->andwhere('report_date >= :p_from_date', [':p_from_date' => $from_date])
            ->andWhere('report_date <= :p_to_date', [':p_to_date' => $to_date])
            ->groupBy('report_date');

        if ($this->organization_id > 0) {
            $report_daily->andWhere(['organization_id' => $this->organization_id]);
        }

        if ($this->campaign_id > 0) {
            $report_daily->andWhere(['campaign_id' => $this->campaign_id]);
        }

        $this->content = $report_daily;
    }
}
