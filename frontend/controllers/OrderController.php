<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/17 0017
 * Time: 10:53
 */

namespace frontend\controllers;


use frontend\models\Address;
use frontend\models\Cart;
use yii\web\Controller;

class OrderController extends Controller
{
    //显示订单页面
    public function actionOrder()
    {
        //当用户提交订单时,判断用户是否登陆
        if(\Yii::$app->user->isGuest)
        {
            //如果没有登陆跳转到登陆页面
            $this->redirect(['members/login']);

        }

        //获取用户id
        $member = \Yii::$app->user->identity;
        //根据查询 用户地址
        $rows = Address::find()->where(['member_id'=>$member])->all();

        //获取用户购物车商品
          




        return $this->render('order',['rows'=>$rows]);
    }
}