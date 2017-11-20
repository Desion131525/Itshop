<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/17 0017
 * Time: 10:54
 */

namespace frontend\models;


use yii\db\ActiveRecord;

class Order extends ActiveRecord
{
    //定义配送方式
    public static $deliveries=[
        1=>['顺丰快递',25,'速度非常快,服务好,价格贵'],
        2=>['EMS',15,'速度快,服务一般,价格一般,全国各地都能到'],
        3=>['圆通',10,'速度快,服务一般,价格便宜'],
    ];
    //定义支付方式
    public static $payment = [
        1=>['在线支付','即时到帐，支持绝大数银行借记卡及部分银行信用卡'],
        2=>['货到付款','	送货上门后再收款，支持现金、POS机刷卡、支票支付']
    ];


    //建立订单与商品之间的关系
    // order.id ----order_goods.order_id 多对多
    public function getOrder_goods()
    {
        return $this->hasMany(Order_goods::className(),['order_id'=>'id']);

    }






}