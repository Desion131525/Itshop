<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/12 0012
 * Time: 15:44
 */

namespace frontend\controllers;


use frontend\components\Sms;
use frontend\models\Member;
use frontend\models\LoginForm;
use frontend\models\RegisterForm;
use yii\web\Controller;
use yii\web\Request;
//'frontend\components\Sms' in file: D:\PhPstudy\WWW\shop1103/frontend/components/Sms.php. Namespace missing?

class MembersController extends Controller
{ public  $enableCsrfValidation = false;
    //登陆
    public function actionLogin()
    {

        //登录表单
        $model = new LoginForm();
        $request = \Yii::$app->request;
        if($request->isPost){
            //接收数据
            $model->load($request->post(),'');

            //验证数据
            if($model->validate()){


                //提示 跳转
                if($model->login($model->cookie))
                {
                    $model->merge_goods();
                    //跳转
                    \Yii::$app->session->setFlash('success','登陆成功');
                    $this->redirect(['index/index']);
                }
            }
        }


        return $this->render('login');
    }

    //注销
    //注销
    public function actionLogout()
    {
        \Yii::$app->user->logout();
        return $this->redirect(['index/index']);
    }

    //注册
    public function actionRegister()
    {
        //显示注册表单
        $model = new RegisterForm();
        $member  = new Member();
        //接收用户注册数据
        $request = \Yii::$app->request;
        if($request->isPost)
        {
            $model->load($request->post(),'');
            if($model->validate())
            {
                //保存数据
                $member->username = $model->username;
                $member->password_hash = \Yii::$app->security->generatePasswordHash($model->confirm_password);
                $member->created_at = time();
                $member->email = $model->email;
                $member->tel = $model->tel;
                $member->save();
                //跳转
                \Yii::$app->session->setFlash('success','注册成功');
                $this->redirect(['index/index']);
            }

        }

        return $this->render('register');

    }

    //验证用户唯一性
    public function actionCheckName($username)
    {
        $username = Member::findOne(['username'=>$username]);

        if($username){
            return 'false';
        }
        return 'true';
    }
    //验证用户的唯一性
    public function actionCheckEmail($email)
    {
        $email = Member::findOne(['email'=>$email]);

        if($email){
            return 'false';
        }
        return 'true';
    }


    //发送短信功能
    public function actionSms($phone)
    {
        //当用户点击获取验证码时,发送ajax短信

       // var_dump($tel);die;
        //接收ajax发送过来的手机号
        //发送短信
        $rand = rand(1000,9999);
        $response = Sms::sendSms(
            "大海夜读", // 短信签名
            "SMS_109535463", // 短信模板编号
            $phone, // 短信接收者
            Array(  // 短信模板中字段的值
                "code"=>$rand,
                "product"=>"dsd"
            ),
            "123"   // 流水号,选填
        );
       // echo "发送短信(sendSms)接口返回的结果:\n";
       // print_r($response);
        //根据$response结果判断是否发送成功
        if ($response->Code=='OK')
        {
            //保存验证码到(session|redis)中
            $redis = new \Redis();
            $redis->connect('127.0.0.1');
            $redis->set("captcha_".$phone,$rand,24*3600);
            return 'success';
        }

        //返回结果

    }

    //验证短信验证码
    public function actionCheckSms()
    {
        $request = new Request();
        $post = $request->post();


        //从redis获取验证码
        $redis = new \Redis();
        $redis->connect('127.0.0.1');
        $code = $redis->get('captcha_'.$post['tel']);
          //var_dump($code);


        //返回对比结果
        if ($code==$post['captcha']){

            return 'true';
        }else{
            return 'false';
        }

    }





    //测试阿里大于
    public function actionTestSms()
    {
        $response = Sms::sendSms(
            "大海夜读", // 短信签名
            "SMS_109535463", // 短信模板编号
            "13481194554", // 短信接收者
            Array(  // 短信模板中字段的值
                "code"=>rand(1000,9999),
                "product"=>"dsd"
            ),
            "123"   // 流水号,选填
        );
        echo "发送短信(sendSms)接口返回的结果:\n";
        print_r($response);

    }
}