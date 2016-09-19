<?php
/**
 * Created by PhpStorm.
 * User: qhuy
 * Date: 18/12/2014
 * Time: 21:59
 */

namespace backend\models;


use common\helpers\CommonUtils;
use common\helpers\CVietnameseTools;
use common\helpers\MediaToolBoxHelper;
use common\helpers\MyCurl;
use common\models\Content;
use common\models\News;
use garyjl\simplehtmldom\SimpleHtmlDom;
use Imagine\Image\Box;
use Imagine\Image\ManipulatorInterface;
use Yii;
use yii\base\Model;
use yii\helpers\FileHelper;
use yii\helpers\Url;
use yii\validators\ImageValidator;
use yii\validators\UrlValidator;
use yii\web\UploadedFile;

class Image extends Model
{
    const TYPE_POSTER = 1;
    const TYPE_THUMBNAIL = 2;
    const TYPE_SLIDESHOW = 3;
    const TYPE_AUTO = 4;

    const ORIENTATION_PORTRAIT = 1;
    const ORIENTATION_LANDSCAPE = 2;

    const RATIO_W = 16;
    const RATIO_H = 9;
    const RATIO_MULIPLIER_UPPER_LIMIT = 120;//Ty le toi da limit (1920x1080)

    public static $imageConfig = null;
    private $_id = '';
    public $content_id;
    public $name;
    public $url;
    public $type = self::TYPE_POSTER;
    public $orientation;

    public static $image_types = [
        self::TYPE_POSTER => 'poster',
        self::TYPE_SLIDESHOW => 'slide show',
        self::TYPE_THUMBNAIL => 'thumbnail',
        self::TYPE_AUTO => 'auto'
    ];

    public static $image_orient = [
        self::ORIENTATION_LANDSCAPE => 'landscape',
        self::ORIENTATION_PORTRAIT => 'portrait'
    ];

    /**
     * @var $image UploadedFile
     */
    public $image;

    /**
     * Create snapshot param
     * @param Content $video
     */
    private static function initParamSnapshot($video, $position)
    {
        $snapshots = [];
        $folderSave = self::createFolderImage($video->id);
        if (!$folderSave) {
            return false;
        }
        if($position <= 0 || $position > $video->duration){
            $position = rand(1, $video->duration);
        }

        $saveFileName = time() . '_' . CVietnameseTools::makeValidFileName($video->display_name . '_' . $position . '_snapshot.jpg');
        $snapshot = new Snapshot();
        $snapshot->time = $position;
        $snapshot->file_path = Yii::getAlias($folderSave) . $saveFileName;
        $snapshot->size = '100%';
        $snapshots[] = $snapshot;

        return $snapshots;
    }

