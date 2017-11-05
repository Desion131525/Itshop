<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/4 0004
 * Time: 0:07
 */

namespace backend\models;


use yii\base\Model;

class Article_categoryForm extends Model
{
    public $name;
    public $intro;
    public $status;
    public function rules()
    {
        return [
            [['name', 'intro', 'status'], 'required'],
            [['intro'], 'string'],
            [ 'status', 'integer'],
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
            'status' => '状态',
        ];
    }
}