<?php

namespace app\controllers;

use Lukasoppermann\Httpstatus\Httpstatus;
use Yii;
use CottaCush\Yii2\Controller\BaseController as UtilsController;
use yii\base\Action;
use yii\base\InvalidConfigException;
use yii\base\Security;
use yii\console\Request;
use yii\helpers\Html;
use yii\web\Response;
use yii\web\Session;
use yii\web\User;

/**
 * Class BaseController
 * @package app\controllers
 * @author Adegoke Obasa <goke@cottacush.com>
 */
class BaseController extends UtilsController
{
    /**
     * @var Httpstatus $httpStatuses
     */
    protected $httpStatuses;

    public function init()
    {
        parent::init();
        $this->httpStatuses = new Httpstatus();
    }

    /**
     * Executed after action
     * @param Action $action
     * @param mixed $result
     * @return mixed
     * @author Adegoke Obasa <goke@cottacush.com>
     */
    public function afterAction($action, mixed $result): mixed
    {
        $result = parent::afterAction($action, $result);
        $this->setSecurityHeaders();

        /**
         * Set Current Transaction in New Relic
         * @author Adegoke Obasa <goke@cottacush.com>
         */
        if (extension_loaded('newrelic')) {
            newrelic_name_transaction($action->controller->id . '/' . $action->id);
        }
        return $result;
    }

    /**
     * Set Headers to prevent Click-jacking and XSS
     * @author Adegoke Obasa <goke@cottacush.com>
     */
    private function setSecurityHeaders()
    {
        $headers = Yii::$app->response->headers;
        $headers->add('X-Frame-Options', 'DENY');
        $headers->add('X-XSS-Protection', '1');
    }

    /**
     * Allow sending success response
     * @author Oluwarotimi Akintewe <akintewe.rotimi@gmail.com>
     * @author Adegoke Obasa <goke@cottacush.com>
     * @param $data
     * @return array
     */
    public function sendSuccessResponse($data): array
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        Yii::$app->response->setStatusCode(200, $this->httpStatuses->getReasonPhrase(200));

        return [
            'status' => 'success',
            'data' => $data
        ];
    }

    /**
     * Allows sending error response
     * @author Oluwarotimi Akintewe <akintewe.rotimi@gmail.com>
     * @author Adegoke Obasa <goke@cottacush.com>
     * @param $message
     * @param $code
     * @param $httpStatusCode
     * @param null $data
     * @return array
     */
    public function sendErrorResponse($message, $code, $httpStatusCode, $data = null): array
    {

        Yii::$app->response->format = Response::FORMAT_JSON;
        Yii::$app->response->setStatusCode($httpStatusCode, $this->httpStatuses->getReasonPhrase($httpStatusCode));

        $response = [
            'status' => 'error',
            'message' => $message,
            'code' => $code
        ];

        if (!is_null($data)) {
            $response["data"] = $data;
        }

        return $response;
    }

    /**
     * Sends fail response
     * @param $data
     * @author Oluwarotimi Akintewe <akintewe.rotimi@gmail.com>
     * @author Adegoke Obasa <goke@cottacush.com>
     * @param int $httpStatusCode
     * @return array
     */
    public function sendFailResponse($data, $httpStatusCode = 500): array
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        Yii::$app->response->setStatusCode($httpStatusCode, $this->httpStatuses->getReasonPhrase($httpStatusCode));

        return [
            'status' => 'fail',
            'data' => $data
        ];
    }

    /**
     * This flashes error message and sends to the view
     * @author Adegoke Obasa <goke@cottacush.com>
     * @param $message
     */
    public function flashError($message)
    {
        Yii::$app->session->setFlash('danger', $message);
    }

    /**
     * This flashes success message and sends to the view
     * @author Adegoke Obasa <goke@cottacush.com>
     * @param $message
     */
    public function flashSuccess($message)
    {
        Yii::$app->session->setFlash('success', $message);
    }

    /**
     * Get Yii2 request object
     * @author Adegoke Obasa <goke@cottacush.com>
     * @return Request|\yii\web\Request
     */
    public function getRequest(): \yii\web\Request|Request
    {
        return Yii::$app->request;
    }

    /**
     * Get Yii2 response object
     * @author Adegoke Obasa <goke@cottacush.com>
     * @return Request|Response
     */
    public function getResponse(): Response|Request
    {
        return Yii::$app->response;
    }

    /**
     * Get Yii2 session object
     * @return mixed
     * @author Adegoke Obasa <goke@cottacush.com>
     */
    public function getSession(): mixed
    {
        return Yii::$app->session;
    }

    /**
     * Get Yii2 security object
     * @author Adegoke Obasa <goke@cottacush.com>
     * @return Security
     */
    public function getSecurity(): Security
    {
        return Yii::$app->security;
    }

    /**
     * Get web user
     * @author Adegoke Obasa <goke@cottacush.com>
     */
    public function getUser()
    {
        return Yii::$app->user;
    }

    /**
     * show flash messages
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param bool $sticky
     * @return string
     */
    public function showFlashMessages($sticky = false): string
    {
        $timeout = $sticky ? 0 : 5000;
        $flashMessages = [];
        $allMessages = $this->getSession()->getAllFlashes();
        foreach ($allMessages as $key => $message) {
            if (is_array($message)) {
                $message = $this->mergeFlashMessages($message);
            }
            $flashMessages[] = [
                'message' => $message,
                'type' => $key,
                'timeout' => $timeout
            ];
        }
        $this->getSession()->removeAllFlashes();
        return Html::script('var notifications =' . json_encode($flashMessages));
    }

    /**
     * Returns the user for the current module
     * @author Adegoke Obasa <goke@cottacush.com>
     * @return User null|object
     * @throws InvalidConfigException
     */
    public function getModuleUser(): User
    {
        return $this->module->get('user');
    }

    /**
     * @return int
     * @throws InvalidConfigException
     * @author Taiwo Ladipo <taiwo.ladipo@cottacush.com>
     */
    public function getUserId(): int
    {
        return $this->getModuleUser()->id;
    }
}
