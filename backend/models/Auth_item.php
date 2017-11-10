<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/9 0009
 * Time: 13:55
 */

namespace backend\models;


use yii\db\ActiveRecord;

class Auth_item extends ActiveRecord
{
    public $permissions;
    public $role;
    public function rules()
    {
        return [
            [['name','description',],'required'],
            ['permissions','safe'],
            ['role','safe']
        ];
    }

    public function attributeLabels()
    {
        return [
            'name'=>'名称',
            'description'=>'描述',
            'permissions'=>'权限',
            'role'=>'角色',
        ];
    }
}