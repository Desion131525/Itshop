<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/14 0014
 * Time: 11:55
 */

namespace frontend\models;


use yii\db\ActiveRecord;

class Address extends ActiveRecord
{
    public $edit_id;

    //约束
    public function rules()
    {
        return
            [
                [['name','tel','user_address'],'required'],
                [['cmb_area','cmb_province','cmb_city','edit_id'],'safe']
            ];
    }

    //标签名
    public function attributeLabels()
    {
        return
            [
                'name'=>'收货人',
                'user_address'=>'详细地址',
                'tel'=>'手机号',
            ];
    }

}