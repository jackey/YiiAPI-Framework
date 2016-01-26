<?php
/**
 * File AValidator.php
 * @author jackeychen <jackey@fumer.cn>
 * @link http://yii-api.fumer.cn
 * @copyright 2016-2016 Fumer Software LLC
 * @license ??
 * @package yii_api
 * @since 1.0
 */
 
 abstract class AValidator {
     protected $errno;
     protected $errmsg;
     protected $config;

     public function __construct($config) {
         $this->config = $config;
     }

     public abstract function validate($value);

     public function hasError() {
         return !!$this->errno;
     }

     public function error() {
         return array(
             'errno' => $this->errno,
             'errmsg' => $this->errmsg
         );
     }

     public function config($name, $default = '') {
         if (isset($this->config[$name])) return $this->config[$name];
         return $default;
     }
 }