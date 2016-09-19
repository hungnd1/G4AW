<?php
/**
 * Created by PhpStorm.
 * User: Linh
 * Date: 18/03/2016
 * Time: 8:54 AM
 */
/** @var $pages \yii\data\Pagination */
/** @var $campaigns  \common\models\Campaign */
/** @var $categories   */
/** @var $partners   */
$this->title ='Danh sách chiến dịch';
?>
    <!-- common page-->
    <div class="content-common">
        <div class="container">
            <div class="left-content-2">
                <?= \frontend\widgets\utils\FilterCampaignByCategoryWidget::widget(['defaultValue' => $categories]) ?>
                <?= \frontend\widgets\utils\FilterCampaignByPartnerWidget::widget(['defaultValue' => $partners]) ?>
            </div>
            <div class="right-content-2">
                <div class="top-ct-2">
                    <h1>Danh sách chiến dịch</h1>
                    <div class="dropdown select-ft">
                        <a id="dLabel" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <?=$pages->totalCount?> Chiến dịch từ thiện <span class="t-select">Cấp thiết nhất</span>
                            <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="dLabel">
                            <li><a href="">Mới nhất</a></li>
                            <li><a href="">Cấp thiết nhất</a></li>
                            <li><a href="">Quan tâm nhất</a></li>
                        </ul>
                    </div>
                </div>
                <div class="list-item">
                    <?php
                    foreach($campaigns as $campaign){
                        /**@var $campaign Campaign  */
                        echo $this->render('/campaign/_item',['model'=>$campaign,'showAvatar'=>false]);
                    }
                    ?>
                </div>
                <div class="page-link-common">
                    <?= \frontend\widgets\LinkPager::widget(['pagination' => $pages]) ?>
                </div>
            </div>
        </div>
    </div>
    <!-- end common page-->

<?php
$url = \yii\helpers\Url::to(['/campaign/index','sort'=>$sort]);
$js = <<<JS
$(document).ready(function() {
    $(document).on('change' , "input[type='checkbox']" , function(){
        var categories = [];
        var partners = [];
       $("input:checkbox[name='FilterCampaignForm[categories][]']:checked").each(function() {
            var thisObject = $(this);
            categories.push(thisObject.val());
      });
        $("input:checkbox[name='FilterCampaignForm[partners][]']:checked").each(function() {
            var thisObject = $(this);
            partners.push(thisObject.val());
      });
      var categoriesString =categories.toString();
      var partnersString =partners.toString();
      var href = "$url";

      href = href + "&categories="+categoriesString + '&partners=' + partnersString;
      window.location.href = href;
    });


});
JS;
$this->registerJs($js);

?>