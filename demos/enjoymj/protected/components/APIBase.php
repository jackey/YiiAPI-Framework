<?php
/**
 * File APIBase.php
 * @author jackeychen <jackey@fumer.cn>
 * @link http://yii-api.fumer.cn
 * @copyright 2016-2016 Fumer Software LLC
 * @license ??
 * @package yii_api
 * @since 1.0
 */

abstract class APIBase extends CAPIBase {

    public function afterRun() {
        parent::afterRun();

        $data = $this->getReturnData();
        $output = ob_get_contents();

        if ($this->exception) {
            $this->setReturnData(array(
                'code' => $this->exception->getCode(),
                'message' => $this->exception->getMessage(),
                'data' => '',
                'debug' => $output
            ));
        }
        else {
            $this->setReturnData(array(
                'code' => 200,
                'message' => 'success',
                'data' => $data,
                'debug' => $output
            ));
        }
    }
}
 
 

 