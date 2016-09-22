<?php
namespace frontend\controllers;

use common\helpers\Brandname;
use common\models\Area;
use common\models\Campaign;
use common\models\Category;
use common\models\Comment;
use common\models\LeadDonor;
use common\models\News;
use common\models\Province;
use common\models\Transaction;
use common\models\User;
use common\models\Village;
use frontend\helpers\FormatNumber;
use frontend\helpers\UserHelper;
use frontend\models\ContactForm;
use frontend\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use Yii;
use yii\base\InvalidParamException;
use yii\data\Pagination;
use yii\db\mssql\PDO;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\widgets\ActiveForm;

/**
 * Site controller
 */
class SiteController extends BaseController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup', 'donate'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout', 'donate'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actionForum(){
        $this->layout = 'mainempty';
        return $this->render('forum');
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
//                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],

        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex($id = 0, $filter = null)
    {

        $listSlide  = News::find()->andWhere(['status'=>News::STATUS_ACTIVE])
            ->orderBy(['created_at'=>SORT_DESC])->limit(6)->all();

        $listArea = Area::find()->andWhere(['status'=>Area::STATUS_ACTIVE])->all();

        $listNew  = News::find()->andWhere(['status'=>News::STATUS_ACTIVE])->andWhere(['type'=>News::TYPE_NEW])
            ->orderBy(['created_at'=>SORT_DESC])->limit(8)->all();

        $listNewCategory = Category::find()->andWhere(['status'=>Category::STATUS_ACTIVE])->andWhere(['type'=>Category::TYPE_NEW])
            ->orderBy(['order_number'=>SORT_ASC])->all();

        $listKnow  = News::find()->andWhere(['status'=>News::STATUS_ACTIVE])->andWhere(['type'=>News::TYPE_KNOW])
            ->orderBy(['created_at'=>SORT_DESC])->limit(8)->all();

        $listKnowCategory = Category::find()->andWhere(['status'=>Category::STATUS_ACTIVE])->andWhere(['type'=>Category::TYPE_KNOW])
            ->orderBy(['order_number'=>SORT_ASC])->all();

        return $this->render('index',['listNew'=>$listNew,'listSlide'=>$listSlide,'listArea'=>$listArea,
        'listNewCategory'=>$listNewCategory,'listKnow'=>$listKnow,'listKnowCategory'=>$listKnowCategory]);
    }

    public function actionRules()
    {
        return $this->render('rules');
    }

    public function actionGetCategoryNews()
    {
        $id = $this->getParameter('id', 0);
        $listCategory = News::find()->innerJoin('news_category_asm', 'news_category_asm.news_id = news.id')
            ->innerJoin('category', 'news_category_asm.category_id = category.id')
            ->andWhere(['category.id' => $id])
            ->andWhere(['news.status' => News::STATUS_ACTIVE])
            ->orderBy(['news.published_at' => SORT_DESC, 'news.updated_at' => SORT_DESC])->limit(8)
            ->all();
        return $this->renderPartial('news_category', ['listNewest' => $listCategory]);
    }




    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            UserHelper::setUserId(Yii::$app->user->id);
            return $this->goHome();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionFeedback()
    {
        $id = $this->getParameter('contentId');
        $content = $this->getParameter('content');
        $check = false;
//        $comment = Comment::findOne(['village_id' => $id, 'user_id' => Yii::$app->user->id]);
        $feedback = new Comment();
            $feedback->village_id = $id;
            $feedback->content = $content;
            $feedback->user_id = Yii::$app->user->id;
            $feedback->created_at = time();
            $feedback->updated_at = time();
            if($feedback->save(false)){
                $check = true;
            }
        if ( $check) {
            $message = 'Bình luận thành công.';
            return Json::encode(['success' => true, 'message' => $message]);
        } else {
            $message = 'Bình luận không thành công.';
            return Json::encode(['success' => false, 'message' => $message]);
        }

    }

    public function actionListComments()
    {
        $contentId = $this->getParameter('contentId');
        $type = $this->getParameter('type');
        $page = $this->getParameter('page');
        $number = $this->getParameter('number');

        $query = Comment::find()
            ->andWhere(['village_id'=>$contentId])
            ->orderBy(['updated_at'=>SORT_DESC]);
        $countQuery = clone  $query;
        $pages = new Pagination(['totalCount'=>$countQuery->count()]);
        $pageSize = Yii::$app->params['page_size'];
        $pages->setPageSize($pageSize);

        $listComment = null;
            $comment = Comment::find()
                ->andWhere(['village_id'=>$contentId])
                ->orderBy(['updated_at'=>SORT_DESC])->limit(10)->offset($page)->all();
        $numberCheck = $number + sizeof($listComment);
        $j =0 ;
        foreach($comment as $item ){
            $listComment[$j] = new \stdClass();
            $listComment[$j]->content  = $item->content;
            $listComment[$j]->user = User::findOne(['id'=>$item->user_id]);
            $listComment[$j]->updated_at = $item->updated_at;
            $j++;
        }
                return $this->renderPartial('_listComment', ['listComments' => $listComment,'pages'=>$pages, 'type' => $type,'numberCheck'=>$numberCheck]);
    }

