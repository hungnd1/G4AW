<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 10/8/2016
 * Time: 9:46 AM
 */
use common\models\User;
?>
<div class="content">
    <div class="container">
        <div class="cr-page-link">
            <a href="">Trang chủ</a>
            <span>/</span>
            <a href="">Cá nhân</a>
        </div>
    </div>
    <div class="container">
        <div class="main-cm-1">
            <div class="left-cn hidden-xs hidden-sm">
                <div class="block-cm-left top-cn-left">
                    <a href="" class="bt-edit"><i class="fa fa-pencil"></i></a>
                    <img src="img/avt_df.png"><br>
                    <h4>Phạm Văn A</h4>
                    <p>Cầu Giấy, Hà Nội</p>
                </div>
                <div class="block-cm-left">
                    <span class="t-span">Số điện thoại</span><br>
                    <span class="b-span">0988 889 889</span>
                </div>
                <div class="block-cm-left">
                    <span class="t-span">Email</span><br>
                    <span class="b-span">example@gmail.com</span>
                </div>
                <div class="block-cm-left">
                    <span class="t-span">Giới tính</span><br>
                    <span class="b-span">Nam</span>
                </div>
                <div class="block-cm-left">
                    <span class="t-span">Tuổi</span><br>
                    <span class="b-span">32</span>
                </div>
            </div>
            <div class="right-cn">
                <div class="creat-cp">
                    <h4>Bạn có nhu cầu cần hỗ trợ?</h4>
                    <a href="">ĐĂNG KÝ NHẬN TRỢ GIÚP</a>
                </div>
                <div class="tab-ct">
                    <!-- Nav tabs -->
                    <div class="out-ul-tab ">
                        <ul class="nav nav-tabs" role="tablist">
                            <li role="presentation" class="hidden-lg hidden-md hidden-info"><a href="#t1" aria-controls="" role="tab" data-toggle="tab">Thông tin cá nhân</i></a></li>
                            <li role="presentation" class="active"><a href="#t2" aria-controls="" role="tab" data-toggle="tab">Yêu cầu của tôi</i></a></li>
                            <li role="presentation"><a href="#t3" aria-controls="" role="tab" data-toggle="tab">Chiến dịch của tôi</a></li>
                        </ul>
                    </div>
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane hidden-lg hidden-md hidden-info " id="t1">
                            <div class="block-cm-left top-cn-left">
                                <a href="" class="bt-edit"><i class="fa fa-pencil"></i></a>
                                <img src="img/avt_df.png"><br>
                                <h4>Phạm Văn A</h4>
                                <p>Cầu Giấy, Hà Nội</p>
                            </div>
                            <div class="block-cm-left">
                                <span class="t-span">Số điện thoại</span><br>
                                <span class="b-span">0988 889 889</span>
                            </div>
                            <div class="block-cm-left">
                                <span class="t-span">Email</span><br>
                                <span class="b-span">example@gmail.com</span>
                            </div>
                            <div class="block-cm-left">
                                <span class="t-span">Giới tính</span><br>
                                <span class="b-span">Nam</span>
                            </div>
                            <div class="block-cm-left">
                                <span class="t-span">Tuổi</span><br>
                                <span class="b-span">32</span>
                            </div>
                        </div>
                        <div role="tabpanel" class="tab-pane active" id="t2">
                            <div class="list-item list-tab-2">
                                <div class="out-card">
                                    <div class="card-item">
                                        <div class="thumb-common">
                                            <img src="img/blank.gif">
                                            <a href="details.html"><img class="thumb-cm" src="img/thumb/1.png"><br></a>
                                        </div>
                                        <div class="if-cm-1">
                                            <div class="top-cp">
                                                <a href="details.html"><h3 class="name-1">Chương trình từ thiện "Áo ấm cho em"</h3><br></a>
                                                <p class="des-ct-2">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Soluta ad reiciendis quidem aliquam</p>
                                            </div>
                                        </div>
                                        <div class="status-cp st-pending">
                                            Đang chờ duyệt
                                        </div>
                                    </div>
                                </div>
                                <div class="out-card">
                                    <div class="card-item">
                                        <div class="thumb-common">
                                            <img src="img/blank.gif">
                                            <a href="details.html"><img class="thumb-cm" src="img/thumb/1.png"><br></a>
                                        </div>
                                        <div class="if-cm-1">
                                            <div class="top-cp">
                                                <a href="details.html"><h3 class="name-1">Chương trình từ thiện "Áo ấm cho em"</h3><br></a>
                                                <p class="des-ct-2">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Soluta ad reiciendis quidem aliquam</p>
                                            </div>
                                        </div>
                                        <div class="status-cp st-cancel">
                                            Yêu cầu bị từ chối
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div role="tabpanel" class="tab-pane" id="t3">
                            <div class="list-item list-tab-4">
                                <div class="out-card">
                                    <div class="card-item">
                                        <div class="thumb-common">
                                            <img src="img/blank.gif">
                                            <a href="details.html"><img class="thumb-cm" src="img/thumb/1.png"><br></a>
                                        </div>
                                        <div class="if-cm-1">
                                            <div class="top-cp">
                                                <a href="details.html"><h3 class="name-1">Chương trình từ thiện "Áo ấm cho em"</h3><br></a>
                                                <p class="des-ct-2">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Soluta ad reiciendis quidem aliquam</p>
                                            </div>
                                            <div class="bt-cp">
                                                <a href="userpartner.html" class="logo-cp"><img src="img/logo-cp.png"></a>
                                                <a href="userpartner.html"><h4>VNPT Technology</h4></a>
                                                <span class="add-cp">Hà Nội</span>
                                                <div class="bar">
                                                    <div class="bar-status" style="width:70%;"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="out-card">
                                    <div class="card-item">
                                        <div class="thumb-common">
                                            <img src="img/blank.gif">
                                            <a href="details.html"><img class="thumb-cm" src="img/thumb/1.png"><br></a>
                                        </div>
                                        <div class="if-cm-1">
                                            <div class="top-cp">
                                                <a href="details.html"><h3 class="name-1">Chương trình từ thiện "Áo ấm cho em"</h3><br></a>
                                                <p class="des-ct-2">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Soluta ad reiciendis quidem aliquam</p>
                                            </div>
                                            <div class="bt-cp">
                                                <a href="userpartner.html" class="logo-cp"><img src="img/logo-cp.png"></a>
                                                <a href="userpartner.html"><h4>VNPT Technology</h4></a>
                                                <span class="add-cp">Hà Nội</span>
                                                <div class="bar">
                                                    <div class="bar-status" style="width:70%;"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="out-card">
                                    <div class="card-item">
                                        <div class="thumb-common">
                                            <img src="img/blank.gif">
                                            <a href="details.html"><img class="thumb-cm" src="img/thumb/1.png"><br></a>
                                        </div>
                                        <div class="if-cm-1">
                                            <div class="top-cp">
                                                <a href="details.html"><h3 class="name-1">Chương trình từ thiện "Áo ấm cho em"</h3><br></a>
                                                <p class="des-ct-2">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Soluta ad reiciendis quidem aliquam</p>
                                            </div>
                                            <div class="bt-cp">
                                                <a href="userpartner.html" class="logo-cp"><img src="img/logo-cp.png"></a>
                                                <a href="userpartner.html"><h4>VNPT Technology</h4></a>
                                                <span class="add-cp">Hà Nội</span>
                                                <div class="bar">
                                                    <div class="bar-status" style="width:70%;"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="out-card">
                                    <div class="card-item">
                                        <div class="thumb-common">
                                            <img src="img/blank.gif">
                                            <a href="details.html"><img class="thumb-cm" src="img/thumb/1.png"><br></a>
                                        </div>
                                        <div class="if-cm-1">
                                            <div class="top-cp">
                                                <a href="details.html"><h3 class="name-1">Chương trình từ thiện "Áo ấm cho em"</h3><br></a>
                                                <p class="des-ct-2">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Soluta ad reiciendis quidem aliquam</p>
                                            </div>
                                            <div class="bt-cp">
                                                <a href="userpartner.html" class="logo-cp"><img src="img/logo-cp.png"></a>
                                                <a href="userpartner.html"><h4>VNPT Technology</h4></a>
                                                <span class="add-cp">Hà Nội</span>
                                                <div class="bar">
                                                    <div class="bar-status" style="width:70%;"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="out-card">
                                    <div class="card-item">
                                        <div class="thumb-common">
                                            <img src="img/blank.gif">
                                            <a href="details.html"><img class="thumb-cm" src="img/thumb/1.png"><br></a>
                                        </div>
                                        <div class="if-cm-1">
                                            <div class="top-cp">
                                                <a href="details.html"><h3 class="name-1">Chương trình từ thiện "Áo ấm cho em"</h3><br></a>
                                                <p class="des-ct-2">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Soluta ad reiciendis quidem aliquam</p>
                                            </div>
                                            <div class="bt-cp">
                                                <a href="userpartner.html" class="logo-cp"><img src="img/logo-cp.png"></a>
                                                <a href="userpartner.html"><h4>VNPT Technology</h4></a>
                                                <span class="add-cp">Hà Nội</span>
                                                <div class="bar">
                                                    <div class="bar-status" style="width:70%;"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end content -->