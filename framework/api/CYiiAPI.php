<?php
/**
 * File CAPIYii.php
 * @author jackeychen <jackey@fumer.cn>
 * @link http://yii-api.fumer.cn
 * @copyright 2016-2016 Fumer Software LLC
 * @license ??
 * @package yii_api
 * @since 1.0
 */

if(!class_exists('YiiBase', false))
    require(dirname(__FILE__).'/../YiiBase.php');

if (!class_exists('CAPIApplication', false))
    require(dirname(__FILE__)).'/CAPIApplication.php';

class YiiAPI extends YiiBase {

    public static function createAPIApplication($config = null) {
        return self::createApplication('CAPIApplication', $config);
    }

    private static function classMapping() {
        $classMapping = array(
            'CAPIException' => dirname(__FILE__).'/CAPIException.php',
            'CAPIApplication' => dirname(__FILE__).'/CAPIApplication.php',
            'CAPIBase' => dirname(__FILE__).'/CAPIBase.php'
        );

        return $classMapping;
    }

    public static function autoload($class) {
        $mapping = self::classMapping();
        if (array_key_exists($class, $mapping))
            include $mapping[$class];

    }
}
spl_autoload_register(array('YiiAPI', 'autoload'), true, true);

 