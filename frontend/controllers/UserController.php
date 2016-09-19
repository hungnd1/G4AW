<?php
namespace frontend\controllers;

use common\helpers\Brandname;
use common\models\Campaign;
use common\models\CampaignDonationItemAsm;
use common\models\DonationItem;
use common\models\DonationRequest;
use common\models\LeadDonor;
use common\models\LoginForm;
use common\models\User;
use DateTime;
use frontend\models\Muser;
use yii\db\mssql\PDO;
use frontend\models\SignupForm;
use kartik\alert\Alert;
use Yii;

use yii\bootstrap\ActiveForm;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\UploadedFile;


/**
 * Site controller
 */
class UserController extends Controller
{
    public function actionPartnerRegister()
    {
        $model = new SignupForm();
        $model->type = User::TYPE_ORGANIZATION;
        if ($model->load(Yii::$app->request->post())) {
            $model->type = User::TYPE_ORGANIZATION;

            if ($user = $model->signup()) {
                Brandname::sendRegisterSms($user);
                $user->status = User::STATUS_WAITING;
                if ($user->save(false) && Yii::$app->getUser()->login($user)) {
                    return $this->goHome();
                }
            }
        }

        return $this->render('partner-register', ['model' => $model]);
    }

    public function actionDonorRegister()
    {
        $model = new SignupForm();
        $model->type = User::TYPE_DONOR;
        if ($model->load(Yii::$app->request->post())) {
            $model->type = User::TYPE_DONOR;
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    return $this->goHome();
                }
            }
        }

        return $this->render('donor-register', ['model' => $model]);
    }

    public function actionDoneeRegister()
    {
        $model = new SignupForm();
        $model->type = User::TYPE_DONEE;
        if ($model->load(Yii::$app->request->post())) {
            $model->type = User::TYPE_DONEE;
            if ($user = $model->signup()) {
                Brandname::sendRegisterSms($user);
                if (Yii::$app->getUser()->login($user)) {
                    return $this->goHome();
                }
            }
        }

        return $this->render('donee-register', ['model' => $model]);
    }

    public function actionSetting($active = 1)
    {
        $id = Yii::$app->user->id;
        /** @var User $model */
        $model = User::findOne($id);

        if (!$model) {
            throw  new BadRequestHttpException('Người dùng không tồn tại');
        }
        $oldAvatar = $model->avatar;
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post())) {
            $avatar = UploadedFile::getInstance($model, 'avatar');
            if ($avatar) {
                $file_name = Yii::$app->user->id . time() . '.' . $avatar->extension;
                if ($avatar->saveAs(Yii::getAlias('@webroot') . "/" . Yii::$app->params['avatar'] . "/" . $file_name)) {
                    $model->avatar = $file_name;
                }
            } else {
                $model->avatar = $oldAvatar;
            }
            if ($model->save()) {
                Yii::$app->getSession()->setFlash('success', Yii::t('app', 'Lưu thay đổi thành công'));
            }
        }
        return $this->render('setting', ['model' => $model, 'active' => $active]);
    }
    public function actionChangeMyPassword(){
        $id = Yii::$app->user->identity->id;
        /** @var User $model */
        $model = User::findOne($id);
        if (!$model) {
            throw  new BadRequestHttpException('Người dùng không tồn tại');
        }

        $model->setScenario('user-setting');

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
//            echo "<pre>";
//            print_r($model);
//            die();
            $model->setPassword($model->setting_new_password);
            $model->password_reset_token = $model->setting_new_password;
            if ($model->save(false)) {
                Yii::$app->getSession()->setFlash('success', Yii::t('app', 'Đổi mật khẩu thành công'));
                return $this->redirect(['my-page','id'=>$id]);
            } else {
                Yii::warning($model->getErrors());
                Yii::$app->getSession()->setFlash('danger', Yii::t('app', 'Đổi mật khẩu không thành công'));
                return $this->redirect(['my-page','id'=>$id]);
            }
        }
        return $this->render('change-my-password',[
            'model'=>$model,
        ]);
    }

    public function actionChangePassword()
    {
        $id = Yii::$app->user->id;
        /** @var User $model */
        $model = User::findOne($id);
        if (!$model) {
            throw  new BadRequestHttpException('Người dùng không tồn tại');
        }
        $model->setScenario('user-setting');
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;

            return ActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->setPassword($model->setting_new_password);
            $model->password_reset_token = $model->setting_new_password;
            if ($model->save(false)) {
                Yii::$app->getSession()->setFlash('success', Yii::t('app', 'Đổi mật khẩu thành công'));
            } else {

                Yii::warning($model->getErrors());
            }
        }
        return $this->redirect(['setting', 'active' => 2]);
    }

    public function actionIndex($active = 1)
    {
        $view = 'index';
        /** @var User $model */
        $model = $this->findModel(Yii::$app->user->id);
        if ($model->type == User::TYPE_ORGANIZATION) {
            // create post
            $view = 'index/partner_index';
        } elseif ($model->type == User::TYPE_DONOR) {
            $view = 'index/donor_index';
        } else {
            $view = 'index/donee_index';
        }
        return $this->render($view, ['model' => $model, 'active' => $active]);
    }

    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('Nội dung không tồn tại.');
        }
    }

    public function actionDetail($id,$active = 1)
    {
        $currentUser=null;
        if(!Yii::$app->user->isGuest){
            $currentUser = $this->findModel(Yii::$app->user->id);
        }

        /** @var User $model */
        $model = $this->findModel($id);

        $view = 'partner_view';

        return $this->render($view, ['model' => $model,'currentModel'=>$currentUser, 'active' => $active]);
    }

    // add by TuanPham 20160810

    public function actionMyPage($id){
        $modelCam = Campaign::find()
            ->innerJoin('donation_request','donation_request.id = campaign.donation_request_id')
            ->andWhere(['donation_request.created_by'=>$id])
            ->andWhere("campaign.status != :status")->addParams([':status'=>campaign::STATUS_DELETED])
            ->andWhere("campaign.status != :status1")->addParams([':status1'=>campaign::STATUS_NEW])
            ->orderBy('campaign.published_at desc')->all();
        $modelDonation = DonationRequest::find()
            ->andWhere(['created_by'=>$id])
            ->andWhere("status != :status")->addParams([':status'=>DonationRequest::STATUS_DELETED])
            ->orderBy('created_at desc')->all();
        $i = 0 ;
        $cam = null;
        foreach($modelCam as $item){
            $cam[$i] = new \stdClass();
            $cam[$i]->image = $item->getCampaignThumbnail();
            $cam[$i]->name = $item->name;
            $cam[$i]->id = $item->id;
            $cam[$i]->short_description = $item->short_description;
            $lead = LeadDonor::findOne($item->lead_donor_id);
            /** @var LeadDonor $lead */
            $cam[$i]->imagelead = $lead->getImageLink();
            $cam[$i]->leadname = $lead->name;
            $cam[$i]->leadid = $lead->id;
            $cam[$i]->leadaddress = $lead->address;
            $cam[$i]->status = $this->getDonationItem($item->id);
            $i++;
        }
//        echo "<pre>";
//        print_r($modelDonation);
//        die();
        $model = User::findOne(['id'=>$id]);
        return $this->render('my-page',[
            'model'=>$model,
//            'modelCam'=>$modelCam,
            'modelDonation'=>$modelDonation,
            'cam'=>$cam
        ]);
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

    public function actionUpdate($id){
        $model = Muser::findOne(['id'=>$id, 'status' => User::STATUS_ACTIVE]);

        if(!$model){
            throw  new BadRequestHttpException('Người dùng không tồn tại');
        }
//        $model->setScenario('update');

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        $avatar_old = $model->avatar;

        $model->birthday = $model->birthday?date('d/m/Y',strtotime($model->birthday)):'';

        if ($model->load(Yii::$app->request->post())) {
            $avatar  = UploadedFile::getInstance($model, 'avatar');
            if ($avatar) {
                $avatar_name = Yii::$app->user->id . '.' . uniqid() . time() . '.' . $avatar->extension;
                if ($avatar->saveAs(Yii::getAlias('@webroot') . "/" . Yii::getAlias('@avatar') . "/" . $avatar_name)) {
                    $model->avatar = $avatar_name;
                    $model->birthday = $model->birthday?date('Y-m-d H:i:s',strtotime(DateTime::createFromFormat("d/m/Y", $model->birthday)->setTime(0,0)->format('Y-m-d H:i:s'))):'';

                    if ($model->save()) {
                        Yii::$app->session->setFlash('success', 'Cập nhật thành công thông tin người dùng!');
                        return $this->redirect(['my-page', 'id' => $model->id]);
                    } else {
                        Yii::error($model->getErrors());
                        Yii::$app->getSession()->setFlash('error', 'Lỗi hệ thống vui lòng thử lại');
                    }
                } else {
                    Yii::$app->getSession()->setFlash('error', 'Lỗi hệ thống, vui lòng thử lại');
                }
            }else {
                $model->avatar = $avatar_old;
                $model->birthday = $model->birthday?date('Y-m-d H:i:s',strtotime(DateTime::createFromFormat("d/m/Y", $model->birthday)->setTime(0,0)->format('Y-m-d H:i:s'))):'';
                $model->save();
                Yii::$app->getSession()->setFlash('success', 'Cập nhật thành công thông tin người dùng');
                return $this->redirect(['my-page', 'id' => $model->id]);
            }
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }
}
