<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "weather_detail".
 *
 * @property integer $id
 * @property string $station_code
 * @property integer $precipitation
 * @property integer $tmax
 * @property integer $tmin
 * @property integer $wnddir
 * @property integer $wndspd
 * @property integer $station_id
 * @property integer $timestamp
 * @property integer $created_at
 * @property integer $updated_at
 */
class WeatherDetail extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */

    public $station_name;
    public $wndspd_km_h;
    public $content;
    public $t_average;
    public $province_name;
    public $precipitation_unit;
    public $image;


    public static function tableName()
    {
        return 'weather_detail';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['precipitation', 'tmax', 'tmin', 'wnddir', 'wndspd', 'station_id', 'timestamp', 'created_at', 'updated_at'], 'integer'],
            [['station_code','content'], 'string', 'max' => 45],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'station_code' => 'Station Code',
            'precipitation' => 'Precipitation',
            'tmax' => 'Tmax',
            'tmin' => 'Tmin',
            'wnddir' => 'Wnddir',
            'wndspd' => 'Wndspd',
            'station_id' => 'Station ID',
            'timestamp' => 'Timestamp',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
