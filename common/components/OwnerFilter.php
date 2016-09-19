<?php

namespace common\components;;


use common\models\User;
use Yii;
use yii\base\ActionFilter;
use yii\web\ForbiddenHttpException;

class OwnerFilter extends ActionFilter
{

    /**
     * @var User $user
     */
    public $user = 'user';
    /**
     * @var callable a PHP callback that returns model relation with service_provider model
     *
     * ~~~
     * function ($action, $params)
     * ~~~
     *
     * where `$action` is the [[Action]] object that this filter is currently handling;
     * `$params` takes the value of [[params]]
     */
    public $model_relation_owner;
    public $field_owner_id = 'created_by';

    public function beforeAction($action)
    {
        $request = Yii::$app->request;
        if ($this->model_relation_owner !== null) {
            $model = call_user_func($this->model_relation_owner, $action, $request->getQueryParams());
        }else{
            return parent::beforeAction($action);
        }

        if($model == null){
            Yii::info('Model relation seller null');
            return parent::beforeAction($action);
        }

        if ($this->user != null) {
            Yii::info('owner id is : '.$model->{$this->field_owner_id});
            if($this->user->id != $model->{$this->field_owner_id}){
                throw new ForbiddenHttpException('Nội dung này không thuộc về bạn!');
            }
        }
        return parent::beforeAction($action);
    }

}
