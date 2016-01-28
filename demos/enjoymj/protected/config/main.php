<?php
/**
 * File main.php
 * @author jackeychen <jackey@fumer.cn>
 * @link http://yii-api.fumer.cn
 * @copyright 2016-2016 Fumer Software LLC
 * @license ??
 * @package yii_api
 * @since 1.0
 */

return array(
    'basePath' => dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
    'name' => "Enjoy Meiju (爱美剧)",
    'preload' => array(),
    'import' => array(
        'application.models.*',
        'application.components.*'
    ),
    'displayHandler' => 'json', // or xml
    'defaultAPIPoint' => 'index.welcome',
    'components' => array(
        'db' => array(
            'class' => 'CDbConnection',
            'connectionString' => 'mysql:host=localhost;dbname=enjoymj_db',
            'username' => 'root',
            'password' => 'admin',
        )
    ),
    'app_keys' => array(
        'iOS' => array(
            'app_id' => '2001503',
            'app_secret' => 'YES',
        )
    ),
    'params' => array(

    )
);
 
 

 