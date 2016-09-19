<?php

namespace frontend\controllers;

use common\models\Campaign;
use common\models\CampaignDonationItemAsm;
use common\models\DonationItem;
use common\models\DonationRequest;
use common\models\News;
use common\models\Village;
use Yii;
use yii\data\Pagination;
use yii\db\mssql\PDO;
use yii\web\NotFoundHttpException;

/**
 * DonationRequestController implements the CRUD actions for DonationRequest model.
 */
class NewsController extends BaseController
{


    /**
     * Displays a single DonationRequest model.
     * @param integer $id
     * @return mixed
     */
    public function actionIndex()
    {
        $listNews = News::find()->andWhere(['status' => News::STATUS_ACTIVE])
            ->orderBy(['created_at' => SORT_DESC]);
        $countQuery = clone $listNews;
        $pages = new Pagination(['totalCount' => $countQuery->count()]);
        $pageSize = Yii::$app->params['page_size'];
        $pages->setPageSize($pageSize);
        $models = $listNews->offset($pages->offset)
            ->limit(10)->all();

        $listCampaign = Campaign::find()
//            ->andWhere('finished_at <= :finished_at',['finished_at'=>time()])
            ->innerJoin('village','village.id = campaign.village_id')
            ->andWhere(['village.status'=>Village::STATUS_ACTIVE])
            ->andWhere(['campaign.status' => Campaign::STATUS_ACTIVE])
            ->andWhere('campaign.donation_request_id  is null or campaign.donation_request_id  = 0')
            ->orderBy(['campaign.updated_at' => SORT_ASC])->limit(10)->all();
        $listCampaignDonor = null;
        $i = 0;
        foreach ($listCampaign as $item) {
            $listCampaignDonor[$i] = new \stdClass();
            $listCampaignDonor[$i]->name = $item->name;
            $listCampaignDonor[$i]->id = $item->id;
            $listCampaignDonor[$i]->thumbnail = $item->getCampaignImage();
            $lead_donor = $this->getVillage($item->village_id);
            /** @var $lead_donor LeadDonor */
            $listCampaignDonor[$i]->status =  $this->getDonationItem($item->id);

            $listCampaignDonor[$i]->village_name = $lead_donor->name;
            $i++;
        }

        $listDonation = null;
        $i = 0;

        return $this->render('index', ['listNews' => $models, 'pages' => $pages, 'listCampaign' => $listCampaignDonor]);
    }

    public function getDonationItem($campaign_id)
    {

        $donation_item = DonationItem::find()
            ->innerJoin('campaign_donation_item_asm', 'campaign_donation_item_asm.donation_item_id = donation_item.id')
            ->andWhere(['campaign_donation_item_asm.campaign_id' => $campaign_id])->all();
        $rateDonation = 0;
        $i = 0;
        foreach ($donation_item as $item) {
            $listDonation[$i] = new \stdClass();
            $listDonation[$i]->expected_number = $this->getCampaignId($item->id,$campaign_id);
            $listDonation[$i]->number_donation = $this->getDonationMoney($item->id, $campaign_id);
            if($listDonation[$i]->expected_number > 0) {
                if($listDonation[$i]->number_donation > $listDonation[$i]->expected_number){
                    $number_ = $listDonation[$i]->expected_number;
                    $rateDonation += $number_ / $listDonation[$i]->expected_number;
                }else{
                    $rateDonation += $listDonation[$i]->number_donation / $listDonation[$i]->expected_number;
                }

            }else{
                $rateDonation = 0;
            }
            $i++;
        }
        $rateDonation = $i>0?round(($rateDonation *100)/$i,2):0;
        return $rateDonation;
    }

    public function getCampaignId($donation_id,$campaign_id){
        $campaign = CampaignDonationItemAsm::findOne(['donation_item_id'=>$donation_id,'campaign_id'=>$campaign_id]);
        return $campaign->expected_number;
    }

    public function getDonationMoney($donation_id, $campaign_id)
    {

        $sql = "select sum(a.donation_number) as money from transaction_donation_item_asm a inner join transaction t on t.id =  a.transaction_id where t.campaign_id = :campaignId and a.donation_item_id = :donation_id";
        $command = Yii::$app->db->createCommand($sql);
        $command->bindValue(":campaignId", "$campaign_id", PDO::PARAM_INT);
        $command->bindValue(":donation_id", "$donation_id", PDO::PARAM_INT);
        $dataReader = $command->query();
        $listUser = null;
        $i = 0;
        foreach ($dataReader as $item) {
            return $item['money'];
        }

    }


    public function getVillage($id)
    {
        return Village::findOne(['id' => $id]);
    }

    public function actionDetail($id)
    {

        $model = $this->findModel($id);
        $otherModels = News::find()->andWhere(['status'=>News::STATUS_ACTIVE])
            ->andWhere(['type'=>$model->type])
            ->andWhere('id <> :id',[':id'=>$model->id])
            ->orderBy(['created_at' => SORT_DESC])->limit(6)->all();

        $listCampaign = Campaign::find()
            ->innerJoin('village','village.id = campaign.village_id')
            ->andWhere(['village.status'=>Village::STATUS_ACTIVE])
//            ->andWhere('finished_at <= :finished_at',['finished_at'=>time()])
            ->andWhere(['campaign.status' => Campaign::STATUS_ACTIVE])
            ->andWhere('campaign.donation_request_id  is null or campaign.donation_request_id  = 0')
            ->orderBy(['campaign.created_at' => SORT_DESC])->limit(10)->all();
        $listCampaignDonor = null;
        $i = 0;
        foreach ($listCampaign as $item) {
            $listCampaignDonor[$i] = new \stdClass();
            $listCampaignDonor[$i]->name = $item->name;
            $listCampaignDonor[$i]->id = $item->id;
            $listCampaignDonor[$i]->thumbnail = $item->getCampaignImage();
            $lead_donor = $this->getVillage($item->village_id);
            /** @var $lead_donor LeadDonor */
            $listCampaignDonor[$i]->status =  $this->getDonationItem($item->id);
            $listCampaignDonor[$i]->village_name = $lead_donor->name;
            $i++;
        }

        return $this->render('detail', ['model' => $model,
            'otherModels' => $otherModels, 'listCampaign' => $listCampaignDonor]);
    }

    public function actionView($id)
    {
        /** @var News $model */
        $model = $this->findModel($id);
        $model->view_count++;
        $model->save(false);
        $otherModels = News::find()->andWhere(['campaign_id' => $model->campaign_id])->andFilterWhere(['!=', 'id', $model->id])->limit(5)->all();
        return $this->render('view', [
            'model' => $model,
            'otherModels' => $otherModels
        ]);
    }


    /**
     * Finds the DonationRequest model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DonationRequest the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = News::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('Nội dung không tồn tại.');
        }
    }
}
