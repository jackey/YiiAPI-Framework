<?php
/**
 * File MeijuModel.php
 * @author jackeychen <jackey@fumer.cn>
 * @link http://yii-api.fumer.cn
 * @copyright 2016-2016 Fumer Software LLC
 * @license ??
 * @package yii_api
 * @since 1.0
 */
 
 class MeijuModel extends CActiveRecord {

    public function primaryKey() {
        return 'Fid';
    }

    public function tableName() {
        return 't_meiju';
    }

    public static function model($class = __CLASS__) {
        return parent::model($class);
    }

    public function loadMeijuWithCat($cats, $page = 1, $limit = 50 ) {
        $query = $this->getDbCriteria();
        if (!is_array($cats)) $cats = array($cats);
        $query->addInCondition('Fcategory', $cats);
        $query->addInCondition('Fcategory2', $cats);

        $query->limit = $limit;
        $query->offset = ($page - 1) * $page;

        return $this->findAll($query);
    }
 }

 