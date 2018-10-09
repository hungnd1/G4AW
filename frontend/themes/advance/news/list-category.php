<?php
/**
 * Created by PhpStorm.
 * User: HungChelsea
 * Date: 11-Aug-16
 * Time: 10:43 AM
 */

use common\models\Category;
use common\models\News;
use frontend\helpers\UserHelper;
use yii\data\Pagination;
use yii\helpers\Url;

?>
<!-- content -->
<div class="content">
    <div class="main-cm-2">
        <div class="container">
            <div class="left-content">
                <div class="cr-page-link">
                    <a href="<?= Url::toRoute(['site/index']) ?>"><?= UserHelper::multilanguage('Trang chủ', 'Home') ?></a>
                    <span>/</span>
                    <a href="<?= Url::toRoute(['news/index']) ?>">Danh sách danh mục</a>
                </div>
                <div class="m-content">
                    <div class="list-news-block">
                        <div class="tab-ct">
                            <div class="tab-content">
                                <?php if (isset($lstCategory) && !empty($lstCategory)) {
                                    $i = 1;
                                    foreach ($lstCategory as $item) {
                                        /** @var $item \common\models\Category */
                                        ?>
                                        <div class="list-new-1">
                                            <div class="thumb-common">
                                                <img class="blank-img" src="../img/blank.gif">
                                                <a href="<?= Url::toRoute(['news/list-new', 'id' => $item->id]) ?>"><img
                                                            class="thumb-cm" src="<?= $item->getImageLink() ?>"></a>
                                            </div>
                                            <div class="left-list">
                                                <h3>
                                                    <a href="<?= Url::toRoute(['news/list-new', 'id' => $item->id]) ?>"><?= $item->display_name ?></a>
                                                </h3>
                                            </div>
                                        </div>
                                        <?php
                                        $i++;
                                    }
                                } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="right-content">
                <div class="block-related block-cm-2">
                    <h3><?= UserHelper::multilanguage('CÓ THỂ BẠN QUAN TÂM', 'Could you care!') ?></h3>
                    <div class="list-related">
                        <?php if (isset($listNewRelated) && !empty($listNewRelated)) {
                            foreach ($listNewRelated as $item) { ?>
                                <?= \frontend\widgets\NewsWidget::widget(['content' => $item]) ?>
                            <?php }
                        } ?>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<!-- end content -->
<script type="text/javascript">
    $(document).ready(function () {
        $('a[data-toggle=\"tab\"]').on('show.bs.tab', function (e) {
            localStorage.setItem('lastTab', $(this).attr('href'));
        });
        var lastTab = localStorage.getItem('lastTab');
        if (lastTab) {
            $('[href=\"' + lastTab + '\"]').tab('show');
        }
    });
</script>