<?php
/**
 * Created by PhpStorm.
 * User: Hoan
 * Date: 12/21/2015
 * Time: 9:51 AM
 */

namespace console\controllers;


use common\models\Province;
use yii\console\Controller;

class ProvinceController extends Controller
{
    public function actionImport()
    {
        $arr = ['AN GIANG      ',
            'BINH PHUOC    ',
            'CAO BANG    ',
            'DAK NONG      ',
            'DAK LAK      ',
            'DIEN BIEN     ',
            'DONG THAP     ',
            'GIA LAI     ',
            'HA GIANG      ',
            'HA TINH      ',
            'KIEN GIANG   ',
            'KOM TUM    ',
            'LAI CHAU    ',
            'LANG SON     ',
            'LAO CAI      ',
            'LONG AN      ',
            'NGHE AN       ',
            'QUANG BINH   ',
            'QUANG NAM    ',
            'QUANG NINH    ',
            'QUANG TRI    ',
            'SON LA     ',
            'TAY NINH      ',
            'THANH HOA     ',
            'THUA THIEN HUE'
        ];

        $arr2 = ['An Giang      ',
            'Bình Phước    ',
            'Cao Bằng      ',
            'Đăk Nông      ',
            'Đắk Lắk       ',
            'Điện Biên     ',
            'Đồng Tháp     ',
            'Gia Lai       ',
            'Hà Giang      ',
            'Hà Tĩnh       ',
            'Kiên Giang    ',
            'Kon Tum       ',
            'Lai Châu      ',
            'Lạng Sơn      ',
            'Lào Cai       ',
            'Long An       ',
            'Nghệ An       ',
            'Quảng Bình    ',
            'Quảng Nam     ',
            'Quảng Ninh    ',
            'Quảng Trị     ',
            'Sơn La        ',
            'Tây Ninh      ',
            'Thanh Hóa     ',
            'Thừa Thiên Huế'
        ];

        $i = 0;
        foreach ($arr as $item) {
            $province = Province::findOne(['name' => trim($item)]);
            if (!$province){
                $province = new Province();
                $province->name = trim($item);
                $province->display_name = trim($arr2[$i]);
                $province->status = 10;
                $province->created_at = time();
                $province->updated_at = time();
//                $province->display_name = trim($item);
                $province->save();
            }
            $i++;
        }
    }
} 