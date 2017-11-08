<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/7 0007
 * Time: 0:45
 */

namespace backend\models;


use yii\base\Model;

class Goods_introForm extends Model
{
    public $content;


    public function rules()
    {
        return [
            ['content','required']
        ];
    }

    public function attributeLabels()
    {
        return [
            'content'=>'商品详情',
        ];
    }
}