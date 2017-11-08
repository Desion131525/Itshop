<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/7 0007
 * Time: 21:58
 */

namespace backend\models;


use yii\base\Model;

class UserForm extends Model
{
    public $username;
    public $auth_key;
    public $password_hash;
    public $email;
    public $status;
    public $created_at;


    public function rules()
    {
        return [
            [['email','username','auth_key','password_hash','status','created_at'],'required']
        ];
    }

    public function attributeLabels()
    {
        return [
            'username'=>'用户名',
            'auth_key'=>'角色',
            'password_hash'=>'密码',
            'status'=>'状态',
            'created_at'=>'创建时间',
        ];
    }
}