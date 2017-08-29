<?php

namespace app\controllers;

use CottaCush\Yii2\Action\ObjectStorageUploadAction;

/**
 * Class DemoController
 * @author Adeyemi Olaoye <yemi@cottacush.com>
 * @package app\controllers
 */
class DemoController extends BaseController
{
    public function actions()
    {
        return [
            'drop-zone-upload' => [
                'class' => ObjectStorageUploadAction::class,
                'uploadedFileName' => 'dropzoneFile',
                'objectStorageFileName' => 'dropzonetestfile.jpg',
                'objectStoragePath' => 'dropzone'
            ]
        ];
    }

    public function actionDropZone()
    {
        return $this->render('dropzone');
    }
}
