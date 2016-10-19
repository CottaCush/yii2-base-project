<?php
/**
 * Bootstrap File
 * @author Adegoke Obasa <goke@cottacush.com>
 */

require(__DIR__ . '/../../vendor/autoload.php');

// Load Environment Variables

if (getenv('APPLICATION_ENV') != 'production') {
    $dotenv = new Dotenv\Dotenv(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'env');
    $dotenv->load();
}

// Setup application environment
$envs = ['development', 'staging', 'production'];
$env = getenv("APPLICATION_ENV");

if (!in_array($env, $envs)) {
    die("Environment is not valid");
}

// Only show debug toolbar and allow gii when in development
if ($env == "development") {
    defined('YII_DEBUG') or define('YII_DEBUG', true);
    defined('YII_ENV') or define('YII_ENV', 'dev');
}

require(__DIR__ . '/../../vendor/yiisoft/yii2/Yii.php');

// Require configuration file
$config = require(__DIR__ . "/../config/web.php");


// Service Dependencies
require(__DIR__ . "/../config/services.php");

// Change this to your timezone
ini_set('date.timezone', 'Africa/Lagos');
(new yii\web\Application($config))->run();
