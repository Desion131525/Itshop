<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/15 0015
 * Time: 23:14
 */

namespace frontend\models;


use yii\db\ActiveRecord;

class Cart extends ActiveRecord
{
    //约束
    public function rules()
    {
        return
            [
                [['goods_id','amount'],'required'],

            ];
    }

    //标签名
    public function attributeLabels()
    {
        return
            [

            ];
    }
}