    private static function toAlias($path)
    {
        $basePath = Yii::getAlias('@webroot');
        Yii::info(preg_quote($basePath, '/'));
        return preg_replace('/'.preg_quote($basePath, '/').'/', '@webroot', $path);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['image'], 'file', 'extensions' => ['jpg', 'png', 'jpeg', 'gif']],
            ['id', 'safe'],
            [['type', 'orientation'], 'integer'],
            [['url', 'name'], 'string', 'max' => 500],
        ];
    }

    public function fields()
    {
//        if ($this->scenario == static::SCENARIO_DEFAULT) {
//            return parent::fields();
//        }

        //List scenario
        $res = [
            // field name is "email", the corresponding attribute name is "email_address"
            'name',
            'url',
            'type',
            'orientation',
        ];

        return $res;
    }

    public function getId()
    {
        if (empty($this->_id)) {
            $this->_id = md5($this->url);
        }
        return $this->_id;
    }

    /**
     * Return full path url
     */
    public function getImageUrl()
    {
        $validator = new UrlValidator();
        if ($validator->validate($this->url)) {
            return $this->url;
        } else {
            if (preg_match('/(@[a-z]*)/', $this->url)) {
                return Yii::getAlias($this->url);
            } else {
                $configImage = self::getImageConfig();
                $baseUrl = isset($configImage['base_url']) ? $configImage['base_url'] : Yii::getAlias('@web');
                return $baseUrl . $this->url;
            }
        }
    }

    public function getFilePath()
    {
        $validator = new UrlValidator();
        if ($validator->validate($this->url)) {
            return null;
        } else {
            if (preg_match('/(@[a-z]*)/', $this->url)) {
                return Yii::getAlias(str_replace('@web', '@webroot', $this->url));
            } else {
                $configImage = self::getImageConfig();
                $baseUrl = isset($configImage['base_url']) ? $configImage['base_url'] : Yii::getAlias('@webroot');
                return $baseUrl . $this->url;
            }
        }
    }

    public function getWidthHeight()
    {
        if(is_file($this->getFilePath())){
            $imageSize = getimagesize($this->getFilePath());
            if ($imageSize) {
                return $imageSize[0] . 'x'. $imageSize[1];
            } else {
                return 'N/A';
            }
        }else{
            return 'File not found';
        }

    }

    public function getNameImageFullSave()
    {
        return time() . '_' . CVietnameseTools::makeValidFileName($this->name);
    }

    public static function getImageConfig()
    {
        if (Image::$imageConfig == null) {
            Image::$imageConfig = [
                'folder' => '@webroot' . DIRECTORY_SEPARATOR . Yii::getAlias('@content_images') . DIRECTORY_SEPARATOR,
                'base_url' => Yii::getAlias('@web') . '/' . Yii::getAlias('@content_images') . DIRECTORY_SEPARATOR
            ];
        }
        return Image::$imageConfig;
    }


    /**
     * Create folder to store image
     * Each store have image separate with video id
     * @return full path folder with alias (@webroot/...)
     */
    public static function createFolderImage($video_id)
    {
        $configImage = self::getImageConfig();
        $basePath = Yii::getAlias($configImage['folder']);
        if (!is_dir($basePath)) {
            FileHelper::createDirectory($basePath, 0777);
        }
        if (!is_dir($basePath) || $video_id == null) {
            Yii::error("Folder base path not exist: " . $basePath);
            return false;
        }

        $fullPath = $basePath . $video_id;
        if (!is_dir($fullPath)) {
            if (!FileHelper::createDirectory($fullPath, 0777)) {
                Yii::error("Can not create folder save image: " . $fullPath);
                return false;
            }
        }

        if (!substr($configImage['folder'], -1) == '/') {
            $configImage['folder'] .= '/';
        }
        return $configImage['folder'] . $video_id . '/';
    }

    /**
     * Thuc hien save image khi dc upload len
     * @param $content News
     * @return bool
     * @throws \yii\web\UnauthorizedHttpException
     */
    public function saveImage($content)
    {
        $video_id = $content->id;
        $folderSave = self::createFolderImage($video_id);
        if (!$folderSave) {
            $this->addError('image', 'Folder save image not found');
            return false;
        }

        if ($this->image == null) {
            $this->addError('image', "Image file not found!");
            return false;
        }

        $saveFileName = $this->getNameImageFullSave();

        $imagePath = Yii::getAlias($folderSave) . $saveFileName;
        Yii::info("Save file to " . $imagePath, 'VideoImage');
        if (!$this->image->saveAs($imagePath)) {
            $this->addError('image', 'Can not save ');
            return false;
        }
        $imageSize = getimagesize($imagePath);

        if (count($imageSize) > 0) {
            if ($imageSize[0] > $imageSize[1]) { //neu width > height
                $this->orientation = self::ORIENTATION_LANDSCAPE;
            } else {
                $this->orientation = self::ORIENTATION_PORTRAIT;
            }
        }
        $this->url = $this->resolveUrl($folderSave) . $saveFileName;

        if ($this->type == self::TYPE_AUTO) {
            $this->autoGenerateImages($content, $folderSave, $saveFileName);
        }
        return true;
    }

    /**
     * replaces '/(@[a-z]*)root/' => '$1'
     * @param string $path
     * @return string
     */
    public function resolveUrl($path)
    {
        return preg_replace('/(@[a-z]*)root/', '$1', $path);
    }

    /**
     * @param $content News
     */
    public function save($content)
    {
        return $content->addImage($this->getArray());
    }

    public function getArray()
    {
        return [
            'name' => $this->name,
            'url' => $this->url,
            'type' => $this->type,
            'orientation' => $this->orientation
        ];
    }

    public function delete()
    {
        //Delete image
        $file_path = $this->getFilePath();
        if (is_dir($file_path)) {
            return true;
        }
        if ($file_path && file_exists($file_path)) {
            return unlink($this->getFilePath());
        }

        return true;
    }

    /**
     * @param $video Content
     * @return bool
     */
    public function loadImageYt($video)
    {
        $ch = new MyCurl();
        $folderSave = self::createFolderImage($video->id);
        if (!$folderSave) {
            $this->addError('image', 'Folder save image not found');
            return false;
        }

        $yt_info = CommonUtils::getVideoYoutubeInfo($video->youtube_id);
        if ($yt_info == null || $yt_info->snippet == null || $yt_info->snippet->thumbnails == null) {
            return false;
        }
        $img_yt_url = '';
        $thumbnails = $yt_info->snippet->thumbnails;
        $img_yt_url = $this->getHighestImage($thumbnails);
        if (empty($img_yt_url)) {
            return false;
        }

        $image_extention = end(explode('.', $img_yt_url));
        $this->name = $video->display_name . '_yt.' . $image_extention;
        $this->type = self::TYPE_AUTO;
        $saveFileName = $this->getNameImageFullSave();
        $imagePath = Yii::getAlias($folderSave) . $saveFileName;
        Yii::info("Save file to " . $imagePath, 'VideoImage');
        $response = $ch->download($img_yt_url, Yii::getAlias($folderSave), $saveFileName);
        if (!$response) {
            $this->addError('image', 'Can not save ');
            return false;
        }

        $imageSize = getimagesize($imagePath);
        if (count($imageSize) > 0) {
            if ($imageSize[0] > $imageSize[1]) { //neu width > height
                $this->orientation = self::ORIENTATION_LANDSCAPE;
            } else {
                $this->orientation = self::ORIENTATION_PORTRAIT;
            }
        }
        $this->url = $this->resolveUrl($folderSave) . $saveFileName;

        $this->autoGenerateImages($video, $folderSave, $saveFileName);

        if ($yt_info->contentDetails != null) {
            $video->duration = CommonUtils::convertYtDurationToSeconds($yt_info->contentDetails->duration);
            $video->update(false);
        }

        return true;
    }

    /**
     * @param  Content $video
     * @return bool
     */
    public static function loadImageSnapshot($video, $video_file, $position = 0)
    {
        $snapshots = self::initParamSnapshot($video,$position);
        $response = MediaToolBoxHelper::getVideoSnapshot($video_file, $snapshots);
        if (!$response) {
            Yii::error('Can not get snapshot');
            return false;
        }

        foreach ($response as $snapshot) {
            $image = new Image();
            $folderSave = self::toAlias(pathinfo($snapshot->file_path, PATHINFO_DIRNAME).DIRECTORY_SEPARATOR);
            $saveFileName = pathinfo($snapshot->file_path, PATHINFO_BASENAME);
            $image->name = $saveFileName;
            $image->autoGenerateImages($video, $folderSave, $saveFileName);
        }
        return true;
    }

    /**
     * Load first image from content to create some image
     * @param $content Content
     */
    public function loadImageFromContent($content)
    {
        $ch = new MyCurl();
        $folderSave = self::createFolderImage($content->id);
        if (!$folderSave) {
            $this->addError('image', 'Folder save image not found');
            return false;
        }
        $img_content_url = '';
        $html = SimpleHtmlDom::str_get_html($content->content);
        // Get first image
        foreach($html->find('img') as $element){
            $img_content_url =  $element->src;
            if(!empty($img_content_url)) break;
        }
        if(empty($img_content_url)){
            return false;
        }

        $url_validator = new UrlValidator();
        if(!$url_validator->validate($img_content_url)){
            $img_content_url = Yii::$app->getUrlManager()->getHostInfo().$img_content_url;
        }
        Yii::info($img_content_url);

        $image_extention = end(explode('.', $img_content_url));
        $this->name = $content->display_name . '_content.' . $image_extention;
        $this->type = self::TYPE_AUTO;
        $saveFileName = $this->getNameImageFullSave();
        $imagePath = Yii::getAlias($folderSave) . $saveFileName;
        Yii::info("Save file to " . $imagePath, 'Image');

        $response = $ch->download($img_content_url, Yii::getAlias($folderSave), $saveFileName);
        if (!$response || !CommonUtils::validateImage($imagePath)) {
            $this->addError('image', 'Can not save ');
            return false;
        }
        $imageSize = getimagesize($imagePath);
        if (count($imageSize) > 0) {
            if ($imageSize[0] > $imageSize[1]) { //neu width > height
                $this->orientation = self::ORIENTATION_LANDSCAPE;
            } else {
                $this->orientation = self::ORIENTATION_PORTRAIT;
            }
        }
        $this->url = $this->resolveUrl($folderSave) . $saveFileName;

        $this->autoGenerateImages($content, $folderSave, $saveFileName);

        return true;
    }

    /**
     * Tu dong generate ra cac file dinh dang khac nhau
     * @param $video
     * @param $folderSave //Dang tuong doi example '@webroot/content_images/45/
     * @param $saveFileName
     * @return bool
     */
    public function autoGenerateImages($video, $folderSave, $saveFileName)
    {
        $imagePath = Yii::getAlias($folderSave) . $saveFileName;
        $imageSize = getimagesize($imagePath);
        $img_width = 1;
        $img_height = 1;
        if (count($imageSize) > 0) {
            $img_width = $imageSize[0];
            $img_height = $imageSize[1];
        } else {
            return false;
        }
        //Resize to slide
        $slide = $this->resizeImage($folderSave, $imagePath, $img_width, $img_height, self::TYPE_SLIDESHOW);
        if (!$slide->save($video)) {
            return false;
        }
        //Resize to poster
        $poster = $this->resizeImage($folderSave, $imagePath, $img_width, $img_height);
        if (!$poster->save($video)) {
            return false;
        };

        //Resize to thumbnail
        $poster = $this->resizeImage($folderSave, $imagePath, $img_width, $img_height, self::TYPE_THUMBNAIL);
        if (!$poster->save($video)) {
            return false;
        }

    }

    /**
     * @param $imageTool \yii\imagine\Image
     * @param $width
     * @param $heigh
     */
    private function resizeImage($folder, $filename, $width, $height, $type = self::TYPE_POSTER)
    {
        $model = new Image();
        $box_src = new Box($width, $height);
        $box = null;
        switch ($type) {
            case self::TYPE_THUMBNAIL:
                $box = new Box(320, 180);
                break;
            default:
                if (($width / $height) === (self::RATIO_W / self::RATIO_H)) {
                    $box = $box_src;
                } else {
                    list($new_width, $new_height) = $this->getNewSize($width, $height, self::RATIO_W / self::RATIO_H);
                    $box = new Box($new_width, $new_height);
                }
        }
        $imageTool = \yii\imagine\Image::getImagine()->open($filename);
        $image = $imageTool->thumbnail($box, ManipulatorInterface::THUMBNAIL_OUTBOUND);
        $model->name = self::$image_types[$type] . '_' . $this->name;
        $model->type = $type;
        $saveFileName = $model->getNameImageFullSave();

        $imagePath = Yii::getAlias($folder) . $saveFileName;
        Yii::info("Save file to " . $imagePath, 'VideoImage');
        if (!$image->save($imagePath)) {
            return null;
        }
        $imageSize = getimagesize($imagePath);
        if (count($imageSize) > 0) {
            if ($imageSize[0] > $imageSize[1]) { //neu width > height
                $model->orientation = self::ORIENTATION_LANDSCAPE;
            } else {
                $model->orientation = self::ORIENTATION_PORTRAIT;
            }
        }
        $model->url = $this->resolveUrl($folder) . $saveFileName;

        return $model;
    }

    private function getHighestImage($thumbnails)
    {
        if ($thumbnails->maxres != null) {
            return $thumbnails->maxres->url;
        }
        if ($thumbnails->standard != null) {
            return $thumbnails->standard->url;
        }
        if ($thumbnails->high != null) {
            return $thumbnails->high->url;
        }
        if ($thumbnails->medium != null) {
            return $thumbnails->medium->url;
        }
        if ($thumbnails->default != null) {
            return $thumbnails->default->url;
        }
    }

    private function getNewSize($width, $height, $ratio)
    {
        // Find closest ratio multiple to image size
        if ($width > $height) {
            // landscape
            $ratioMultiple = round($height / self::RATIO_H, 0, PHP_ROUND_HALF_DOWN);
        } else {
            // portrait
            $ratioMultiple = round($width / self::RATIO_W, 0, PHP_ROUND_HALF_DOWN);
        }

        $newWidth = $ratioMultiple * self::RATIO_W;
        $newHeight = $ratioMultiple * self::RATIO_H;

        if ($newWidth > self::RATIO_W * self::RATIO_MULIPLIER_UPPER_LIMIT || $newHeight > self::RATIO_H * self::RATIO_MULIPLIER_UPPER_LIMIT) {
            // File is larger than upper limit
            $ratioMultiple = self::RATIO_MULIPLIER_UPPER_LIMIT;
        }

        $this->tweakMultiplier($ratioMultiple, $width, $height);

        $newWidth = $ratioMultiple * self::RATIO_W;
        $newHeight = $ratioMultiple * self::RATIO_H;
        return [
            $newWidth,
            $newHeight
        ];
    }

    /**
     * Xac dinh ratio sao cho new_width, new_height kho qua kich thuoc anh that
     * @param $ratioMultiple
     * @param $fitInsideWidth
     * @param $fitInsideHeight
     */
    protected function tweakMultiplier(&$ratioMultiple, $fitInsideWidth, $fitInsideHeight)
    {
        $newWidth = $ratioMultiple * self::RATIO_W;
        $newHeight = $ratioMultiple * self::RATIO_H;

        if ($newWidth > $fitInsideWidth || $newHeight > $fitInsideHeight) {
            $ratioMultiple--;
            $this->tweakMultiplier($ratioMultiple, $fitInsideWidth, $fitInsideHeight);
        } else {
            return;
        }
    }

}