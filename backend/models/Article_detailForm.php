<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/4 0004
 * Time: 10:06
 */

namespace backend\models;


use yii\base\Model;

class Article_detailForm extends Model
{
    public $content;

    //约束
    public function rules()
    {
        return
            [
                ['content','required']
            ];
    }

    //标签名
    public function attributeLabels()
    {
        return
            [
                'content'=>'文章详情'
            ];
    }
}