<?php
/**
 *
 * @author Nguyen Chi Thuc
 */

namespace common\helpers;

use common\models\VideoStream;
use Mobile_Detect;

class StreamingHelper {
    const TYPE_TRAILER = 1;
    const TYPE_VIDEO = 2;

    const QUALITY_LOW = 1;
    const QUALITY_NORMAL = 2;
    const QUALITY_HIGH = 3;
    const QUALITY_HD = 4;

    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;
    const STATUS_TEST = 3;

    const STREAMING_HTTP = 0;
    const STREAMING_RTSP = 1;
    const STREAMING_HLS = 2;
    const STREAMING_RTMP = 3;
    const STREAMING_MMS = 4;

    public static function getSupportedStreamingProtocol() {
        $detector = new Mobile_Detect();
        if($detector->isMobile() || $detector->isTablet()){
            if ($detector->is('AndroidOS')) {
                if ($detector->version('Android') < 3.0) {
                    return self::STREAMING_RTSP;
                } else {
                    return self::STREAMING_HLS;
                }
            } else if ($detector->is('SymbianOS')) {
                return self::STREAMING_RTSP; // rtsp
            } else if ($detector->is('iOS')) {
                return self::STREAMING_HLS; // hls
            } else if ($detector->is('WindowsMobileOS')) {
                return self::STREAMING_HTTP;
            } else {
                return self::STREAMING_HTTP; // default http
            }
        }else{
            return self::STREAMING_HLS;
        }
    }

}

?>
