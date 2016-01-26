<?php
/**
 * File index.php
 * @author jackeychen <jackey@fumer.cn>
 * @link http://yii-api.fumer.cn
 * @copyright 2016-2016 Fumer Software LLC
 * @license ??
 * @package yii_api
 * @since 1.0
 */

define('ROOT', dirname(__FILE__));

$yii_api = ROOT.'/../../framework/api/CYiiAPI.php';
$config = ROOT.'/protected/config/main.php';

require_once $yii_api;
YiiAPI::createAPIApplication($config)->run();
