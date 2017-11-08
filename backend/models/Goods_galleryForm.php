<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/6 0006
 * Time: 14:07
 */

namespace backend\models;


use yii\base\Model;

class Goods_galleryForm extends Model
{
    public $imgFile;
    public $goods_id;

    public function rules()
    {
        return [
            ['imgFile','file','extensions'=>['jpg','png','gif'],'skipOnEmpty'=>false]
        ];
    }

    public function attributeLabels()
    {
        return [
            'imgFile'=>'商品图片',
        ];
    }
}