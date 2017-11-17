<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/12 0012
 * Time: 15:52
 */

namespace frontend\models;



use yii\base\Model;
use yii\web\Cookie;

class LoginForm extends Model
{
    //验证登陆
    public $username;
    public $password;
    public $checkcode;
    public $cookie;

    //约束
    public function rules()
    {
        return [
            [['username','password','checkcode'],'required'],
             ['cookie','safe']
        ];
    }
    //设置属性标签名
    public function attributeLabels()
    {
        return [
            'username'=>'用户名',
            'password'=>'密码',
            'checkcode'=>'验证码',
        ];
    }
    //验证登陆数据
    public function login($cookie)
    {
        //先验证帐号
        $admin = Member::findOne(['username'=>$this->username]);
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

    //成功登陆后将cookie中的商品合并到数据库
    public function merge_goods()
    {
        //将cookie中的商品查询出来
        $cookies = \Yii::$app->request->cookies;
        //从cookie中取出购物车数据,
        $carts = $cookies->getValue('carts');
        $carts = unserialize($carts);
        $model = new Cart();
        //获取用户登陆的id
        $member =  \Yii::$app->user->identity;
        foreach ($carts as $goods_id=>$cart)
        {
            $goods = Cart::find()->where(['goods_id'=>$goods_id])->andWhere(['member_id'=>$member->id])->one();

            if($goods)
            {
                //如果该用户有相同的商品id 则修改商品数量
                $goods->amount += $cart;
                $goods->save();
            }else{

                //没有则添加
                $model->goods_id = $goods_id;
                $model->amount = $cart;
                $model->member_id = $member->id;
                $model->save();
            }
        }
        //商品清除cookie
        $carts = [];
        //发送cookie
        $cookies = \Yii::$app->response->cookies;
        $cookie = new Cookie();
        $cookie->name = 'carts';
        $cookie->value = serialize($carts);
        $cookies->add($cookie);

    }
}