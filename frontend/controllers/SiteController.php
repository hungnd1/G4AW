<?php
namespace frontend\controllers;

use common\models\Banner;
use common\models\Category;
use common\models\Comment;
use common\models\News;
use common\models\Subscriber;
use common\models\SubscriberActivity;
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
        if (time() - Yii::$app->session->get('timeExpired') > 60 * 60) {
            $request = Yii::$app->request;
            $agent = $this->parse_user_agent($request->getUserAgent());
            $params = $request->getQueryParams();
            $audit_log = new SubscriberActivity();
            $audit_log->subscriber_id = Yii::$app->user->getId() ? Yii::$app->user->getId() : 1;
            $audit_log->msisdn = Yii::$app->user->getId() ? Yii::$app->user->getIdentity()->username : null;
            $audit_log->ip_address = $request->getUserIP();
            $audit_log->user_agent = Yii::t('app', 'Dùng ') . $agent['browser'] . '/' . $agent['version'] . Yii::t('app', ' trên HĐH ') . $agent['platform'];
            $audit_log->action = 'Xem';
            $audit_log->target_id = isset($params['id']) ? $params['id'] : null;
            $audit_log->description = 'Truy cap trang';
            $audit_log->status = 'Waiting response';
            $audit_log->channel = 7;
            $audit_log->save(false);
            Yii::$app->session->set('timeExpired', time());
        }
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
        $advice = $response['data']['items'];
        $content = '';
        foreach ($advice as $item) {
            $content .= '<h3>' . $item['tag'] . '</h3>';
            $content .= $item['content'];
        }
        return $this->render('detail', ['listAdvice' => $advice, 'content' => $content]);

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
        $listSlide = Banner::find()->all();
        return $this->render('about', ['listSlide' => $listSlide]);
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
        $dataCharts = $this->getData((object)$weather_week_ago);
        $dataPrecipitation = $this->getDataPrecipitation((object)$weather_week_ago);

        return $this->render('weather_detail',
            ['weather_current' => (object)$weather_current, 'weather_next_week' => $weather_next_week, 'weather_week_ago' => $weather_week_ago, 'dataCharts' => $dataCharts, 'dataPrecipitation' => $dataPrecipitation]);
    }


    private function getData($listData)
    {
        $report_date = [];
        $arr_total_revenues = [];
        $arr_renew_revenues = [];
        foreach ($listData as $item) {
            array_splice($report_date, 0, 0, date('d/m/Y H:i', $item['timestamp']));
            array_splice($arr_total_revenues, 0, 0, $item['tmin']);
            array_splice($arr_renew_revenues, 0, 0, $item['tmax']);
        }
        $data[0] = array_reverse($report_date);
        $data[1] = array_reverse($arr_total_revenues);
        $data[2] = array_reverse($arr_renew_revenues);
        return $data;
    }

    private function getDataPrecipitation($listData)
    {
        $report_date = [];
        $arr_renew_revenues = [];
        foreach ($listData as $item) {
            array_splice($report_date, 0, 0, date('d/m/Y H:i', $item['timestamp']));
            array_splice($arr_renew_revenues, 0, 0, $item['precipitation']);
        }
        $data[0] = array_reverse($report_date);
        $data[1] = array_reverse($arr_renew_revenues);
        return $data;
    }

    public function actionWeather()
    {
        return $this->render('weather');
    }

    private function parse_user_agent($u_agent = null)
    {
        if (is_null($u_agent)) {
            if (isset($_SERVER['HTTP_USER_AGENT'])) {
                $u_agent = $_SERVER['HTTP_USER_AGENT'];
            } else {
                throw new \InvalidArgumentException('parse_user_agent requires a user agent');
            }
        }

        $platform = null;
        $browser = null;
        $version = null;

        $empty = array('platform' => $platform, 'browser' => $browser, 'version' => $version);

        if (!$u_agent) return $empty;

        if (preg_match('/\((.*?)\)/im', $u_agent, $parent_matches)) {
            preg_match_all('/(?P<platform>BB\d+;|Android|CrOS|Tizen|iPhone|iPad|iPod|Linux|Macintosh|Windows(\ Phone)?|Silk|linux-gnu|BlackBerry|PlayBook|X11|(New\ )?Nintendo\ (WiiU?|3?DS)|Xbox(\ One)?)
				(?:\ [^;]*)?
				(?:;|$)/imx', $parent_matches[1], $result, PREG_PATTERN_ORDER);

            $priority = array('Xbox One', 'Xbox', 'Windows Phone', 'Tizen', 'Android', 'CrOS', 'X11');

            $result['platform'] = array_unique($result['platform']);
            if (count($result['platform']) > 1) {
                if ($keys = array_intersect($priority, $result['platform'])) {
                    $platform = reset($keys);
                } else {
                    $platform = $result['platform'][0];
                }
            } elseif (isset($result['platform'][0])) {
                $platform = $result['platform'][0];
            }
        }

        if ($platform == 'linux-gnu' || $platform == 'X11') {
            $platform = 'Linux';
        } elseif ($platform == 'CrOS') {
            $platform = 'Chrome OS';
        }

        preg_match_all('%(?P<browser>Camino|Kindle(\ Fire)?|Firefox|Iceweasel|Safari|MSIE|Trident|AppleWebKit|TizenBrowser|Chrome|
				Vivaldi|IEMobile|Opera|OPR|Silk|Midori|Edge|CriOS|UCBrowser|
				Baiduspider|Googlebot|YandexBot|bingbot|Lynx|Version|Wget|curl|
				Valve\ Steam\ Tenfoot|
				NintendoBrowser|PLAYSTATION\ (\d|Vita)+)
				(?:\)?;?)
				(?:(?:[:/ ])(?P<version>[0-9A-Z.]+)|/(?:[A-Z]*))%ix',
            $u_agent, $result, PREG_PATTERN_ORDER);

        // If nothing matched, return null (to avoid undefined index errors)
        if (!isset($result['browser'][0]) || !isset($result['version'][0])) {
            if (preg_match('%^(?!Mozilla)(?P<browser>[A-Z0-9\-]+)(/(?P<version>[0-9A-Z.]+))?%ix', $u_agent, $result)) {
                return array('platform' => $platform ?: null, 'browser' => $result['browser'], 'version' => isset($result['version']) ? $result['version'] ?: null : null);
            }

            return $empty;
        }

        if (preg_match('/rv:(?P<version>[0-9A-Z.]+)/si', $u_agent, $rv_result)) {
            $rv_result = $rv_result['version'];
        }

        $browser = $result['browser'][0];
        $version = $result['version'][0];

        $lowerBrowser = array_map('strtolower', $result['browser']);

        $find = function ($search, &$key, &$value = null) use ($lowerBrowser) {
            $search = (array)$search;

            foreach ($search as $val) {
                $xkey = array_search(strtolower($val), $lowerBrowser);
                if ($xkey !== false) {
                    $value = $val;
                    $key = $xkey;

                    return true;
                }
            }

            return false;
        };

        $key = 0;
        $val = '';
        if ($browser == 'Iceweasel') {
            $browser = 'Firefox';
        } elseif ($find('Playstation Vita', $key)) {
            $platform = 'PlayStation Vita';
            $browser = 'Browser';
        } elseif ($find(array('Kindle Fire', 'Silk'), $key, $val)) {
            $browser = $val == 'Silk' ? 'Silk' : 'Kindle';
            $platform = 'Kindle Fire';
            if (!($version = $result['version'][$key]) || !is_numeric($version[0])) {
                $version = $result['version'][array_search('Version', $result['browser'])];
            }
        } elseif ($find('NintendoBrowser', $key) || $platform == 'Nintendo 3DS') {
            $browser = 'NintendoBrowser';
            $version = $result['version'][$key];
        } elseif ($find('Kindle', $key, $platform)) {
            $browser = $result['browser'][$key];
            $version = $result['version'][$key];
        } elseif ($find('OPR', $key)) {
            $browser = 'Opera Next';
            $version = $result['version'][$key];
        } elseif ($find('Opera', $key, $browser)) {
            $find('Version', $key);
            $version = $result['version'][$key];
        } elseif ($find(array('IEMobile', 'Edge', 'Midori', 'Vivaldi', 'Valve Steam Tenfoot', 'Chrome'), $key, $browser)) {
            $version = $result['version'][$key];
        } elseif ($browser == 'MSIE' || ($rv_result && $find('Trident', $key))) {
            $browser = 'MSIE';
            $version = $rv_result ?: $result['version'][$key];
        } elseif ($find('UCBrowser', $key)) {
            $browser = 'UC Browser';
            $version = $result['version'][$key];
        } elseif ($find('CriOS', $key)) {
            $browser = 'Chrome';
            $version = $result['version'][$key];
        } elseif ($browser == 'AppleWebKit') {
            if ($platform == 'Android' && !($key = 0)) {
                $browser = 'Android Browser';
            } elseif (strpos($platform, 'BB') === 0) {
                $browser = 'BlackBerry Browser';
                $platform = 'BlackBerry';
            } elseif ($platform == 'BlackBerry' || $platform == 'PlayBook') {
                $browser = 'BlackBerry Browser';
            } else {
                $find('Safari', $key, $browser) || $find('TizenBrowser', $key, $browser);
            }

            $find('Version', $key);
            $version = $result['version'][$key];
        } elseif ($pKey = preg_grep('/playstation \d/i', array_map('strtolower', $result['browser']))) {
            $pKey = reset($pKey);

            $platform = 'PlayStation ' . preg_replace('/[^\d]/i', '', $pKey);
            $browser = 'NetFront';
        }

        return array('platform' => $platform ?: null, 'browser' => $browser ?: null, 'version' => $version ?: null);

    }
}
