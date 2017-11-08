<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/4 0004
 * Time: 16:33
 */

namespace backend\models;


use yii\db\ActiveRecord;
use creocoder\nestedsets\NestedSetsBehavior;
class Goods_category extends ActiveRecord
{


    public function behaviors() {
        return [
            'tree' => [
                'class' => NestedSetsBehavior::className(),
                'treeAttribute' => 'tree',
                // 'leftAttribute' => 'lft',
                // 'rightAttribute' => 'rgt',
                // 'depthAttribute' => 'depth',
            ],
        ];
    }

    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    public static function find()
    {
        return new Goods_category_query(get_called_class());
    }

    //获取分类数据
    public static function getZtreeNodes()
    {
             return self::find()->select(['id','name','parent_id'])->asArray()->all();




    }
}