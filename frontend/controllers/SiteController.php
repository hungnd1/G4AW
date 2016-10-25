<?php
namespace frontend\controllers;

use common\helpers\Brandname;
use common\models\Area;
use common\models\Campaign;
use common\models\Category;
use common\models\Comment;
use common\models\Introduction;
use common\models\LeadDonor;
use common\models\News;
use common\models\Province;
use common\models\Transaction;
use common\models\UnitLink;
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

    public function actionSession($vi){
        $_SESSION['vi'] = $vi;
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

        $listUnit = UnitLink::findAll(['status'=>UnitLink::STATUS_ACTIVE]);

        $newsQuery = Village::find()
            ->andWhere(['status' => Village::STATUS_ACTIVE])
            ->orderBy('name')->limit(10);
        $countQuery = clone $newsQuery;
        $pages = new Pagination(['totalCount' => $countQuery->count()]);
        $listVillage = $newsQuery->all();

        $listProvince = Province::find()->andWhere(['status' => Province::STATUS_ACTIVE])->orderBy(['name'=>SORT_ASC])->all();


        return $this->render('index',['listNew'=>$listNew,'listSlide'=>$listSlide,'listArea'=>$listArea,
            'listNewCategory'=>$listNewCategory,'listKnow'=>$listKnow,'listKnowCategory'=>$listKnowCategory,
            'pages'=>$pages,'listProvince' => $listProvince,
            'listUnit'=>$listUnit,'listVillage'=>$listVillage]);
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex($id = 0, $filter = null)
    {
        if(!isset($_SESSION['vi'])){
            $_SESSION['vi'] = 'vi';
        }
        if($_SESSION['vi'] == 'en'){
            $_SESSION['vi'] = 'en';
        }else{
            $_SESSION['vi'] = 'vi';
        }


        $listSlide  = News::find()->andWhere(['status'=>News::STATUS_ACTIVE])
            ->andWhere(['is_slide'=>News::SLIDE])
            ->orderBy(['created_at'=>SORT_DESC])->limit(6)->all();

        $listArea = Area::find()->andWhere(['status'=>Area::STATUS_ACTIVE])->all();

        $listNew  = News::find()->andWhere(['status'=>News::STATUS_ACTIVE])->andWhere(['type'=>News::TYPE_NEW])
            ->orderBy(['created_at'=>SORT_DESC])->limit(3)->all();

        $listNewCategory = Category::find()->andWhere(['status'=>Category::STATUS_ACTIVE])->andWhere(['type'=>Category::TYPE_NEW])
            ->orderBy(['order_number'=>SORT_ASC])->all();

        $listKnow  = News::find()->andWhere(['status'=>News::STATUS_ACTIVE])->andWhere(['type'=>News::TYPE_KNOW])
            ->orderBy(['created_at'=>SORT_DESC])->limit(3)->all();

        $listKnowCategory = Category::find()->andWhere(['status'=>Category::STATUS_ACTIVE])->andWhere(['type'=>Category::TYPE_KNOW])
            ->orderBy(['order_number'=>SORT_ASC])->all();

        $listUnit = UnitLink::findAll(['status'=>UnitLink::STATUS_ACTIVE]);

        $newsQuery = Village::find()
            ->andWhere(['status' => Village::STATUS_ACTIVE])
            ->orderBy('name')->limit(10);
        $countQuery = clone $newsQuery;
        $pages = new Pagination(['totalCount' => $countQuery->count()]);
        $listVillage = $newsQuery->all();

        $listProvince = Province::find()->andWhere(['status' => Province::STATUS_ACTIVE])->orderBy(['name'=>SORT_ASC])->all();


        return $this->render('index',['listNew'=>$listNew,'listSlide'=>$listSlide,'listArea'=>$listArea,
        'listNewCategory'=>$listNewCategory,'listKnow'=>$listKnow,'listKnowCategory'=>$listKnowCategory,
            'pages'=>$pages,'listProvince' => $listProvince,
        'listUnit'=>$listUnit,'listVillage'=>$listVillage]);
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


    public function actionGetVillage()
    {

        $id = $this->getParameter('id', 0);
        $filter = $this->getParameter('filter', null);

        if ($id == 0 && $filter == null && $filter == '') {
            $newsQuery = Village::find()->andWhere(['status' => Village::STATUS_ACTIVE])
                ->orderBy(['name' => SORT_ASC])->limit(10);
            $countQuery = clone $newsQuery;
            $pages = new Pagination(['totalCount' => $countQuery->count()]);
            $listVillage = $newsQuery->all();
        } else if ($id > 0) {
            $newsQuery = Village::find()->andWhere(['status' => Village::STATUS_ACTIVE])
                ->andWhere(['id_province'=>$id])
                ->orderBy(['name' => SORT_ASC])->limit(10);
            $countQuery = clone $newsQuery;
            $pages = new Pagination(['totalCount' => $countQuery->count()]);
            $listVillage = $newsQuery->all();
        } else {
            $newsQuery = Village::find()
                ->andWhere(['status' => Village::STATUS_ACTIVE])
                ->andWhere(['like', 'mid(lower(name),1,1)', strtolower($filter)])
                ->orderBy(['name' => SORT_ASC])
                ->limit(10);
            $countQuery = clone $newsQuery;
            $pages = new Pagination(['totalCount' => $countQuery->count()]);
            $listVillage = $newsQuery->all();
        }
        return $this->renderPartial('_listVillage', ['listVillage' => $listVillage,'pages'=>$pages]);
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

    public function actionGetVillages()
    {
        $listComment = null;
        $page = $this->getParameter('page');

        $id = $this->getParameter('id', 0);
        $filter = $this->getParameter('filter', null);
        if ($id == 0 && $filter == null && $filter == '') {
            $listVillage = Village::find()->andWhere(['status' => Village::STATUS_ACTIVE])
                ->orderBy(['name' => SORT_ASC])->limit(10)->offset($page)->all();
        } else if ($id > 0) {
            $listVillage = Village::find()->andWhere(['status' => Village::STATUS_ACTIVE])
                ->andWhere(['id_province'=>$id])
                ->orderBy(['name' => SORT_ASC])->limit(10)->offset($page)->all();
        } else {
            $listVillage = Village::find()
                ->andWhere(['status' => Village::STATUS_ACTIVE])
                ->andWhere(['like', 'mid(lower(name),1,1)', strtolower($filter)])
                ->orderBy(['name' => SORT_ASC])
                ->limit(10)->offset($page)->all();
        }
        return $this->renderPartial('_listVillageMore', ['listVillage' => $listVillage]);
    }

    public function actionLinked()
    {
        $listDonor = UnitLink::findAll(['status' => UnitLink::STATUS_ACTIVE]);
        return $this->render('lead_donor', ['listDonor' => $listDonor]);
    }

    public function actionFeedback()
    {
        $id = $this->getParameter('contentId');
        $content = $this->getParameter('content');
        $type = $this->getParameter('type');
        $check = false;
//        $comment = Comment::findOne(['village_id' => $id, 'user_id' => Yii::$app->user->id]);
        $feedback = new Comment();
            $feedback->id_new = $id;
            $feedback->content = $content;
            $feedback->type = $type;
            $feedback->status = Comment::STATUS_DRAFT;
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
        $type_new = $this->getParameter('type_new');
        $number = $this->getParameter('number');

        $query = Comment::find()
            ->andWhere(['id_new'=>$contentId])
            ->andWhere(['type'=>$type_new])
            ->andWhere(['status'=>Comment::STATUS_ACTIVE])
            ->orderBy(['updated_at'=>SORT_DESC]);
        $countQuery = clone  $query;
        $pages = new Pagination(['totalCount'=>$countQuery->count()]);
        $pageSize = Yii::$app->params['page_size'];
        $pages->setPageSize($pageSize);

        $listComment = null;
            $comment = Comment::find()
                ->andWhere(['id_new'=>$contentId])
                ->andWhere(['type'=>$type_new])
                ->andWhere(['status'=>Comment::STATUS_ACTIVE])
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
        $type_new = $this->getParameter('type_new');
        $page = $this->getParameter('page');
        $number = $this->getParameter('number');


        $query = Comment::find()
            ->andWhere(['id_new'=>$contentId])
            ->andWhere(['type'=>$type_new])
            ->andWhere(['status'=>Comment::STATUS_ACTIVE])
            ->orderBy(['updated_at'=>SORT_DESC]);
        $countQuery = clone  $query;
        $pages = new Pagination(['totalCount'=>$countQuery->count()]);
        $pageSize = Yii::$app->params['page_size'];
        $pages->setPageSize($pageSize);

        $listComment = null;
        $comment = Comment::find()
            ->andWhere(['id_new'=>$contentId])
            ->andWhere(['type'=>$type_new])
            ->andWhere(['status'=>Comment::STATUS_ACTIVE])
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
        $model = Introduction::findOne(['status'=>News::STATUS_ACTIVE]);
        return $this->render('about',['model'=>$model]);
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


    public function actionSignup()
    {
        $model = new SignupForm();
        $model->setScenario('signup');
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
