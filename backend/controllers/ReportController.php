<?php
namespace backend\controllers;

use backend\models\ReportDonationForm;
use common\auth\filters\Yii2Auth;
use common\models\Campaign;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\Controller;

class ReportController extends Controller
{
    /**
     * @inheritdoc
     */

    public function behaviors()
    {
        return [
            'auth' => [
                'class' => Yii2Auth::className(),
                'autoAllow' => false,
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionDonation()
    {
        $param = Yii::$app->request->queryParams;
        $from_date = isset($param['ReportDonationForm']['from_date']) ? $param['ReportDonationForm']['from_date'] : null;
        $to_date = isset($param['ReportDonationForm']['to_date']) ? $param['ReportDonationForm']['to_date'] : null;
        $organization_id = isset($param['ReportDonationForm']['organization_id']) ? $param['ReportDonationForm']['organization_id'] : null;
        $campaign_id = isset($param['ReportDonationForm']['campaign_id']) ? $param['ReportDonationForm']['campaign_id'] : null;

        $report = new ReportDonationForm();
        $report->from_date = $from_date;
        $report->to_date = $to_date;
        $report->organization_id = $organization_id;
        $report->campaign_id = $campaign_id;
        $report->content = null;

        $report->generateReport();

        $report->dataProvider = new ActiveDataProvider([
            'query' => $report->content,
            'pagination' => [
                'pageSize' => 20,
            ],
            'sort' => [
                'defaultOrder' => [
                    'report_date' => SORT_DESC
                ]
            ]
        ]);
        return $this->render('donation', [
            'report' => $report,
        ]);
    }

    public function actionListCampaign()
    {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents != null) {
                $created_by = $parents[0];
                $out = Campaign::getCampaignByCreatedBy($created_by);

                echo Json::encode(['output' => $out, 'selected' => '']);
                return;
            }
        }
        echo Json::encode(['output' => $out, 'selected' => '']);
    }
}
