<?php
/**
 * File CAPIApplication.php
 * @author jackeychen <jackey@fumer.cn>
 * @link http://yii-api.fumer.cn
 * @copyright 2016-2016 Fumer Software LLC
 * @license ??
 * @package yii_api
 * @since 1.0
 */

class CAPIApplication extends CApplication {

    public $defaultAPIPoint = "";

    public function processRequest() {
        $request = $this->getRequest();
        $uri = $request->getUrl();
        if ($uri == '/' || empty($uri)) $apiPoint = $this->getDefaultAPIPoint();
        else $apiPoint = substr($uri, 1);

        if (strpos($apiPoint, '.') !== false)
            $apiParts = explode('.', $apiPoint);
        else
            throw new CAPIException('sorry, the uri of api only supports dot model ( like user.info ). please read the document to get more details.');

        print_r($apiParts);
    }

    public function getDefaultAPIPoint() {
        if ($this->defaultAPIPoint) return $this->defaultAPIPoint;
        return 'home.index';
    }
}
 