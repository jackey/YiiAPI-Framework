<?php
/**
 * File ANumberValidator.php
 * @author jackeychen <jackey@fumer.cn>
 * @link http://yii-api.fumer.cn
 * @copyright 2016-2016 Fumer Software LLC
 * @license ??
 * @package yii_api
 * @since 1.0
 */
 
 

 class ANumberValidator extends AValidator {

     public function validate($value) {
        if (!is_numeric($value)) {
            $this->errno = $this->config('errno', 500);
            $this->errmsg = $this->config('errmsg', 'number required');
        }
         $max = $this->config('max', false);
         $min = $this->config('min', false);
         if ($min && $value < $min) {
             $this->errno = $this->config('errno', 500);
             $this->errmsg = $this->config('errmsg', 'number is too small than '. $min);
         }
         if ($max && $value > $max) {
             $this->errno = $this->config('errno', 500);
             $this->errmsg = $this->config('errmsg', 'number is too big than '. $max);
         }
     }
 }