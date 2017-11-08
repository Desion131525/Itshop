<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/4 0004
 * Time: 20:27
 */

namespace backend\models;


use yii\db\ActiveRecord;

class Goods extends ActiveRecord
{
    public function getBrand()
    {
        // goods.brand_id  ---- brand.id 多对1
       return $this->hasOne(Brand::className(),['id'=>'brand_id']);

    }
}