    public function actionListComment()
    {


        $contentId = $this->getParameter('contentId');
        $type = $this->getParameter('type');
        $page = $this->getParameter('page');
        $number = $this->getParameter('number');


        $query = Comment::find()
            ->andWhere(['village_id'=>$contentId])
            ->orderBy(['updated_at'=>SORT_DESC]);
        $countQuery = clone  $query;
        $pages = new Pagination(['totalCount'=>$countQuery->count()]);
        $pageSize = Yii::$app->params['page_size'];
        $pages->setPageSize($pageSize);

        $listComment = null;
        $comment = Comment::find()
            ->andWhere(['village_id'=>$contentId])
            ->orderBy(['updated_at'=>SORT_DESC])->limit(10)->all();
        $numberCheck = $number + sizeof($listComment);
        $j =0 ;
        foreach($comment as $item ){
            $listComment[$j] = new \stdClass();
            $listComment[$j]->content  = $item->content;
            $listComment[$j]->user = User::findOne(['id'=>$item->user_id]);
            $listComment[$j]->updated_at = $item->updated_at;
            $j++;
        }
        return $this->renderPartial('_listComment', ['listComments' => $listComment,'pages'=>$pages, 'type' => $type,'numberCheck'=>$numberCheck]);
    }


    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionContact()
    {
        return $this->render('contact');
    }

    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    public function actionNews()
    {
        $newsQuery = News::find()->andWhere(['status' => News::STATUS_ACTIVE])->orderBy('created_at desc');
        $countQuery = clone $newsQuery;
        $pages = new Pagination(['totalCount' => $countQuery->count()]);
        $pageSize = Yii::$app->params['page_size'];
        $pages->setPageSize($pageSize);
        $news = $newsQuery->offset($pages->offset)
            ->limit($pages->limit)->all();
        return $this->render('news',
            [
                'news' => $news,
                'pages' => $pages,

            ]);
    }

    public function actionDonate($campaign_id)
    {
        $campaign = Campaign::findOne($campaign_id);
        if (!$campaign) {
            throw  new NotFoundHttpException('Không tìm thấy chiến dịch. vui lòng thử lại');
        }
        $transactionModel = new Transaction();
        $transactionModel->campaign_id = $campaign_id;
        $transactionModel->user_id = Yii::$app->user->id;
        $transactionModel->username = $this->user->username;
        $transactionModel->payment_type = Transaction::PAYMENT_TYPE_CARD;
        return $this->render('donate', [
            'model' => $transactionModel,
            'campaign' => $campaign
        ]);
    }

    public function actionDonateByMoney($campaign_id)
    {
        $campaign = Campaign::findOne($campaign_id);
        if (!$campaign) {
            throw  new NotFoundHttpException('Không tìm thấy chiến dịch. vui lòng thử lại');
        }
        $transactionModel = new Transaction();

        if ($transactionModel->load(Yii::$app->request->post()) && $transactionModel->saveTransaction()) {
            Brandname::sendSms($transactionModel);
            Yii::$app->session->addFlash('success', 'Bạn đã ủng hộ thành công');

        }
        $transactionModel->campaign_id = $campaign_id;
        $transactionModel->user_id = Yii::$app->user->id;
        $transactionModel->username = $this->user->username;
        $transactionModel->payment_type = Transaction::PAYMENT_TYPE_MONEY;
        return $this->render('/site/_donate_type/money', [
            'model' => $transactionModel,
            'campaign' => $campaign
        ]);
    }

    public function actionSignup()
    {
        $model = new SignupForm();
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                Yii::$app->session->setFlash('success', 'Đăng ký thành công');
                return $this->redirect(['site/login']);
            }
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for email provided.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password was saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

    public function actionSearch()
    {
        $keyword = addcslashes($this->getParameter('keyword', ''), '\\');
        $page = $this->getParameter('page', 1);
        $rows_per_page = $this->getParameter('per-page', 10);

        $page_start = ($page - 1) * $rows_per_page;
        $page_end = $rows_per_page;
        $search = $this->getParameter('search', '');
        if (!empty($keyword)) {
            $_COOKIE['keyword'] = $keyword;;
        }
        $keyword = FormatNumber::standardStringPreventSqlInjection($keyword);
        $keyword = strtolower($keyword);

        $sql = " select distinct title as name,id,short_description as description,2 as type from news where lower(title) like :keyword or lower(short_description) like :keyword and status = 10";
        $sql .= " LIMIT " . $page_start . " , " . $page_end;

        $command = Yii::$app->db->createCommand($sql);
        $command->bindValue(":keyword", "%$keyword%", PDO::PARAM_STR);
        $dataReader = $command->query();
        $listSearch = null;
        $i = 0;
        foreach ($dataReader as $item) {
            $listSearch[$i] = new \stdClass();
            $listSearch[$i]->id = $item['id'];
            $listSearch[$i]->name = $item['name'];
            $listSearch[$i]->description = $item['description'];
            $listSearch[$i]->type = $item['type'];
            $i++;
        }
        $totalCount = $this->countNumber($keyword);
        return $this->render('search', ['listSearch' => $listSearch, 'totalCount' => $totalCount]);
    }

    public function countNumber($keyword)
    {

        $sql = " select distinct title as name,id,short_description as description,2 as type from news where title like :keyword or short_description like :keyword";

        $command = Yii::$app->db->createCommand($sql);
        $command->bindValue(":keyword", "%$keyword%", PDO::PARAM_STR);
        $rowCount = $command->execute();
        return $rowCount;
    }


}
