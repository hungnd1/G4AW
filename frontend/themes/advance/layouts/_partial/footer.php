<?php
/**
 * Created by PhpStorm.
 * User: Linh
 * Date: 17/12/2015
 * Time: 3:38 PM
 */
use frontend\helpers\UserHelper;
?>
<!-- Testimonials block BEGIN -->
<div class="footer-block content-center">
    <div class="bottom-footer">
        <div class="container">

            <div class="copy-right">
                <h5><?= UserHelper::multilanguage('CHƯƠNG TRÌNH PHÁT TRIỂN DỰ ÁN CAFE','Information services for sustainable coffee farm managemen') ?></h5>
                <?=UserHelper::multilanguage('Mọi tin, bài, thắc mắc xin gửi về thư điện tử: ','All news, articles, inquiries should be sent to e-mail:') ?><br> Email: eriptcoffee@gmail.com<br><br>
                <?= UserHelper::multilanguage('Hoặc theo địa chỉ Website: Ban Quản trị nội dung - Viện Kinh tế BƯu điện','By address or Website: Content Management Board Posts and Telecommunications Institute of Technology') ?><br>
                <?= UserHelper::multilanguage("Địa chỉ: 122 Hoàng Quốc Việt, Cầu Giấy, Hà Nội<br> ĐT: (04) 04.35746792",'Address: 122 Hoang Quoc Vietnam, Cau Giay, Ha Noi <br> Telephone: (04) 04.35746792') ?><br>

            </div>
            <div class="social">
                <a href="" class="s-fb"><i class="fa fa-facebook"></i></a>
                <a href="" class="s-tw"><i class="fa fa-twitter"></i></a>
                <a href="" class="s-gg"><i class="fa fa-google"></i></a>
            </div>

        </div>
    </div>
</div>
<!-- Testimonials block END -->