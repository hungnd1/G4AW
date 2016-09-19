<?php
namespace common\helpers;
/**
 * This is the helper class for you to have api-specific stuff in WebApplication instance.
 * Note that you might want to describe your application components
 * using `@property` declarations in this docblock.
 *
 * @package YiiBoilerplate\Api
 */
class ResMessage
{
    const TRO_GIUP = 'TRO_GIUP';
    const LOI_SAI_CU_PHAP = 'LOI_SAI_CU_PHAP';
    const CHUA_DANG_KY_DICH_VU = 'CHUA_DANG_KY_DICH_VU';
    const DA_DANG_KY_DICH_VU = 'DA_DANG_KY_DICH_VU';
    const LAY_MAT_KHAU = 'LAY_MAT_KHAU';
    const TAI_APP_CLIENT = 'TAI_APP_CLIENT';
    const DANG_KY_LAN_DAU_THANH_CONG = 'DANG_KY_LAN_DAU_THANH_CONG';
    const DANG_KY_LAN_HAI_THANH_CONG = 'DANG_KY_LAN_HAI_THANH_CONG';
    const DANG_KY_LAI_TRONG_CHU_KY_KM = 'DANG_KY_LAI_TRONG_CHU_KY_KM';
    const DANG_KY_THIEU_TIEN = 'DANG_KY_THIEU_TIEN';
    const DANG_KY_ROI_DANG_KY_LAI_DICH_VU = 'DANG_KY_ROI_DANG_KY_LAI_DICH_VU';
    const LOI_HE_THONG = 'LOI_HE_THONG';
    const HUY_DO_GIA_HAN_LOI = 'HUY_DO_GIA_HAN_LOI';
    const HUY_THANH_CONG = 'HUY_THANH_CONG';
    const HUY_KHI_CHUA_DANG_KY = 'HUY_KHI_CHUA_DANG_KY';

    public static  $list_mt = [
        self::CHUA_DANG_KY_DICH_VU => 'CHUA_DANG_KY_DICH_VU',
        self::DA_DANG_KY_DICH_VU => 'DA_DANG_KY_DICH_VU',
        self::DANG_KY_LAN_DAU_THANH_CONG => 'DANG_KY_LAN_DAU_THANH_CONG',
        self::DANG_KY_LAN_HAI_THANH_CONG => 'DANG_KY_LAN_HAI_THANH_CONG',
        self::DANG_KY_LAI_TRONG_CHU_KY_KM => 'DANG_KY_LAI_TRONG_CHU_KY_KM',
        self::DANG_KY_THIEU_TIEN => 'DANG_KY_THIEU_TIEN',
        self::DANG_KY_ROI_DANG_KY_LAI_DICH_VU => 'DANG_KY_ROI_DANG_KY_LAI_DICH_VU',
        self::HUY_DO_GIA_HAN_LOI => 'HUY_DO_GIA_HAN_LOI',
        self::HUY_THANH_CONG => 'HUY_THANH_CONG',
        self::HUY_KHI_CHUA_DANG_KY => 'HUY_KHI_CHUA_DANG_KY',
        self::LAY_MAT_KHAU => 'LAY_MAT_KHAU',
        self::TAI_APP_CLIENT => 'TAI_APP_CLIENT',
        self::TRO_GIUP => 'TRO_GIUP',
        self::LOI_SAI_CU_PHAP => 'LOI_SAI_CU_PHAP',
        self::LOI_HE_THONG => 'LOI_HE_THONG',
    ];
}