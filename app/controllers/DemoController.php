<?php

namespace app\controllers;

use CottaCush\Yii2\File\ObjectStorageComponent;
use CottaCush\Yii2\File\ObjectStorageException;
use Yii;
use yii\web\UploadedFile;

/**
 * Class DemoController
 * @author Adeyemi Olaoye <yemi@cottacush.com>
 * @package app\controllers
 */
class DemoController extends BaseController
{
    public function actionDropZone()
    {
        return $this->render('dropzone');
    }

    public function actionDropZoneUpload()
    {
        $uploadedFile = UploadedFile::getInstanceByName('dropzoneFile');
        if (!$uploadedFile) {
            return $this->sendErrorResponse('No image uploaded', 'error', 400);
        }

        /** @var ObjectStorageComponent $objectStorage */
        $objectStorage = Yii::$app->get('objectStorage');

        try {
            $url = $objectStorage->storeUploadedFile($uploadedFile);
            return $this->sendSuccessResponse(['url' => $url]);
        } catch (ObjectStorageException $objectStorageException) {
            return $this->sendErrorResponse($objectStorageException->getMessage(), 'error', 500);
        }
    }
}
