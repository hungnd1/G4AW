<?php
/**
 * Created by PhpStorm.
 * User: VS9 X64Bit
 * Date: 22/05/2015
 * Time: 2:28 PM
 */
namespace api\controllers;

use api\models\CampaignItem;
use api\models\CategoryItem;
use yii\web\NotFoundHttpException;

class CategoryController extends ApiController
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator']['except'] = [
            'index',
        ];

        return $behaviors;
    }

    protected function verbs()
    {
        return [
            'index' => ['GET'],
        ];
    }

    public function actionIndex()
    {
        return CategoryItem::getSubCategories();
    }


}