<?php

namespace frontend\controllers;

use common\components\OwnerFilter;
use common\models\Campaign;
use common\models\CampaignDonationItemAsm;
use common\models\Comment;
use common\models\DonationItem;
use common\models\DonationRequest;
use common\models\LeadDonor;
use common\models\News;
use common\models\NewsVillageAsm;
use common\models\User;
use PDO;
use Yii;
use common\models\Village;
use yii\base\Exception;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\helpers\Json;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * CampaignController implements the CRUD actions for Campaign model.
 */
class VillageController extends BaseController
{
    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            [
                'class' => OwnerFilter::className(),
                'user' => $this->user,
                'field_owner_id' => 'created_by',
                'only' => ['update', 'delete','start']
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                    'start' => ['post'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['create', 'update', 'delete','start'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['create', 'update', 'delete','start'],
                        'roles' => ['@'],
                    ],
                ],
            ],
        ]);
    }


    /**
     * Displays a single Campaign model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id_village)
    {
        $model =$this->findModel($id_village);
        $listCampaignDonor = null;
        $listCampaiasiKind = null;
        $listComment = null;

        $query = Comment::find()
            ->andWhere(['village_id'=>$id_village])
            ->orderBy(['updated_at'=>SORT_DESC]);
        $countQuery = clone  $query;
        $pages = new Pagination(['totalCount'=>$countQuery->count()]);
        $pageSize = Yii::$app->params['page_size'];
        $pages->setPageSize($pageSize);
        $comment = $query->offset($pages->offset)->limit(10)->all();
        $j =0 ;
        foreach($comment as $item ){
            $listComment[$j] = new \stdClass();
            $listComment[$j]->content  = $item->content;
            $listComment[$j]->user = User::findOne(['id'=>$item->user_id]);
            $listComment[$j]->updated_at = $item->updated_at;
            $j++;
        }

        $listCampaign = Campaign::find()
            ->innerJoin('village','village.id = campaign.village_id')
            ->andWhere(['village.status'=>Village::STATUS_ACTIVE])
            ->andWhere(['campaign.status'=>Campaign::STATUS_ACTIVE])
            ->andWhere(['campaign.village_id'=>$model->id])
            ->andWhere('campaign.donation_request_id  is null or campaign.donation_request_id  = 0')
            ->orderBy(['campaign.published_at'=>SORT_DESC,'campaign.updated_at'=>SORT_DESC])->all();

        $i=0;
        foreach($listCampaign as $item){
            $listCampaignDonor[$i] = new \stdClass();
            $listCampaignDonor[$i]->name = $item->name;
            $listCampaignDonor[$i]->id = $item->id;
            $listCampaignDonor[$i]->thumbnail = $item->getCampaignThumbnail();
            $listCampaignDonor[$i]->expected_amount = $item->expected_amount;
            $listCampaignDonor[$i]->current_amount = $item->current_amount;
            $lead_donor = $this->getLeadDonor($item->lead_donor_id);
            /** @var $lead_donor LeadDonor */
            $listCampaignDonor[$i]->image = $lead_donor->getImageLink();
            $listCampaignDonor[$i]->id_lead_donor = $lead_donor->id;
            $listCampaignDonor[$i]->lead_donor_name = $lead_donor->name;
            $listCampaignDonor[$i]->lead_donor_add = $lead_donor->address;
            $listCampaignDonor[$i]->status =  $this->getDonationItem($item->id);

            $i++;
        }

        $listCampaignKind = Campaign::find()
            ->innerJoin('village','village.id = campaign.village_id')
            ->andWhere(['village.status'=>Village::STATUS_ACTIVE])
            ->andWhere(['campaign.status'=>Campaign::STATUS_ACTIVE])
            ->andWhere(['campaign.village_id'=>$model->id])
            ->andWhere('campaign.donation_request_id  > 0')
            ->orderBy(['campaign.published_at'=>SORT_DESC,'campaign.updated_at'=>SORT_DESC])->all();

        $i=0;
        foreach($listCampaignKind as $item){
            $listCampaiasiKind[$i] = new \stdClass();
            $listCampaiasiKind[$i]->name = $item->name;
            $listCampaiasiKind[$i]->id = $item->id;
            $listCampaiasiKind[$i]->thumbnail = $item->getCampaignThumbnail();
            $listCampaiasiKind[$i]->expected_amount = $item->expected_amount;
            $listCampaiasiKind[$i]->current_amount = $item->current_amount;
            $lead_donor = $this->getLeadDonor($item->lead_donor_id);
            /** @var $lead_donor LeadDonor */
            $listCampaiasiKind[$i]->image = $lead_donor->getImageLink();
            $listCampaiasiKind[$i]->lead_donor_name = $lead_donor->name;
            $listCampaiasiKind[$i]->id_lead_donor = $lead_donor->id;
            $listCampaiasiKind[$i]->lead_donor_add = $lead_donor->address;
            $listCampaiasiKind[$i]->status =  $this->getDonationItem($item->id);

            $listCampaiasiKind[$i]->short_description = $item->short_description;
            $i++;
        }
        $leadDonor = LeadDonor::findOne(['id'=>$model->lead_donor_id]);

        $new_idea = News::find()
            ->innerJoin('news_village_asm','news_village_asm.news_id = news.id')
            ->innerJoin('village','news_village_asm.village_id = village.id')
            ->andWhere(['news.status'=>News::STATUS_ACTIVE])
            ->andWhere(['news.type'=>News::TYPE_IDEA])
            ->andWhere(['village.id'=>$model->id])
            ->orderBy(['news.published_at'=>SORT_DESC,'news.updated_at'=>SORT_DESC])->all();
        $new_trade = News::find()
            ->innerJoin('news_village_asm','news_village_asm.news_id = news.id')
            ->innerJoin('village','news_village_asm.village_id = village.id')
            ->andWhere(['news.status'=>News::STATUS_ACTIVE])
            ->andWhere(['news.type'=>News::TYPE_TRADE])
            ->andWhere(['village.id'=>$model->id])
            ->orderBy(['news.published_at'=>SORT_DESC,'news.updated_at'=>SORT_DESC])->all();
        return $this->render('view', [
            'model' => $model,
            'listCampaignDonor'=>$listCampaignDonor,
            'leadDonor'=>$leadDonor,
            'newIdea'=>$new_idea,
            'newTrade'=>$new_trade,
            'listCampaignKind'=>$listCampaiasiKind,
            'listComment'=>$listComment,
            'pages'=>$pages
        ]);
    }

    public function getLeadDonor($id){
        return LeadDonor::findOne(['id'=>$id]);
    }

    public function getDonationMoney($donation_id,$campaign_id){

        $sql = "select sum(a.donation_number) as money from transaction_donation_item_asm a inner join transaction t on t.id =  a.transaction_id where t.campaign_id = :campaignId and a.donation_item_id = :donation_id";
        $command = Yii::$app->db->createCommand($sql);
        $command->bindValue(":campaignId","$campaign_id",PDO::PARAM_INT);
        $command->bindValue(":donation_id","$donation_id",PDO::PARAM_INT);
        $dataReader = $command->query();
        $listUser = null;
        $i=0;
        foreach($dataReader as $item){
            return $item['money'];
        }

    }

    public function getCampaignId($donation_id,$campaign_id){
        $campaign = CampaignDonationItemAsm::findOne(['donation_item_id'=>$donation_id,'campaign_id'=>$campaign_id]);
        return $campaign->expected_number;
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

    /**
     * Creates a new Campaign model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($request_id=null )
    {
        /** @var User $user */
        $user = User::findOne(Yii::$app->user->id);
        if(!$user || $user->type != User::TYPE_ORGANIZATION){
            throw  new ForbiddenHttpException('Bạn không có quyền thực hiện chức năng này');
        }

        $model = new Campaign();
        $requestModel=null;
        if($request_id !== null){
            /** @var DonationRequest $requestModel */
            $requestModel = DonationRequest::findOne($request_id);
            if(!$requestModel){
                throw  new Exception('Không tìm thấy yêu cầu hỗ trợ');
            }
            // assign default value
            $model->donation_request_id = $request_id;
            $model->created_for_user = $requestModel->created_by;
            $model->short_description= $requestModel->short_description;
            $model->content = $requestModel->content;
            $model->expected_amount = $requestModel->expected_amount;
            $model->imageAsms = $requestModel->loadImageAsm();
        }



        if ($model->load(Yii::$app->request->post())) {
            $thumbnail = UploadedFile::getInstance($model, 'thumbnail');
            if ($thumbnail) {
                $file_name = Yii::$app->user->id . time() . '.' . $thumbnail->extension;
                if ($thumbnail->saveAs(Yii::getAlias('@webroot') . "/" . Yii::$app->params['upload_images'] . "/" . $file_name)) {
                    $model->thumbnail = $file_name;
                }
            }
            $model->started_at = strtotime(date('d-m-Y', strtotime($model->started_at)));
            $model->finished_at = strtotime(date('d-m-Y', strtotime($model->finished_at)));
            if ($model->save()) {
                $model->assignImages();
                $model->assignCategories();
                if($requestModel){
                    $requestModel->status = DonationRequest::STATUS_ACTIVE;
                    $requestModel->save();
                }

                Yii::$app->session->addFlash('success', 'Tạo chiến dịch thành công');
                return $this->redirect(['/user/index']);
            } else {
                Yii::$app->session->addFlash('error', 'Tạo chiến dịch không thành công. vui lòng thử lại sau');
            }


        }
        Yii::error($model->getErrors());
        return $this->render('create', [
            'model' => $model,
            'requestModel'=>$requestModel
        ]);
    }

    /**
     * Updates an existing Campaign model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->started_at = date('d-m-Y', $model->started_at);
        $model->finished_at = date('d-m-Y', $model->finished_at);
        $oldThumbnail = $model->thumbnail;
        if ($model->load(Yii::$app->request->post())  ) {
            $thumbnail = UploadedFile::getInstance($model, 'thumbnail');
            if ($thumbnail) {
                $file_name = Yii::$app->user->id . time() . '.' . $thumbnail->extension;
                if ($thumbnail->saveAs(Yii::getAlias('@webroot') . "/" . Yii::$app->params['upload_images'] . "/" . $file_name)) {
                    $model->thumbnail = $file_name;
                }
            }else {
                $model->thumbnail = $oldThumbnail;
            }
            $model->started_at = strtotime(date('d-m-Y', strtotime($model->started_at)));
            $model->finished_at = strtotime(date('d-m-Y', strtotime($model->finished_at)));
            if ($model->save()) {
                $model->assignImages();
                $model->assignCategories();
                Yii::$app->session->addFlash('success', 'Cập nhật chiến dịch thành công');
            } else {
                Yii::$app->session->addFlash('error', 'Cập nhật chiến dịch không thành công. vui lòng thử lại sau');
            }

        }

        $model->imageAsms = $model->loadImageAsm();
        $model->categoryAsms=$model->loadCategoryAsm();
        Yii::info($model->categoryAsms);
        return $this->render('update', [
            'model' => $model,
            'requestModel'=>$model->donationRequest
        ]);

    }

    /**
     * Deletes an existing Campaign model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model=$this->findModel($id);
        $model->status = Campaign::STATUS_DELETED;
        if($model->save()){
            Yii::$app->session->addFlash('success', 'Xóa chiến dịch thành công');
        } else {
            Yii::$app->session->addFlash('error', 'Xóa chiến dịch không thành công. vui lòng thử lại sau');
        }
        return $this->redirect(['/user/index']);
    }

    /**
     * Finds the Campaign model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Campaign the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Village::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('Nội dung không tồn tại.');
        }
    }

    public function actionGetRequestTo()
    {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents != null) {
                $userId = $parents[0];
                $out = DonationRequest::getRequestFrom($userId);

                echo Json::encode(['output' => $out, 'selected' => '']);
                return;
            }
        }
        echo Json::encode(['output' => '', 'selected' => '']);
    }
    public function actionIndex($categories=null,$partners=null,$sort='newest'){
        $campaignsQuery = Campaign::find()->andWhere(['status'=>Campaign::STATUS_ACTIVE])->findByCategory($categories)->findByPartner($partners)->sortBy($sort);
        $countQuery = clone $campaignsQuery;
        $pages = new Pagination(['totalCount' => $countQuery->count()]);
        $pageSize = Yii::$app->params['page_size'];
        $pages->setPageSize($pageSize);
        $campaigns = $campaignsQuery->offset($pages->offset)
            ->limit($pages->limit)->all();
        return $this->render('index',
            [
                'categories' =>$categories,
                'partners' =>$partners,
                'campaigns'=>$campaigns,
                'pages' =>$pages,
                'sort'=>$sort
            ]);
    }
    public function actionDonateTransaction($campaign_id){
        $model = $this->findModel($campaign_id);
        $query =  Transaction::find()->andWhere(['campaign_id'=>$campaign_id]);
        $countQuery = clone $query;
        $pages = new Pagination(['totalCount' => $countQuery->count()]);
        $pageSize = Yii::$app->params['page_size'];
        $pages->setPageSize($pageSize);
        $transactions = $query->orderBy('created_at desc')->offset($pages->offset)
            ->limit($pages->limit)->all();
        return $this->render('donate-transaction',
            [
                'model' =>$model,
                'transactions' =>$transactions,
                'pages'=>$pages,

            ]);
    }
    public function actionStart($id){
        /** @var Campaign $campaign */
        $campaign= Campaign::findOne($id);
        if(!$campaign){
            throw  new NotFoundHttpException('Chiến dịch không tồn tại. vui lòng thử lại sau');
        }
        if($campaign->start())
        {
            Yii::$app->session->addFlash('success', 'Khởi động chiến dịch thành công');
        } else {
            Yii::$app->session->addFlash('error', 'Khởi động chiến dịch không thành công. vui lòng thử lại sau');
        }
        return $this->redirect(['/campaign/view','id'=>$id]);
    }
}
