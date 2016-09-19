<?php

namespace frontend\controllers;

use api\models\News;
use common\components\OwnerFilter;
use common\models\Bank;
use common\models\Campaign;
use common\models\CampaignBankAsm;
use common\models\CampaignDonationItemAsm;
use common\models\DonationItem;
use common\models\DonationRequest;
use common\models\LeadDonor;
use common\models\Transaction;
use common\models\TransactionDonationItemAsm;
use common\models\User;
use common\models\Village;
use Yii;
use yii\base\Exception;
use yii\data\Pagination;
use yii\db\mssql\PDO;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

/**
 * CampaignController implements the CRUD actions for Campaign model.
 */
class CampaignController extends BaseController
{
    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            [
                'class' => OwnerFilter::className(),
                'user' => $this->user,
                'field_owner_id' => 'created_by',
                'model_relation_owner' => function ($action, $params) {
                    return Campaign::findOne($params['id']);
                },
                'only' => ['update', 'delete', 'start']
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
                'only' => ['create', 'update', 'delete', 'start'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['create', 'update', 'delete', 'start'],
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
    public function actionView($id)
    {
        $model = $this->findModel($id);

        $village = Village::findOne(['id' => $model->village_id]);

        $news_relate = News::find()->andWhere(['campaign_id' => $model->id])
            ->andWhere(['status' => News::STATUS_ACTIVE])
            ->orderBy(['published_at'=>SORT_DESC,'updated_at'=>SORT_DESC,'created_at'=>SORT_DESC])
            ->limit(6)->all();

        $donation_item = DonationItem::find()
            ->select('donation_item.name,donation_item.unit,campaign_donation_item_asm.expected_number,campaign_donation_item_asm.current_number')
            ->innerJoin('campaign_donation_item_asm', 'campaign_donation_item_asm.donation_item_id = donation_item.id')
            ->andWhere(['campaign_donation_item_asm.campaign_id' => $model->id])
            ->andWhere(['donation_item.status' => DonationItem::STATUS_ACTIVE])
            ->all();
        $lead = LeadDonor::find()->andWhere(['id'=>$model->lead_donor_id])->one();

        $listCampaign = null;
        $i=0;
        $campaign_related = Campaign::find()
            ->innerJoin('village','village.id = campaign.village_id')
            ->andWhere(['village.status'=>Village::STATUS_ACTIVE])
            ->andWhere(['campaign.village_id' => $model->village_id])
            ->andWhere(['campaign.lead_donor_id' => $model->lead_donor_id])
            ->andWhere('campaign.id <> :id', ['id' => $model->id])
            ->andWhere(['campaign.status'=>Campaign::STATUS_ACTIVE])
            ->orderBy(['published_at'=>SORT_DESC,'created_at'=>SORT_DESC])
            ->limit(3)->all();
        foreach ($campaign_related as $item) {
            $listCampaign[$i]= new \stdClass();
            $listCampaign[$i]->id = $item->id;
            $listCampaign[$i]->image =  $item->getCampaignImage();
            $listCampaign[$i]->name =  $item->name;
            $listCampaign[$i]->rateDonation =  $this->getDonationItem($item->id);
            $i++;
        }


        $listCampaign_village = null;
        $i=0;
        $campaign_related_village = Campaign::find()
            ->innerJoin('village','village.id = campaign.village_id')
            ->andWhere(['village.status'=>Village::STATUS_ACTIVE])
            ->andWhere('campaign.village_id <> :_id',['_id' => $model->village_id])
            ->andWhere('campaign.id <> :id', ['id' => $model->id])
            ->andWhere(['campaign.status'=>Campaign::STATUS_ACTIVE])
            ->orderBy(['published_at'=>SORT_DESC,'created_at'=>SORT_DESC])
            ->limit(3)->all();
        foreach ($campaign_related_village as $item) {
            $listCampaign_village[$i]= new \stdClass();
            $listCampaign_village[$i]->id = $item->id;
            $listCampaign_village[$i]->image =  $item->getCampaignImage();
            $listCampaign_village[$i]->name =  $item->name;
            $listCampaign_village[$i]->rateDonation =  $this->getDonationItem($item->id);
            $i++;
        }

        $listDonation = null;
        $i = 0;
        $donation_item = DonationItem::find()
            ->innerJoin('campaign_donation_item_asm','campaign_donation_item_asm.donation_item_id = donation_item.id')
            ->andWhere(['campaign_donation_item_asm.campaign_id'=>$model->id])->all();

        $rateDonation = 0;
        foreach($donation_item as $item){
            $listDonation[$i] = new \stdClass();
            $listDonation[$i]->name_donation = $item->name;
            $listDonation[$i]->unit = $item->unit;
            $listDonation[$i]->expected_number = $this->getCampaignId($item->id,$model->id) ? $this->getCampaignId($item->id,$model->id): 0;
            $listDonation[$i]->user = $this->getMoney($item->id,$model->id);
            $listDonation[$i]->number_donation =  $this->getDonationMoney($item->id,$model->id) ? $this->getDonationMoney($item->id,$model->id) : 0;
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

        return $this->render('view', ['model' => $model, 'village' => $village,
            'newsRelated' => $news_relate, 'donationItem' => $listDonation,
            'listCampaign_village'=>$listCampaign_village,
            'campaign_related' => $listCampaign,'rateDonation'=>$rateDonation,
            'lead'=>$lead]);

    }

    public function getMoneyDonate($campaign_id){

        $money = 0;
        $campaign = CampaignDonationItemAsm::findAll(['campaign_id'=>$campaign_id]);
        foreach($campaign as $item){
            if($item->expected_number > 0){
                $money += $item->current_number/$item->expected_number * 100;
            }else{
                $money = 0;
            }
        }
        return $money;
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

    public function actionDonation($id,$name)
    {
        $model = $this->findModel($id);
        $village = Village::find()->andWhere(['id' => $model->village_id])
        ->andWhere(['status' => Village::STATUS_ACTIVE])->one();
        $listDonation = null;
        $i = 0;
        $donation_item = DonationItem::find()
            ->innerJoin('campaign_donation_item_asm','campaign_donation_item_asm.donation_item_id = donation_item.id')
            ->andWhere(['campaign_donation_item_asm.campaign_id'=>$model->id])->all();

        $rateDonation = 0;
        foreach($donation_item as $item){
            $listDonation[$i] = new \stdClass();
            $listDonation[$i]->id = $item->id;
            $listDonation[$i]->name_donation = $item->name;
            $listDonation[$i]->unit = $item->unit;
            $listDonation[$i]->expected_number = $this->getCampaignId($item->id,$model->id);
            $listDonation[$i]->user = $this->getMoney($item->id,$model->id);
            $listDonation[$i]->number_donation =  $this->getDonationMoney($item->id,$model->id);
            $listDonation[$i]->number_least = $this->getDonationMoney($item->id,$model->id);
            if($listDonation[$i]->number_donation >= $listDonation[$i]->expected_number){
                $listDonation[$i]->number_donation = $listDonation[$i]->expected_number;
                $listDonation[$i]->number_least = $this->getDonationMoney($item->id,$model->id);
            }
            if($listDonation[$i]->expected_number > 0) {
                $rateDonation += $listDonation[$i]->number_donation / $listDonation[$i]->expected_number;
            }else{
                $rateDonation = 0;
            }
            $i++;
        }
        $rateDonation = $i>0?round($rateDonation *100/$i,2):0;
        return $this->render('donation', ['model' => $model, 'village' => $village,
            'donationItem' => $donation_item,'listDonation'=>$listDonation,'rateDonation'=>$rateDonation,
            'name'=>$name
        ]);
    }

    public function getMoney($donation_id,$campaign_id){

        $sql = "select a.donation_number,t.user_id,t.username,t.address from transaction_donation_item_asm a inner join transaction t on t.id =  a.transaction_id where t.campaign_id = :campaignId and a.donation_item_id = :donation_id";
        $command = Yii::$app->db->createCommand($sql);
        $command->bindValue(":campaignId","$campaign_id",PDO::PARAM_INT);
        $command->bindValue(":donation_id","$donation_id",PDO::PARAM_INT);
        $dataReader = $command->query();
        $listUser = null;
        $i=0;
        foreach($dataReader as $item){
            $listUser[$i] = new \stdClass();
            if($item['user_id']){
                $listUser[$i]->username = $this->getUser($item['user_id'])->fullname;
                $listUser[$i]->avatar = $this->getUser($item['user_id'])->getAvatar();
                $listUser[$i]->address = $item['address'];
            }else{
                $listUser[$i]->username = $item['username'];
                $listUser[$i]->avatar = '../img/avt_df.png';
                $listUser[$i]->address = $item['address'];
            }
            $listUser[$i]->donation_number = $item['donation_number'];
            $i++;
        }
        return $listUser;

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

    public function getUser($user_id){
        return User::findOne(['id'=>$user_id]);
    }



    /**
     * Creates a new Campaign model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($request_id = null)
    {
        /** @var User $user */
        $user = User::findOne(Yii::$app->user->id);
        if (!$user || $user->type != User::TYPE_ORGANIZATION) {
            throw  new ForbiddenHttpException('Bạn không có quyền thực hiện chức năng này');
        }

        $model = new Campaign();
        $requestModel = null;
        if ($request_id !== null) {
            /** @var DonationRequest $requestModel */
            $requestModel = DonationRequest::findOne($request_id);
            if (!$requestModel) {
                throw  new Exception('Không tìm thấy yêu cầu hỗ trợ');
            }
            // assign default value
            $model->donation_request_id = $request_id;
            $model->created_for_user = $requestModel->created_by;
            $model->short_description = $requestModel->short_description;
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
                if ($requestModel) {
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
            'requestModel' => $requestModel
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
        if ($model->load(Yii::$app->request->post())) {
            $thumbnail = UploadedFile::getInstance($model, 'thumbnail');
            if ($thumbnail) {
                $file_name = Yii::$app->user->id . time() . '.' . $thumbnail->extension;
                if ($thumbnail->saveAs(Yii::getAlias('@webroot') . "/" . Yii::$app->params['upload_images'] . "/" . $file_name)) {
                    $model->thumbnail = $file_name;
                }
            } else {
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
        $model->categoryAsms = $model->loadCategoryAsm();
        Yii::info($model->categoryAsms);
        return $this->render('update', [
            'model' => $model,
            'requestModel' => $model->donationRequest
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
        $model = $this->findModel($id);
        $model->status = Campaign::STATUS_DELETED;
        if ($model->save()) {
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
        if (($model = Campaign::find()->andWhere(['id'=>$id])->one()) !== null) {
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

    public function actionIndex($categories = null, $partners = null, $sort = 'newest')
    {
        $campaignsQuery = Campaign::find()->andWhere(['status' => Campaign::STATUS_ACTIVE])->findByCategory($categories)->findByPartner($partners)->sortBy($sort);
        $countQuery = clone $campaignsQuery;
        $pages = new Pagination(['totalCount' => $countQuery->count()]);
        $pageSize = Yii::$app->params['page_size'];
        $pages->setPageSize($pageSize);
        $campaigns = $campaignsQuery->offset($pages->offset)
            ->limit($pages->limit)->all();
        return $this->render('index',
            [
                'categories' => $categories,
                'partners' => $partners,
                'campaigns' => $campaigns,
                'pages' => $pages,
                'sort' => $sort
            ]);
    }

    public function actionSupport($id){

        $campaign = Campaign::findOne(['id'=>$id]);
        /** @var  $campaign Campaign */
        $village = Village::findOne(['id'=>$campaign->village_id]);
        /** @var  $village Village */
        $bank = CampaignBankAsm::find()
            ->andWhere(['campaign_id'=>$id])
            ->all();

        return $this->render('support',['village'=>$village,'bank'=>$bank]);
    }

    public function actionDonateTransaction($campaign_id)
    {
        $model = $this->findModel($campaign_id);
        $query = Transaction::find()->andWhere(['campaign_id' => $campaign_id]);
        $countQuery = clone $query;
        $pages = new Pagination(['totalCount' => $countQuery->count()]);
        $pageSize = Yii::$app->params['page_size'];
        $pages->setPageSize($pageSize);
        $transactions = $query->orderBy('created_at desc')->offset($pages->offset)
            ->limit($pages->limit)->all();
        return $this->render('donate-transaction',
            [
                'model' => $model,
                'transactions' => $transactions,
                'pages' => $pages,

            ]);
    }

    public function actionStart($id)
    {
        /** @var Campaign $campaign */
        $campaign = Campaign::findOne($id);
        if (!$campaign) {
            throw  new NotFoundHttpException('Chiến dịch không tồn tại. vui lòng thử lại sau');
        }
        if ($campaign->start()) {
            Yii::$app->session->addFlash('success', 'Khởi động chiến dịch thành công');
        } else {
            Yii::$app->session->addFlash('error', 'Khởi động chiến dịch không thành công. vui lòng thử lại sau');
        }
        return $this->redirect(['/campaign/view', 'id' => $id]);
    }

    public function actionListCampaign(){
        $newsQuery = Campaign::find()
            ->innerJoin('village','village.id = campaign.village_id')
            ->andWhere(['campaign.status'=>Campaign::STATUS_ACTIVE])
            ->andWhere(['village.status'=>Village::STATUS_ACTIVE])
            ->andWhere(['campaign.donation_request_id'=>null])
            ->orderBy('campaign.created_at desc');
        $countQuery = clone $newsQuery;
        $pages = new Pagination(['totalCount' => $countQuery->count()]);
        $pageSize = Yii::$app->params['page_size'];
        $pages->setPageSize($pageSize);
        $model = $newsQuery->offset($pages->offset)
            ->limit(8)->all();
        $listCampaignDonor = null;
        $i=0;
        $rateDonation = 0;
        foreach($model as $item){
            $listCampaignDonor[$i] = new \stdClass();
            $listCampaignDonor[$i]->name = $item->name;
            $listCampaignDonor[$i]->id = $item->id;
            $listCampaignDonor[$i]->thumbnail = $item->getCampaignImage();
            $village = $this->getVillage($item->village_id);
            /** @var $village Village */
            $listCampaignDonor[$i]->village_name = $village->name;
            $listCampaignDonor[$i]->village_id = $item->village_id;
            /** @var $lead_donor LeadDonor */
            $list_donor = $this->getLeadDonor($item->lead_donor_id);
            $listCampaignDonor[$i]->lead_id = $item->lead_donor_id;
            $listCampaignDonor[$i]->lead_name = $list_donor->name;
            $listCampaignDonor[$i]->address = LeadDonor::_substr($list_donor->address,25);
            $listCampaignDonor[$i]->image = $list_donor->getImageLink();
            $listCampaignDonor[$i]->status =  $this->getDonationItem($item->id);
            $i++;
        }
        return $this->render('list-campaign',[
            'listCampaignDonor'=>$listCampaignDonor,
            'pages'=> $pages
        ]);
    }
    public function getVillage($id){
        return Village::findOne(['id'=>$id]);
    }
    public function getLeadDonor($id){
        return LeadDonor::findOne(['id'=>$id]);
    }

}
