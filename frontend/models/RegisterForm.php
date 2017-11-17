<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/12 0012
 * Time: 16:35
 */

namespace frontend\models;


use yii\base\Model;

class RegisterForm extends Model
{
    //验证登陆
    public $username;
    public $password;
    public $confirm_password;
    public $checkcode;
    public $email;
    public $tel;

    //约束
    public function rules()
    {
        return [
            [['username','password','checkcode','email','tel','confirm_password'],'required'],

        ];
    }
    //设置属性标签名
    public function attributeLabels()
    {
        return [
            'username'=>'用户名',
            'password'=>'密码',
            'checkcode'=>'验证码',
            'email'=>'邮箱',
            'tel'=>'手机',

        ];
    }
}