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
                <h5><?= UserHelper::multilanguage('DỊCH VỤ THÔNG TIN GREENCOFFEE','Information services for sustainable coffee farm managemen') ?></h5>
                <?=UserHelper::multilanguage('Mọi tin, bài, thắc mắc xin gửi về thư điện tử: ','All news, articles, inquiries should be sent to e-mail:') ?><br> Email: icco.greencoffee@gmail.com<br><br>
                <?= UserHelper::multilanguage('Hoặc theo địa chỉ Website: Ban Quản trị nội dung','By address or Website: Content Management Board Posts and Telecommunications Institute of Technology') ?><br>
                <?= UserHelper::multilanguage("Địa chỉ: 122 Hoàng Quốc Việt, Cầu Giấy, Hà Nội<br> ĐT: (+84) 901 775 939",'Address: 122 Hoang Quoc Vietnam, Cau Giay, Ha Noi <br> Telephone: (+84) 901 775 939') ?><br>

            </div>
            <div class="copy-right" style="padding-left: 50px;">
                <h5><?= UserHelper::multilanguage('THÔNG TIN ĐỐI TÁC','Partner Information') ?></h5>
                <p>Nguồn thông tin thị trường: Viện chính sách chiến lược phát triển Nông nghiệp nông thôn (IPSARD)</p>
               <p>Nguồn thông tin kỹ thuật canh tác: Tổ chức chứng nhận UTZ</p>
                Nguồn thông tin thời tiết: Viện Quy hoạch và Dự báo Nông nghiệp (NIAPP)<br>
                Trung Tâm Phát Triển Cộng Đồng - CDC<br>

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