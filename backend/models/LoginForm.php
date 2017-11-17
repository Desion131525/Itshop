<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/8 0008
 * Time: 10:56
 */

namespace backend\models;


use yii\base\Model;

class LoginForm extends Model
{
    public $username;
    public $password;
    public $cookie;

    //约束
    public function rules()
    {
        return [
            [['username','password'],'required'],

        ];
    }
    //设置属性标签名
    public function attributeLabels()
    {
        return [
            'username'=>'用户名',
            'password'=>'密码',
        ];
    }

    //验证登陆数据
    public function login($cookie)
    {
        //先验证帐号
        $admin = User::findOne(['username'=>$this->username]);
        //帐号验证通过了再验证密码
        if($admin)
        {
            //验证密码要调用安全组件验证密码的方法来验证
            $password = \Yii::$app->security->validatePassword($this->password,$admin->password_hash);
            if($password)
            {
                //记录此次登陆时间
                $admin->last_login_time = time();
                //获取用户访问ip
                $admin->last_login_ip = \Yii::$app->request->getUserIP();
                //保存到cookie中
                if($cookie)
                {
                    $time = 30*24*3600;
                }else{
                    $time = 0;
                }
                //密码验证通过了将登陆标识保存到session中
                \Yii::$app->user->login($admin,$time);
                $admin->save();
                return true;
            }else{
                //密码错误给模型添加错误提示
                $this->addError('password','密码错误');
            }
        }else{
            //帐号错误给模型添加错误提示
            $this->addError('username','帐号不存在');
        }

        return false;

    }
}