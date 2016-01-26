<?php
/**
 * File info.php
 * @author jackeychen <jackey@fumer.cn>
 * @link http://yii-api.fumer.cn
 * @copyright 2016-2016 Fumer Software LLC
 * @license ??
 * @package yii_api
 * @since 1.0
 */

class Info extends APIBase {

    public function rules() {
        return array(
            'limit' => array(
                'V' => array(
                    'Number' => array('errno' => 520, 'errmsg' => '请输入 limit')
                ),
                'D' => 10
            )
        );
    }

    public function run() {
        $query = MeijuModel::model()->getDbCriteria();
        $query->limit = 10;
        $query->offset = 0;
        $meijuModels = MeijuModel::model()->findAll($query);

        return array(
            'hello' => 'world'
        );
    }
}
 

 