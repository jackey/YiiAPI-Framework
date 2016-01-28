<?php
/**
 * File CAPIBase.php
 * @author jackeychen <jackey@fumer.cn>
 * @link http://yii-api.fumer.cn
 * @copyright 2016-2016 Fumer Software LLC
 * @license ??
 * @package yii_api
 * @since 1.0
 */

abstract class CAPIBase {

    const STATUS_APPID_NOT_FOUND = 504;
    const STATUS_SIGN_REQUIRED = 505;
    const STATUS_SIGN_NOT_MATCH = 506;
    const STATUS_TIMESTAMP_REQUIRED = 507;

    protected $returnData;

    /**
     * @var Exception
     */
    protected $exception;

    /**
     * @var CAPIApplication
     */
    protected $application;

    /**
     * @var CHttpRequest
     */
    private $request;

    public function init() {}

    public function rules() {}

    public function validate() {
        $rules = $this->rules();
        if (!empty($rules) && is_array($rules)) {
            foreach ($rules as $pname => $validators) {
                $value = $this->request->getParam($pname, $validators['D']);
                foreach ($validators['V'] as $vName => $config) {
                    $fVNmame = "A{$vName}Validator";
                    $vInstance = new $fVNmame($config);
                    $vInstance->validate($value);
                    if ($vInstance->hasError())
                        throw new CHttpException(500, $vInstance->error()['errmsg'], $vInstance->error()['errno']);

                }
            }
        }
    }

    public function setReturnData($data) {
        $this->returnData = $data;
    }

    public function getReturnData() {
        return $this->returnData;
    }

    public function handleRequest() {
        try {
            // Let output from API to debug data
            ob_start();

            $this->init();

            $this->beforeRun();

            $this->returnData = $this->run();
        }
        catch(Exception $e) {
            $this->exception = $e;
        }

        $this->afterRun();
        ob_end_clean();
    }

    public function beforeRun() {
        $this->request = Yii::app()->getRequest();
        $this->application = Yii::app();
        $this->validate();

        $this->checkSign();
    }

    protected function checkSign() {
        $keys = $this->application->app_keys;
        $clientAppId = $this->request->getParam('app_id');
        foreach ($keys as $app_key) {
            if ($app_key['app_id'] == $clientAppId)
                $clientAppSecret = $app_key['app_secret'];
        }

        if (!$this->request->getParam('timestamp'))
            throw new CAPIException(500, 'Sorry, the timestamp param is required', self::STATUS_TIMESTAMP_REQUIRED);

        if (empty($clientAppSecret))
            throw new CAPIException(500, "Sorry, the app id {$clientAppId} is missed or  not found", self::STATUS_APPID_NOT_FOUND);

        if ($this->request->isPostRequest
            || $this->request->isPutRequest)  $params = $_POST;
        else $params = $_GET;

        $clientSign = $this->request->getParam('sign');
        if (empty($clientSign))
            throw new CAPIException(500, 'Sorry, the sign is required', self::STATUS_SIGN_REQUIRED);

        if ($clientSign != $this->makeSign($clientAppSecret, $params))
            throw new CAPIException(500, 'Sorry, the sign is not matched. ', self::STATUS_SIGN_NOT_MATCH);


    }

    public function makeSign($appSecret, $params) {
        $appId = $params['app_id'];

        // Sort params with key name
        ksort($params);
        $string = '';
        foreach ($params as $name => $param) {
            if ($param == 'app_id'
                || is_array($param)
                || $name = 'sign')  continue;
            $string .=  $name .'='. $param.'&';
        }
        $string = trim($string, '&')."&secret=".$appSecret;
        $string = md5($string);

        return strtoupper($string);
    }

    public function afterRun() {

    }

    abstract public function run();

}
 
 

 