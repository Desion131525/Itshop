<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/10 0010
 * Time: 0:50
 */

namespace backend\models;


use yii\base\Model;

class Edit_passwordForm extends Model
{
    public $old_pwd;
    public $new_pwd;
    public $re_pwd;
    public function rules()
    {
        return [

            [['old_pwd','new_pwd','re_pwd'],'required'],
            ['re_pwd','compare','compareAttribute'=>'new_pwd','message'=>'两次输入密码不一致']
        ];
    }

    public function attributeLabels()
    {
        return [
            'old_pwd'=>'原始密码',
            'new_pwd'=>'新密码',
            're_pwd'=>'确认新密码',
        ];
    }
}