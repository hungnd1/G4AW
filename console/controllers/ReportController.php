<?php

/**
 * Swiss army knife to work with user and rbac in command line
 * @author: Nguyen Chi Thuc
 * @email: gthuc.nguyen@gmail.com
 */
namespace console\controllers;

use common\models\Campaign;
use common\models\ReportDonation;
use common\models\Transaction;
use common\models\User;
use DateTime;
use Exception;
use Yii;
use yii\console\Controller;

/**
 * UserController create user in commandline
 */
class ReportController extends Controller
{
    public function actionReportDonation($start_day = '')
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            print("Start report donation \n");

            if ($start_day != '') {
                $to_day = strtotime(DateTime::createFromFormat("dmY", $start_day)->setTime(0, 0)->format('Y-m-d H:i:s'));
                $end_day = strtotime(DateTime::createFromFormat("dmY", $start_day)->setTime(23, 59, 59)->format('Y-m-d H:i:s'));
                $to_day_date = DateTime::createFromFormat("dmY", $start_day)->setTime(0, 0)->format('Y-m-d H:i:s');
            } else {
                $to_day = strtotime("midnight", time());
                $end_day = strtotime("tomorrow", $to_day) - 1;
                $to_day_date = (new DateTime('now'))->setTime(0, 0)->format('Y-m-d H:i:s');
            }

            print("Thoi gian bat dau: $to_day : Thoi gian ket thuc: $end_day ");
            print("Convert sang ngay: $to_day_date \n");

            Yii::$app->db->createCommand()->delete('report_donation', ['report_date' => $to_day_date])->execute();

            /** @var User[] $organizations */
            $organizations = User::find()->andWhere(['type' => User::TYPE_ORGANIZATION])->all();
            foreach ($organizations as $organization) {
                /** @var Campaign[] $campaigns */
                $campaigns = Campaign::find()->andWhere(['created_by'=>$organization->id])->all();
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

                    echo "OG: $organization->user_code, CP: $campaign->campaign_code, Revenues: $revenues, Donate count: $donation_count \n";
                }
            }

            $transaction->commit();
            print "Done \n";

        } catch (Exception $e) {
            $transaction->rollBack();
            print "Error";
            print $e;
        }
    }
    public function actionReportDonationFinal($start_day = '')
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            print("Start report donation \n");

            if ($start_day != '') {
                $to_day = strtotime(DateTime::createFromFormat("dmY", $start_day)->setTime(0, 0)->format('Y-m-d H:i:s'));
                $end_day = strtotime(DateTime::createFromFormat("dmY", $start_day)->setTime(23, 59, 59)->format('Y-m-d H:i:s'));
                $to_day_date = DateTime::createFromFormat("dmY", $start_day)->setTime(0, 0)->format('Y-m-d H:i:s');
            } else {
                $to_day = strtotime("midnight", time());
                $end_day = strtotime("tomorrow", $to_day) - 1 - 86400;
                $to_day = $to_day - 86400;
                $to_day_date = (new DateTime('now'))->modify('-1 day')->setTime(0, 0)->format('Y-m-d H:i:s');
            }

            print("Thoi gian bat dau: $to_day : Thoi gian ket thuc: $end_day ");
            print("Convert sang ngay: $to_day_date \n");

            Yii::$app->db->createCommand()->delete('report_donation', ['report_date' => $to_day_date])->execute();

            /** @var User[] $organizations */
            $organizations = User::find()->andWhere(['type' => User::TYPE_ORGANIZATION])->all();
            foreach ($organizations as $organization) {
                /** @var Campaign[] $campaigns */
                $campaigns = Campaign::find()->andWhere(['created_by'=>$organization->id])->all();
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

                    echo "OG: $organization->user_code, CP: $campaign->campaign_code, Revenues: $revenues, Donate count: $donation_count \n";
                }
            }

            $transaction->commit();
            print "Done \n";

        } catch (Exception $e) {
            $transaction->rollBack();
            print "Error";
            print $e;
        }
    }
}
