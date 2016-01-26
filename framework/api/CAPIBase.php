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

    protected $returnData;

    /**
     * @var Exception
     */
    protected $exception;

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
                    if ($vInstance->hasError()) {
                        throw new CHttpException(500, $vInstance->error()['errmsg'], $vInstance->error()['errno']);
                    }
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
        $this->validate();
    }

    public function afterRun() {

    }

    abstract public function run();

}
 
 

 