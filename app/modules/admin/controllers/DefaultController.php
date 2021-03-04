<?php

namespace app\modules\admin\controllers;

class DefaultController extends BaseAdminController
{
    public function actionIndex(): string
    {
        return $this->render('index');
    }
}
