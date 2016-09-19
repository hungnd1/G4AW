<?php

namespace backend\controllers;

use backend\models\TransactionForm;
use common\auth\filters\NewsAuth;
use common\auth\filters\Yii2Auth;
use common\models\Campaign;
use common\models\CampaignBankAsm;
use common\models\CampaignDonationItemAsm;
use common\models\CampaignSearch;
use common\models\DonationRequest;
use common\models\Transaction;
use common\models\TransactionDonationItemAsm;
use common\models\User;
use DateTime;
use Exception;
use kartik\form\ActiveForm;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\UploadedFile;

/**
 * CampaignController implements the CRUD actions for Campaign model.
 */
class CampaignController extends Controller
{
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

    /**
     * Lists all Campaign models.
     * @return mixed
     */
    public function actionIndex()
    {

        if (isset($_POST['hasEditable'])) {
            // read your posted model attributes
            $post = Yii::$app->request->post();
            if ($post['editableKey']) {
                // read or convert your posted information
                /** @var Campaign $campaign */
                $campaign = Campaign::findOne($post['editableKey']);
                $index = $post['editableIndex'];
                if ($campaign) {
                    $campaign->load($post['Campaign'][$index], '');
                    if ($campaign->update()) {
                        echo \yii\helpers\Json::encode(['output' => '', 'message' => '']);
                    } else {
                        Yii::error($campaign->getErrors());
                        echo \yii\helpers\Json::encode(['output' => '', 'message' => 'Dữ liệu không hợp lệ']);
                    }
                } else {
                    echo \yii\helpers\Json::encode(['output' => '', 'message' => 'Campaign không tồn tại']);
                }

            } // else if nothing to do always return an empty JSON encoded output
            else {
                echo \yii\helpers\Json::encode(['output' => '', 'message' => '']);
            }
            return;
        }

        $searchModel = new CampaignSearch();

        $params = Yii::$app->request->queryParams;

        /** @var User $user */
        $user = Yii::$app->user->identity;
        if ($user->type == User::TYPE_LEAD_DONOR && $user->lead_donor_id) {
            $params['CampaignSearch']['lead_donor_id'] = $user->lead_donor_id;
        }
        if ($user->type == User::TYPE_VILLAGE && $user->village_id) {
            $params['CampaignSearch']['village_id'] = $user->village_id;
        }

        $dataProvider = $searchModel->search($params);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Campaign model.
     * @param integer $id
     * @param integer $active
     * @return mixed
     */
    public function actionView($id, $active = 1)
    {
        $model = $this->findModel($id);

        if (isset($_POST['hasEditable'])) {
            // read your posted model attributes
            $post = Yii::$app->request->post();
            if ($post['editableKey']) {
                // read or convert your posted information
                /** @var CampaignDonationItemAsm $campaign_item_asm */
                $campaign_item_asm = CampaignDonationItemAsm::findOne($post['editableKey']);
                $index = $post['editableIndex'];
                if ($campaign_item_asm) {
                    $campaign_item_asm->load($post['CampaignDonationItemAsm'][$index], '');
                    if ($campaign_item_asm->update()) {

                        echo \yii\helpers\Json::encode(['output' => '', 'message' => '']);
                    } else {

                        echo \yii\helpers\Json::encode(['output' => '', 'message' => 'Dữ liệu không hợp lệ']);
                    }
                } else {
                    echo \yii\helpers\Json::encode(['output' => '', 'message' => 'Nội dung không tồn tại']);
                }

            } // else if nothing to do always return an empty JSON encoded output
            else {
                echo \yii\helpers\Json::encode(['output' => '', 'message' => '']);
            }
            return;
        }

        $param = Yii::$app->request->queryParams;

        $start_date = isset($param['Campaign']['start_date']) ? $param['Campaign']['start_date'] : null;
        $end_date = isset($param['Campaign']['end_date']) ? $param['Campaign']['end_date'] : null;

        $transactions = $model->getTransaction($start_date, $end_date);

        $query_donation_item = CampaignDonationItemAsm::find()->andWhere(['campaign_id' => $id]);

        $query_campaign_bank_direct = CampaignBankAsm::find()->andWhere(['campaign_id' => $id])
            ->andWhere(['type' => CampaignBankAsm::TYPE_DIRECT_ADDRESS]);

        $query_campaign_bank_account = CampaignBankAsm::find()->andWhere(['campaign_id' => $id])
            ->andWhere(['type' => CampaignBankAsm::TYPE_BANK_ACCOUNT]);

        $donation_item = new ActiveDataProvider([
            'query' => $query_donation_item,
            'sort' => ['defaultOrder' =>
                ['created_at' => SORT_DESC]
            ]
        ]);

        $campaign_bank_account = new ActiveDataProvider([
            'query' => $query_campaign_bank_account,
            'sort' => ['defaultOrder' =>
                ['created_at' => SORT_DESC]
            ]
        ]);

        $campaign_direct_address = new ActiveDataProvider([
            'query' => $query_campaign_bank_direct,
            'sort' => ['defaultOrder' =>
                ['created_at' => SORT_DESC]
            ]
        ]);

        return $this->render('view', [
            'model' => $model,
            'active' => $active,
            'donation_item' => $donation_item,
            'transactions' => $transactions,
            'campaign_direct_address' => $campaign_direct_address,
            'campaign_bank_account' => $campaign_bank_account,
        ]);
    }

    /**
     * Creates a new Campaign model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($donation_request_id = 0)
    {
        $model = new Campaign();

        /** @var DonationRequest $donation_request */
        $donation_request = DonationRequest::findOne($donation_request_id);
        if ($donation_request) {
            $model->village_id = $donation_request->village_id;
            $model->donation_request_id = $donation_request->id;
        }
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        $model->setScenario('create');
        $param = Yii::$app->request->post();
        if ($model->load($param)) {

            $started_at = 0;
            $finished_at = 0;
            if ($model->start_date) {
                $started_at = strtotime(DateTime::createFromFormat("d-m-Y H:i:s", $model->start_date)->format('Y-m-d H:i:s'));;
            }

            if ($model->end_date) {
                $finished_at = strtotime(DateTime::createFromFormat("d-m-Y H:i:s", $model->end_date)->format('Y-m-d H:i:s'));;
            }

            if ($finished_at < $started_at) {
                Yii::$app->getSession()->setFlash('error', 'Ngày kết thúc không được nhỏ hơn ngày bắt đầu');
                return $this->render('create', [
                    'model' => $model,
                    'donation_request' => $donation_request
                ]);
            }

            //Xử lí phần logo
            $thumbnail = UploadedFile::getInstance($model, 'thumbnail');
            if ($thumbnail) {
                $file_name = uniqid() . time() . '.' . $thumbnail->extension;
                if ($thumbnail->saveAs(Yii::getAlias('@webroot') . "/" . Yii::getAlias('@cat_image') . "/" . $file_name)) {
                    $model->thumbnail = $file_name;
                } else {
                    Yii::$app->getSession()->setFlash('error', 'Không thành công, vui lòng thử lại');
                    return $this->render('create', [
                        'model' => $model,
                        'donation_request' => $donation_request
                    ]);
                }
            } else {
                Yii::$app->getSession()->setFlash('error', 'Không được để trống ảnh đại diện');
                return $this->render('create', [
                    'model' => $model,
                    'donation_request' => $donation_request
                ]);
            }
            $model->lead_donor_id = $model->village->lead_donor_id;
            $model->started_at = $started_at;
            $model->finished_at = $finished_at;
            $model->status = Campaign::STATUS_NEW;

            if ($model->save()) {
                /** @var DonationRequest $rq */
                $rq = DonationRequest::findOne($model->donation_request_id);
                if ($rq) {
                    $rq->status = DonationRequest::STATUS_APPROVED;
                    if (!$rq->save()) {
                        Yii::error($rq->getErrors());
                    }
                }
                Yii::$app->getSession()->setFlash('success', ' Thêm mới chiến dịch thành công');
                $this->redirect(['view', 'id' => $model->id, 'active' => 2]);

            } else {
                Yii::error($model->getErrors());
                Yii::$app->getSession()->setFlash('error', 'Không thành công, vui lòng thử lại');
            }
        }

        return $this->render('create', [
            'model' => $model,
            'donation_request' => $donation_request
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
//        $model->setScenario('update');
        $model->start_date = date('d-m-Y H:i:s', $model->started_at);
        $model->end_date = date('d-m-Y H:i:s', $model->finished_at);
        $old_thumbnail = $model->thumbnail;

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        $model->setScenario('update');
        if ($model->load(Yii::$app->request->post())) {
            $started_at = 0;
            $finished_at = 0;
            if ($model->start_date) {
                $started_at = strtotime(DateTime::createFromFormat("d-m-Y H:i:s", $model->start_date)->format('Y-m-d H:i:s'));;
            }

            if ($model->end_date) {
                $finished_at = strtotime(DateTime::createFromFormat("d-m-Y H:i:s", $model->end_date)->format('Y-m-d H:i:s'));;
            }

            $model->started_at = $started_at;
            $model->finished_at = $finished_at;
            $model->lead_donor_id = $model->village->lead_donor_id;

            if ($model->finished_at < $model->started_at) {
                Yii::$app->getSession()->setFlash('error', 'Ngày kết thúc không được nhỏ hơn ngày bắt đầu');
                return $this->render('update', [
                    'model' => $model,
                ]);
            }

            $thumbnail = UploadedFile::getInstance($model, 'thumbnail');
            if ($thumbnail) {
                $file_name = uniqid() . time() . '.' . $thumbnail->extension;
                if ($thumbnail->saveAs(Yii::getAlias('@webroot') . "/" . Yii::getAlias('@cat_image') . "/" . $file_name)) {
                    $model->thumbnail = $file_name;
                } else {
                    Yii::$app->getSession()->setFlash('error', 'Không thành công, vui lòng thử lại');
                    return $this->render('update', [
                        'model' => $model,
                    ]);
                }
            } else {
                $model->thumbnail = $old_thumbnail;
            }

            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }

        }
        return $this->render('update', [
            'model' => $model,
        ]);

    }

    public function actionUpdateStatus($id, $type)
    {
        $model = $this->findModel($id);
        $model->status = $type;
        if ($type == Campaign::STATUS_ACTIVE) {
            $campaign_bank = CampaignBankAsm::findOne(['campaign_id' => $model->id]);
            $campaign_item = CampaignDonationItemAsm::findOne(['campaign_id' => $model->id]);
            if (!$campaign_bank) {
                Yii::$app->session->setFlash('error', 'Chiến dịch chưa thể bắt đầu khi hình thức ủng hộ còn trống!');
                return $this->redirect(['view', 'id' => $model->id, 'active' => 4]);
            }
            if (!$campaign_item) {
                Yii::$app->session->setFlash('error', 'Chiến dịch chưa thể bắt đầu khi danh mục ủng hộ còn trống!');
                return $this->redirect(['view', 'id' => $model->id, 'active' => 2]);
            }
            $model->published_at = time();
        }
        if ($model->save()) {
            if ($type == Campaign::STATUS_ACTIVE) {
//                Brandname::sendCampaignCreateSms($model);
//                Brandname::sendCampaignApprovedSms($model);
            }
            if ($type == Campaign::STATUS_DONE) {
//                $model->sendSmsEndOfCampaign();
            }

            if ($type == Campaign::STATUS_DELETED) {
                if ($model->donationRequest) {
                    $model->donationRequest->status = DonationRequest::STATUS_NEW;
                    $model->donationRequest->save();
                }
            }
            Yii::$app->session->setFlash('success', 'Cập nhật trạng thái thành công!');
        } else {
            Yii::error($model->getErrors());
            Yii::$app->session->setFlash('error', 'Lỗi hệ thống, vui lòng thử lại!');
        }
        return $this->redirect(['view', 'id' => $model->id]);
    }

    /**
     * Deletes an existing Campaign model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
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
        if (($model = Campaign::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    // THE CONTROLLER
    public function actionGetDonationRequest()
    {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents != null) {
                $village_id = $parents[0];

                /** @var DonationRequest[] $donations_rq */
                $donations_rq = DonationRequest::find()->andWhere(['village_id' => $village_id])->all();
                foreach ($donations_rq as $rq) {
                    $item = ['id' => $rq->id, 'name' => $rq->title];
                    Yii::info($item);
                    array_push($out, $item);
                }

//                $out = [];
                Yii::info($out);
                // the getSubCatList function will query the database based on the
                // cat_id and return an array like below:
                // [
                //    ['id'=>'<sub-cat-id-1>', 'name'=>'<sub-cat-name1>'],
                //    ['id'=>'<sub-cat_id_2>', 'name'=>'<sub-cat-name2>']
                // ]
                echo Json::encode(['output' => $out, 'selected' => '']);
                return;
            }
        }
        echo Json::encode(['output' => '', 'selected' => '']);
    }


    public function actionUploadFile()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $model = new Campaign();

        $files = null;

        $type = 1;

        $old_value = Yii::$app->request->post('screenshots_input');

        $model->load(Yii::$app->request->post());

        $attribute = 'album_image';

        $files = $_FILES['Campaign'];
        $file_type = '';
        list($width, $height, $file_type, $attr) = getimagesize($files['tmp_name']["$attribute"][0]);
        Yii::info($width . 'xxx' . $height);

        Yii::info($files);
        $new_file = [];
        $size = $files['size']["$attribute"][0];
        $ext = explode('.', basename($files['name']["$attribute"][0]));
        $file_name = uniqid() . time() . '.' . array_pop($ext);
        $target = Yii::getAlias('@webroot') . DIRECTORY_SEPARATOR . Yii::getAlias('@cat_image') . DIRECTORY_SEPARATOR . $file_name;
        if (move_uploaded_file($files['tmp_name']["$attribute"][0], $target)) {
            $success = true;
            $new_file['name'] = $file_name;
            $new_file['type'] = $type;
            $new_file['size'] = $size;
        } else {
            $success = false;
        }

        // neu tao file thanh cong. tra ve danh sach file moi
        if ($success) {
            $old_value = Campaign::convertJsonToArray($old_value);
            $old_value[] = $new_file;
        }
        $output = ['success' => $success, 'output' => $new_file];

        return $output;
    }

    public function actionAddDonationItem($id)
    {
        $model = new CampaignDonationItemAsm();
        if ($model->load(Yii::$app->request->post())) {
            $model->status = CampaignDonationItemAsm::STATUS_ACTIVE;
            $model->campaign_id = $id;
            if ($model->save()) {
                Yii::$app->getSession()->setFlash('success', 'Thêm mới danh mục ủng hộ thành công');
            } else {
                Yii::error($model->getErrors());
                Yii::$app->getSession()->setFlash('error', 'Không thành công, vui lòng thử lại');
            }
        }

        $this->redirect(['view', 'id' => $id, 'active' => 2]);
    }

    public function actionAddCampaignBank($id)
    {
        $model = new CampaignBankAsm();
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post())) {
            $model->campaign_id = $id;
            if ($model->save()) {
                Yii::$app->getSession()->setFlash('success', 'Thêm mới hình thức ủng hộ thành công');
            } else {
                Yii::error($model->getErrors());
                Yii::$app->getSession()->setFlash('error', 'Không thành công, vui lòng thử lại');
            }
        }

        $this->redirect(['view', 'id' => $id, 'active' => 4]);
    }

    public function actionAddTransaction($id)
    {
        $model = new TransactionForm();
        if ($model->load(Yii::$app->request->post())) {
            $transaction_time = strtotime(DateTime::createFromFormat("d-m-Y H:i:s", $model->donation_time)->format('Y-m-d H:i:s'));
            Yii::info($transaction_time);
            $db_transaction = Yii::$app->db->beginTransaction();
            try {
                $transaction = Transaction::newTransaction(null, $model->username, $id, $transaction_time, $model->address);
                if ($transaction) {
                    $keys = array_keys($model->donation_items);
                    $i = 0;
                    $total = 0;
                    foreach ($model->donation_items as $item) {
                        if ($item > 0) {
                            $tran_item = new TransactionDonationItemAsm();
                            $tran_item->donation_item_id = $keys[$i];
                            $tran_item->transaction_id = $transaction->id;
                            $tran_item->donation_number = $item;
                            $tran_item->status = TransactionDonationItemAsm::STATUS_SUCCESS;
                            if ($tran_item->save()) {
                                /** @var CampaignDonationItemAsm $campaign_item_asm */
                                $campaign_item_asm = CampaignDonationItemAsm::findOne(['campaign_id' => $id, 'donation_item_id' => $keys[$i]]);
                                if ($campaign_item_asm) {
                                    $campaign_item_asm->current_number += $item;
                                    if ($campaign_item_asm->save()) {
                                        $total++;
                                    } else {
                                        $db_transaction->rollBack();
                                        Yii::error($campaign_item_asm->getErrors());
                                    }
                                }
                            } else {
                                $db_transaction->rollBack();
                                Yii::error($tran_item->getErrors());
                            }
                        }
                        $i++;
                    }
                    if ($total > 0) {
                        $db_transaction->commit();
                        Yii::$app->getSession()->setFlash('success', 'Thêm mới danh mục ủng hộ thành công');
                    } else {
                        Yii::$app->getSession()->setFlash('error', 'Không thành công, vui lòng thử lại');
                    }

                } else {
                    Yii::error($model->getErrors());
                    Yii::$app->getSession()->setFlash('error', 'Không thành công, vui lòng thử lại');
                }
            } catch (Exception $e) {
                $db_transaction->rollBack();
                Yii::$app->getSession()->setFlash('error', 'Không thành công, vui lòng thử lại');
            }
        }

        $this->redirect(['view', 'id' => $id, 'active' => 3]);
    }

    public function actionDeleteDonation($id, $campaign_id)
    {
        /** @var CampaignDonationItemAsm $asm */
        $asm = CampaignDonationItemAsm::findOne($id);
        if ($asm) {
            if ($asm->delete()) {
                Yii::$app->getSession()->setFlash('success', 'Xóa danh mục ủng hộ thành công');
            } else {
                Yii::$app->getSession()->setFlash('error', 'Không thành công, vui lòng thử lại');
            }
        } else {
            Yii::$app->getSession()->setFlash('error', 'Không thành công, vui lòng thử lại');
        }
        $this->redirect(['view', 'id' => $campaign_id, 'active' => 2]);
    }

    public function actionDeleteCampaignBank($id, $campaign_id)
    {
        /** @var CampaignDonationItemAsm $asm */
        $asm = CampaignBankAsm::findOne($id);
        if ($asm) {
            if ($asm->delete()) {
                Yii::$app->getSession()->setFlash('success', 'Xóa hình thức ủng hộ thành công');
            } else {
                Yii::$app->getSession()->setFlash('error', 'Không thành công, vui lòng thử lại');
            }
        } else {
            Yii::$app->getSession()->setFlash('error', 'Không thành công, vui lòng thử lại');
        }
        $this->redirect(['view', 'id' => $campaign_id, 'active' => 4]);
    }
}
