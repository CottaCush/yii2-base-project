<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\assets\AdminAsset;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

AdminAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) . ' &ndash; ' . Yii::$app->name; ?></title>
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

    <?php $this->head() ?>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <link rel="shortcut icon" href="/favicon.png" type="image/png">
</head>
<body class="hold-transition card-page">
<?php $this->beginBody() ?>

<div class="card-box">
    <div class="card-header">
        <?= Html::a(Html::img(['/admin-assets/img/logo.png']), ['/admin/'], ['class' => 'card-logo']); ?>
    </div>
    <div class="card-box-body">
        <?= $content ?>
    </div>
    <div class="card-box-footer">
        <?= ArrayHelper::getValue($this->params, 'card-footer', ''); ?>
    </div>
</div>

<?= $this->context->showFlashMessages(); ?>
<?php $this->endBody(); ?>
</body>
</html>
<?php $this->endPage();
