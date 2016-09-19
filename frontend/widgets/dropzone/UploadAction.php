<?php

namespace frontend\widgets\dropzone;

use Yii;
use yii\base\Action;
use yii\helpers\Json;
use yii\web\HttpException;
use yii\web\UploadedFile;

class UploadAction extends \devgroup\dropzone\UploadAction
{
    public $fileName = 'file';
    public $upload = 'images';

    public $afterUploadHandler = null;
    public $afterUploadData = null;

    protected $uploadDir = '';
    protected $uploadSrc = '';

    public function init()
    {
        parent::init();

        $this->uploadDir = Yii::getAlias('@webroot/uploads/' . $this->upload . '/');
        $this->uploadSrc = Yii::getAlias('@web/uploads/' . $this->upload . '/');
    }

    public function setUpload($upload)
    {
        $this->upload = $upload;

        $this->uploadDir = Yii::getAlias('@webroot/' . $this->upload . '/');
        $this->uploadSrc = Yii::getAlias('@web/' . $this->upload . '/');
    }

    public function run()
    {
        $file = UploadedFile::getInstanceByName($this->fileName);
        if ($file->hasError) {
            throw new HttpException(500, 'Upload error');
        }

         $fileName = uniqid() . '.' . $file->extension;

        $file->saveAs($this->uploadDir . $fileName);

        $response = [
            'filename' => $fileName,
        ];

        if (isset($this->afterUploadHandler)) {
            $data = [
                'data' => $this->afterUploadData,
                'file' => $file,
                'dirName' => $this->uploadDir,
                'src' => $this->uploadSrc,
                'filename' => $fileName,
                'params' => Yii::$app->request->post(),
            ];

            if ($result = call_user_func($this->afterUploadHandler, $data)) {
                $response['afterUpload'] = $result;
            }
        }

        return Json::encode($response);
    }
}
