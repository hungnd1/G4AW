<?php
namespace frontend\controllers;

use common\helpers\CUtils;
use common\models\Campaign;
use common\models\Category;
use common\models\Comment;
use common\models\LeadDonor;
use common\models\News;
use common\models\Subscriber;
use common\models\Term;
use common\models\Transaction;
use common\models\UnitLink;
use common\models\Village;
use frontend\helpers\FormatNumber;
use frontend\helpers\UserHelper;
use frontend\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use Yii;
use yii\base\InvalidParamException;
use yii\base\InvalidValueException;
use yii\data\Pagination;
use yii\db\mssql\PDO;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\web\BadRequestHttpException;
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

    public function actionForum()
    {
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
    public function actionIndex()
    {

        $listSlide = News::find()->andWhere(['status' => News::STATUS_ACTIVE])
            ->andWhere(['is_slide' => News::SLIDE])
            ->orderBy(['created_at' => SORT_DESC])->limit(6)->all();

        $listNewCategory = Category::find()->andWhere(['status' => Category::STATUS_ACTIVE])
            ->orderBy(['order_number' => SORT_ASC])->all();


        $listUnit = UnitLink::findAll(['status' => UnitLink::STATUS_ACTIVE]);


        return $this->render('index', ['listSlide' => $listSlide, 'listArea' => null,
            'listNewCategory' => $listNewCategory,
            'pages' => 0, 'listProvince' => null,
            'listUnit' => $listUnit, 'listVillage' => null]);
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
                ->andWhere(['id_province' => $id])
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
        return $this->renderPartial('_listVillage', ['listVillage' => $listVillage, 'pages' => $pages]);
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
                ->andWhere(['id_province' => $id])
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
        $id = $this->getParameter('contentId', '');
        $content = $this->getParameter('content', '');
        $type = $this->getParameter('type');
        $diseaseId = $this->getParameter('diseaseId', '');
        $check = false;
//        $comment = Comment::findOne(['village_id' => $id, 'user_id' => Yii::$app->user->id]);
        $feedback = new Comment();
        $feedback->id_new = $id;
        $feedback->content = $content;
        $feedback->id_disease = $diseaseId;
        $feedback->type = $type;
        $feedback->status = Comment::STATUS_ACTIVE;
        $feedback->user_id = Yii::$app->user->id;
        $feedback->created_at = time();
        $feedback->updated_at = time();
        if ($feedback->save(false)) {
            $check = true;
        }
        if ($check) {
            $message = 'Bình luận thành công.';
            return Json::encode(['success' => true, 'message' => $message]);
        } else {
            $message = 'Bình luận không thành công.';
            return Json::encode(['success' => true, 'message' => $message]);
        }

    }

    public function actionListComments()
    {
        $contentId = $this->getParameter('contentId');
        $diseaseId = $this->getParameter('diseaseId');
        $type = $this->getParameter('type');
        $page = $this->getParameter('page');
        $type_new = $this->getParameter('type_new');
        $number = $this->getParameter('number');

        if ($contentId) {
            $query = Comment::find()
                ->andWhere(['id_new' => $contentId]);
        } else {
            $query = Comment::find()
                ->andWhere(['id_disease' => $diseaseId]);
        }
        $query->andWhere(['type' => $type_new])
            ->andWhere(['status' => Comment::STATUS_ACTIVE])
            ->orderBy(['updated_at' => SORT_DESC]);
        $countQuery = clone  $query;
        $pages = new Pagination(['totalCount' => $countQuery->count()]);
        $pageSize = Yii::$app->params['page_size'];
        $pages->setPageSize($pageSize);

        $listComment = null;
        if ($contentId) {
            $comment = Comment::find()
                ->andWhere(['id_new' => $contentId]);
        } else {
            $comment = Comment::find()
                ->andWhere(['id_disease' => $diseaseId]);
        }
        $query->andWhere(['type' => $type_new])
            ->andWhere(['status' => Comment::STATUS_ACTIVE])
            ->orderBy(['updated_at' => SORT_DESC])->limit(10)->offset($page);

        $numberCheck = $number + sizeof($listComment);
        $j = 0;
        foreach ($comment->all() as $item) {
            $listComment[$j] = new \stdClass();
            $listComment[$j]->content = $item->content;
            $listComment[$j]->user = Subscriber::findOne(['id' => $item->user_id]);
            $listComment[$j]->updated_at = $item->updated_at;
            $j++;
        }
        return $this->renderPartial('_listComment', ['listComments' => $listComment, 'pages' => $pages, 'type' => $type, 'numberCheck' => $numberCheck]);
    }

    public function actionListComment()
    {


        $contentId = $this->getParameter('contentId');
        $diseaseId = $this->getParameter('diseaseId');
        $type = $this->getParameter('type');
        $type_new = $this->getParameter('type_new');
        $page = $this->getParameter('page');
        $number = $this->getParameter('number');

        if ($contentId) {
            $query = Comment::find()
                ->andWhere(['id_new' => $contentId]);
        } else {
            $query = Comment::find()
                ->andWhere(['id_disease' => $diseaseId]);
        }
        $query->andWhere(['type' => $type_new])
            ->andWhere(['status' => Comment::STATUS_ACTIVE])
            ->orderBy(['updated_at' => SORT_DESC]);
        $countQuery = clone  $query;
        $pages = new Pagination(['totalCount' => $countQuery->count()]);
        $pageSize = Yii::$app->params['page_size'];
        $pages->setPageSize($pageSize);

        $listComment = null;
        if ($contentId) {
            $comment = Comment::find()
                ->andWhere(['id_new' => $contentId]);
        } else {
            $comment = Comment::find()
                ->andWhere(['id_disease' => $diseaseId]);
        }

        $query->andWhere(['type' => $type_new])
            ->andWhere(['status' => Comment::STATUS_ACTIVE])
            ->orderBy(['updated_at' => SORT_DESC])->limit(10);
        $numberCheck = $number + sizeof($listComment);
        $j = 0;
        foreach ($comment->all() as $item) {
            $listComment[$j] = new \stdClass();
            $listComment[$j]->content = $item->content;
            $listComment[$j]->user = Subscriber::findOne(['id' => $item->user_id]);
            $listComment[$j]->updated_at = $item->updated_at;
            $j++;
        }
        return $this->renderPartial('_listComment', ['listComments' => $listComment, 'pages' => $pages, 'type' => $type, 'numberCheck' => $numberCheck]);
    }

    public function actionGetWeatherDetail()
    {
        $station_id = $this->getParameter('station_id', '');
        if (!$station_id) {
            throw new InvalidValueException('Station ID ');
        }
        $url = Yii::$app->params['apiUrl'] . "weather/get-weather-detail?station_id=" . $station_id;
        $response = $this->callCurl($url);
        $weather = $response['data']['items'];
        return $this->renderPartial('weatherDetail', ['weather' => (object)$weather]);
    }


    private function callCurl($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/xml;charset=UTF-8', 'X-Api-Key: xjunvhntdjcews3bftmvep6wu3hs62qc'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $ch_result = curl_exec($ch);
        curl_close($ch);
        $arr_detail = json_decode($ch_result, true);
        return $arr_detail;
    }

    public function actionDetail($temp = 0, $pre = 0, $wind = 0)
    {

        $url = Yii::$app->params['apiUrl'] . "app/gap-advice?tem=" . $temp . "&pre=" . $pre . "&wind=" . $wind;
        $response = $this->callCurl($url);
        $advice = $response['data'];
        /** @var  $model News */
        return $this->render('detail', ['model' => (object)$advice]);

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
        $model = Term::find()->orderBy(['created_at' => SORT_DESC])->one();
        return $this->render('about', ['model' => $model]);
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

    public function actionWeatherDetail($station_id)
    {
        $url = Yii::$app->params['apiUrl'] . "weather/get-weather-detail?station_id=" . $station_id;
        $response = $this->callCurl($url);
        $weather = $response['data'];
        $weather_current = $response['data']['items'];
        $weather_next_week = $response['data']['events'];
        $weather_week_ago = $response['data']['weather_week_ago'];
        return $this->render('weather_detail',
            ['weather_current' => (object)$weather_current, 'weather_next_week' => $weather_next_week, 'weather_week_ago' => (object)$weather_week_ago]);
    }

}
