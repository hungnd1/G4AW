<?php
/**
 * Created by PhpStorm.
 * User: Linh
 * Date: 18/03/2016
 * Time: 8:46 AM
 */
/** @var $model \frontend\models\FilterCampaignForm */
/** @var $this \yii\web\View */
use \yii\bootstrap\ActiveForm;

$categoriesData = \yii\helpers\ArrayHelper::map(\common\models\Category::find()->andWhere(['status' => \common\models\Category::STATUS_ACTIVE])->asArray()->all(), 'id', 'display_name');
?>
    <div class="filter-col-1">
        <h4>Thể loại</h4>
        <?php $form = ActiveForm::begin(['id' => 'filter-campaign-by-category-form']); ?>

        <?= $form->field($model, 'categories')->checkboxList($categoriesData, ['id' => 'filter-by-category'])->label(false) ?>

        <?php ActiveForm::end(); ?>
    </div>
