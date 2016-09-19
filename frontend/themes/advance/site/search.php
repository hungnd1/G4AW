<?php
use yii\helpers\Url;
use yii\widgets\ActiveForm;
?>

<!-- content -->
<div class="content">
    <div class="container">
        <div class="cr-page-link">
            <a href="<?= Url::toRoute(['site/index']) ?>">Trang chủ</a>
            <span>/</span>
            <a href="">Kết quả tìm kiếm</a>
        </div>
    </div>
    <div class="container">
        <div class="main-cm-1">
            <div class="block-search-result">
                <h1>Kết quả tìm kiếm</h1>
                <div class="line-ip">
                    <?php $form = ActiveForm::begin([
                        'action' => Url::toRoute('site/search'),
                        'method' => 'GET'
                    ]); ?>
                        <input type="text" name="keyword" onfocus="this.select();" placeholder="Nhập nội dung..." value="<?= isset($_COOKIE['keyword']) && !empty($_COOKIE['keyword']) ? $_COOKIE['keyword'] : ''; ?>">
                        <input type="hidden" name="search"
                           value="<?php echo str_replace(".php", "", "$_SERVER[REQUEST_URI]"); ?>">
                        <button class="bt-submit" type="submit">Tìm kiếm</button>
                    <?php ActiveForm::end(); ?>
                </div>
                <div class="result-searh">
                    <h5>Tìm thấy <?= $totalCount ?> Kết quả với từ khóa <span><?= isset($_COOKIE['keyword']) && !empty($_COOKIE['keyword']) ? $_COOKIE['keyword'] : ''; ?></span></h5>
                    <?php if(isset($listSearch)) {
                        foreach($listSearch as $item){ ?>
                            <div class="list-rs">
                                <?php if($item->type == 1){ ?>
                                    <h4><a href="<?= Url::toRoute(['campaign/view','id'=>$item->id]) ?>"><?= $item->name ?></a></h4>
                                <?php }else if($item->type == 2){ ?>
                                    <h4><a href="<?= Url::toRoute(['news/detail','id'=>$item->id]) ?>"><?= $item->name ?></a></h4>
                                <?php }else if($item->type ==3 ){ ?>
                                    <h4><a href="<?= Url::toRoute(['village/view','id_village'=>$item->id]) ?>"><?= $item->name ?></a></h4>
                                <?php }else if($item->type == 4){ ?>
                                    <h4><a href="<?= Url::toRoute(['donor/view','id'=>$item->id]) ?>"><?= $item->name ?></a></h4>
                                <?php }  ?>
                                <p><?= isset($_COOKIE['keyword']) ? str_replace($_COOKIE['keyword'],'<b>'.$_COOKIE['keyword'].'</b>',str_replace(mb_substr($item->description, 120, strlen($item->description), 'utf-8'), '...', $item->description)) :  str_replace(mb_substr($item->description, 120, strlen($item->description), 'utf-8'), '...', $item->description)?></p>
                            </div>
                        <?php }
                    }?>

                </div>
                <?php
                $pagination = new \yii\data\Pagination(['totalCount' => $totalCount,'pageSize' =>10]);
                echo \yii\widgets\LinkPager::widget([
                    'pagination' => $pagination,
                ]);
                ?>
            </div>
        </div>
    </div>
</div>
<!-- end content -->
