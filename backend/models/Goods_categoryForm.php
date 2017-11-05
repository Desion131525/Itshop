<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/4 0004
 * Time: 16:47
 */

namespace backend\models;


use yii\base\Model;

class Goods_categoryForm extends Model
{
    public $name;
    public $intro;
    public function rules()
    {
        return [
            [['name', 'intro'], 'required'],
            [['intro'], 'string'],
            [['name'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '分类名称',
            'intro' => '描述',
        ];
    }
}