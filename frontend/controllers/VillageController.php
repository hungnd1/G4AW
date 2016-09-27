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
    public function actionView($id)
    {
        $model =$this->findModel($id);
        $listCampaignDonor = null;
        $listCampaiasiKind = null;
        $listComment = null;

        $query = Comment::find()
            ->andWhere(['id_new'=>$id])
            ->andWhere(['type'=>Comment::TYPE_VILLAGE])
            ->andWhere(['status'=>Comment::STATUS_ACTIVE])
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


        return $this->render('view', [
            'model' => $model,
            'listComment'=>$listComment,
            'pages'=>$pages
        ]);
    }

    protected function findModel($id)
    {
        if (($model = Village::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('Nội dung không tồn tại.');
        }
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
