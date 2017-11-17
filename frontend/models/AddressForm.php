<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/14 0014
 * Time: 12:34
 */

namespace frontend\models;


use yii\base\Model;

class AddressForm extends Model
{
    public $name;
    public $tel;
    public $cmb_area;
    public $cmb_city;
    public $cmb_province;
    public $user_address;
    //约束
    public function rules()
    {
        return
            [
                [['name','tel','user_address'],'required'],
                [['cmb_area','cmb_province','cmb_city'],'safe']
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