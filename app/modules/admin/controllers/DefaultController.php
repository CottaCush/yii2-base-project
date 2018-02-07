<?php

namespace app\modules\admin\controllers;

class DefaultController extends BaseAdminController
{
    public function actionIndex()
    {
        return $this->render('index');
    }
}
