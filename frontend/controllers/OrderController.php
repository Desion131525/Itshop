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
use frontend\models\Goods;
use frontend\models\Order;
use frontend\models\Order_goods;
use yii\db\Exception;
use yii\web\Controller;
use yii\web\HttpException;

class OrderController extends Controller
{   public $enableCsrfValidation = false;

    //显示订单页面
    public function actionOrder_info()
    {
        $order = new Order();
        //当用户提交订单时,判断用户是否登陆
        if(\Yii::$app->user->isGuest)
        {
            //如果没有登陆跳转到登陆页面
            $this->redirect(['members/login']);

        }

        //获取用户id
        $member = \Yii::$app->user->id;
        //根据查询 用户地址
        $rows = Address::find()->where(['member_id'=>$member])->all();
        //获取用户购物车商品
        $query = Cart::find()->where(['member_id'=>$member]);
        $carts = $query->all();

        //计算购物商品总金额
        $total = '';
        foreach ($carts as $cart)
        {
            //建立关系 然后调用
            $total += $cart->goods->market_price*$cart->amount;
        }

        return $this->render('order_info',['rows'=>$rows ,'order'=>$order,'carts'=>$carts,'query'=>$query,'total'=>$total]);
    }

    //提交订单
    public function actionOrder_point()
    {
        //当订单提交时,将订单信息保存到数库
        $requet = \Yii::$app->request;
        if($requet->isPost)
        {
            $order = new Order();
            $order->member_id = \Yii::$app->user->id;
            $address_id = $requet->post('address_id');
            //根据id 查询地址
            $address = Address::findOne(['id'=>$address_id,'member_id'=>\Yii::$app->user->id]);

            //判断地址是否存在
            if($address==null)
            {
                //地址为空,抛出404异常
                throw new HttpException(404,'地址错误');
                return false;
            }
            //用户在地址
            $order->name = $address->name;
            $order->province = $address->cmb_province;
            $order->city = $address->cmb_city;
            $order->area = $address->cmb_area;
            $order->address = $address->user_address;
            $order->tel = $address->tel;
            //配送方式
            $order->delivery_id = $requet->post('delivery');
            $order->delivery_name =Order::$deliveries[$order->delivery_id][0];
            $order->delivery_price =Order::$deliveries[$order->delivery_id][1];
            //支付方式
            $order->payment_id = $requet->post('pay');
            $order->payment_name = Order::$payment[$order->payment_id][0];

          /*  //获取用户购物车商品
            $carts = Cart::find()->where(['member_id'=>\Yii::$app->user->id])->all();
            //计算购物商品总金额
            $total = '';
            foreach ($carts as $cart)
            {
                //建立关系 然后调用
                $total += $cart->goods->market_price*$cart->amount;
            }*/
            $order->total =$order->delivery_price;
            $order->status = 1;
            $order->trade_no = 0;
            $order->create_time = time();

            //在订单保存之前开启事务
            $transaction = \Yii::$app->db->beginTransaction();
            try
            {
                //如果订单保存成功,则保存商品表
                if($order->save())
                {
                    //判断商品库存是否足够
                    $carts = Cart::find()->where(['member_id'=>\Yii::$app->user->id])->all();
                    foreach ($carts as $cart)
                    {
                        //检测商品库存是否足够
                        if($cart->amount > $cart->goods->stock)
                        {
                            throw new Exception($cart->goods->name . '商品库存不足');
                        }
                        //保存商品表
                        $order_goods = new Order_goods();
                        $order_goods->order_id = $order->id;
                        $order_goods->goods_id =$cart->goods->id;
                        $order_goods->goods_name =$cart->goods->name;
                        $order_goods->logo =$cart->goods->logo;
                        $order_goods->logo =$cart->goods->logo;
                        $order_goods->price =$cart->goods->market_price;
                        $order_goods->amount =$cart->amount;
                        $order_goods->total =$cart->amount*$cart->goods->market_price;
                        $order_goods->save();
                        $order->total+=$order_goods->total;
                        $order->save();
                        //扣减商品库存
                        \backend\models\Goods::updateAllCounters(['stock'=>-$cart->amount],['id'=>$cart->goods_id]);

                    }
                   //删除购物车
                    Cart::deleteAll('member_id='.\Yii::$app->user->id);

                }
                //提交事务
                $transaction->commit();

                    //跳转
                    echo '1';die;

            }catch (Exception $e)
            {
                //回滚
                $transaction->rollBack();
                //下单失败,跳转回购物车,并且提示商品库存不足
                echo $e->getMessage();exit;
            }

        }

        //显示订单提示信息页面
        return $this->render('order_point');
    }

    //显示订单列表
    public function actionOrder()
    {
        //根据用户id查询订单
        $orders = Order::find()->where(['member_id'=>\Yii::$app->user->id])->all();
        //通过订单id获取订单商品

        return $this->render('order',['orders'=>$orders]);
    }

    //删除订单
    public function actionDel($id)
    {
        //根据订单id查询订单
        $order = Order::findOne($id);
        //根据订单id查询商品
        if($order->delete())
        {
            $goods = $order->order_goods;
            foreach ($goods as $v)
            {
               $v->delete();
            }

            echo '1';

        }else{

            echo '删除订单失败';
        }


    }




    //测试
    public function actionTest()
    {
        $cart = Order::findOne(['id'=>9]);

        $name =$cart->order_goods;

    }
}