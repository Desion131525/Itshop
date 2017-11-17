<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/14 0014
 * Time: 21:27
 */

namespace frontend\controllers;


use Aliyun\Core\Http\HttpHelper;
use backend\models\Goods_gallery;
use backend\models\Goods_intro;
use frontend\models\Cart;
use frontend\models\Goods;
use backend\models\Goods_category;
use function PHPSTORM_META\map;
use yii\bootstrap\Html;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\Cookie;
use yii\web\Request;

class IndexController extends Controller
{   public  $enableCsrfValidation = false;
   //显示首页
    public function actionIndex()
    {
        //获取parent_id为0的数据
        $rows = Goods_category::find()->where(['parent_id'=>0])->all();
        /*var_dump($rows);die;
        $rows_01 = Goods_category::find()->where(['parent_id'=>$rows->id])->all();
        var_dump($rows_01);*/
        return $this->render('index',['rows'=>$rows]);
    }

    //商品分类列表
    public function actionList($id)
    {
        //根据id查询商品商品记录
        $goods_category = Goods_category::findOne(['id'=>$id]);
        //var_dump($goods_category);die;
        //根据深度判断 如果商品的为最后一层分类,直接查询商品

        if($goods_category->depth==2)
        {
            $query = Goods::find()->where(['goods_category_id'=>$id]);
        }else{

            //如果不是 获取其子分类id
           $ids = $goods_category->children()->andWhere(['depth'=>2])->column();
            //根据id查询商品
            $query = Goods::find()->where(['in','goods_category_id',$ids]);
        }


        $pager = new Pagination();
        $pager->totalCount = $query->count();

        $pager->pageSize = 10;
        $rows = $query->limit($pager->limit)->offset($pager->offset)->all();
        return $this->render('list',['rows'=>$rows,'pager'=>$pager]);

    }

    //商品详情
    public function actionGoods($id)
    {
        //显示页面
        $goods_intro = Goods_intro::findOne(57);

        $goods = \backend\models\Goods::findOne(['id'=>$id]);

        return $this->render('goods',['goods_intro'=>$goods_intro,'goods'=>$goods]);
    }

    //购物车
    /*public function actionCart()
    {
        $request = new Request();
        $model = new Cart();
        if($request->isPost)
        {
            //接收数据
            $model->load($request->post(),'');
            $model->member_id = \Yii::$app->user->identity->id;
            if($model->save())
            {
                $goods = Goods::findOne(['id'=>$model->goods_id]);
                return $this->render('cart',['goods'=>$goods]);
            }else{
                //显示购物车页面
                return $this->render('cart');
            }
        }



    }*/

    //购物车页面
    public function actionCart()
    {
        //显示购物车列表,判断是否登陆,如果没有登陆则从cookie中取出购物车数据
        //如果已登陆则从数据库中取出数据
        if(\Yii::$app->user->isGuest)
        {
            //未登陆 从cookie中取出数据 首先需要实例化一个读操作的cookie对象
            $cookies = \Yii::$app->request->cookies;
            //从cookie中取出购物车数据,调用视图渲染
            $carts = $cookies->getValue('carts');

            //判断cookie中是否有数据
            if($carts)
            {
                //如果有将数据时行反序列化,因为保存到cookie的数据是以数组的形式放时去的
                //需要进行序列化后才能保存,所以取到数据后也需要反序列化
                $carts = unserialize($carts);

            }else{
                //如果没有就赋值一个空数组,避免报错
                $carts = [];
            }

            //因为在保存商品数据到cookie中时,将商品的id作为key 商品数量作为value 对应保存,所以
            //在获取商品信息时,需要根据保存在cookie中的goods_id去数据库将商品查询出来.
            $rows = \backend\models\Goods::find()->where(['in','id',array_keys($carts)])->all();

        }else{
            //已经登陆 从数据库中取出数据
            $member = \Yii::$app->user->identity;
            $carts = Cart::find()->andWhere(['member_id'=>$member->id])->all();
            //将对象转换成指定键值对的数组
            $carts = ArrayHelper::map($carts,'goods_id','amount');

            $rows = \backend\models\Goods::find()->where(['in','id',array_keys($carts)])->all();
        }

        return $this->render('cart',['carts'=>$carts,'rows'=>$rows]);
    }

    //添加购物车
    public function actionAdd_cart($goods_id,$amount)
    {
        //判断是否登陆
        if(\Yii::$app->user->isGuest)
        {
            //未登陆 从cookie中取出数据 首先需要实例化一个读操作的cookie对象
            $cookies = \Yii::$app->request->cookies;
            //从cookie中取出购物车数据,调用视图渲染
            $carts = $cookies->getValue('carts');
            //判断cookie中是否有数据
            if($carts)
            {
                //如果有将数据时行反序列化,因为保存到cookie的数据是以数组的形式放时去的
                //需要进行序列化后才能保存,所以取到数据后也需要反序列化
                $carts = unserialize($carts);
            }else{
                //如果没有就赋值一个空数组,避免报错
                $carts = [];
            }

            //判断cookie中是否有同一id的商品存在
            if(array_key_exists($goods_id,$carts))
            {
                //有则数量相加
                $carts[$goods_id] += $amount;
            }else{

                //没有则新增
                $carts[$goods_id] = $amount;

            }

            //存入cookie,实例化一个<写>cookie
            $cookies = \Yii::$app->response->cookies;
            $cookie = new Cookie();
            $cookie->name = 'carts';
            $cookie->value = serialize($carts);

            $cookies->add($cookie);

        }else{
            //已登陆,操作数据库
            $request = new Request();
            $model = new Cart();
            if($request->get())
            {
                //接收数据
                $model->load($request->get(),'');
                //判断数据库中是否有同id的商品,有则修改没有则添加
                $member = \Yii::$app->user->identity;
                $cart =Cart::find()->where(['goods_id'=>$model->goods_id])->andWhere(['member_id'=>$member])->one();
                //var_dump($cart->goods_id);die;
                if($cart)
                {
                    //有相同id商品,将商品数量累加 后保存
                    $cart->amount +=$model->amount;
                    $cart->save();
                }else{

                    //没有相同id商品,直接添加商品
                    $model->member_id = \Yii::$app->user->identity->id;
                    $model->save();
                }


            }
        }

        //跳转到购物车页面
        return $this->redirect(['cart']);
    }

    //ajax操作购物车
    public function actionAjax_cart($type)
    {
        //接收ajax传过来的参数(商品的id,数量)
        $goods_id = \Yii::$app->request->post('goods_id');
        $amount = \Yii::$app->request->post('amount');

        switch ($type)
        {
            //修改购物车
            case 'change':

                //判断是否登陆
                if(\Yii::$app->user->isGuest)
                {
                   //未登陆则cookie中取出商品数据进行修改
                    $cookies = \Yii::$app->request->cookies;
                    //从cookie中取出购物车数据,
                    $carts = $cookies->getValue('carts');
                    //判断cookie中是否有数据
                    if($carts)
                    {
                        //如果有将数据进行反序列化,因为保存到cookie的数据是以数组的形式放时去的
                        //需要进行序列化后才能保存,所以取到数据后也需要反序列化
                        $carts = unserialize($carts);
                    }else{
                        //如果没有就赋值一个空数组,避免报错
                        $carts = [];
                    }

                    //取到数据 修改购物车商品数量 覆盖掉原来的商品数量
                    $carts[$goods_id] = $amount;
                    //保存修改后的数据
                    $cookies = \Yii::$app->response->cookies;
                    $cookie = new Cookie();
                    $cookie->name = 'carts';
                    $cookie->value = serialize($carts);

                    $cookies->add($cookie);

                }else{

                    //已登陆 则操作数据库
                    //根据商品id和用户id 查询商品的记录
                    //获取用户id
                    $member =  \Yii::$app->user->identity;
                    $cart = Cart::find()->where(['goods_id'=>$goods_id])->andWhere(['member_id'=>$member])->one();
                    $cart->amount = $amount;

                    $cart->save();

                }
                break;

            //删除购物车
            case 'del':
            //判断是否登陆
            if(\Yii::$app->user->isGuest)
            {
                //未登陆则cookie中取出商品数据进行修改
                $cookies = \Yii::$app->request->cookies;
                //从cookie中取出购物车数据,
                $carts = $cookies->getValue('carts');
                $carts = unserialize($carts);
                //根据goods_id删除对应的商品
                unset($carts[$goods_id]);
                //响应ajax
                echo '1';

                //如果删完了就不执行添加.
                if(!empty($carts)){

                    //发送cookie
                    $cookies = \Yii::$app->response->cookies;
                    $cookie = new Cookie();
                    $cookie->name = 'carts';
                    $cookie->value = serialize($carts);
                    $cookies->add($cookie);
                }

            }else{

                //已经登陆
                //根据商品id和用户id 查询商品的记录
                $member =  \Yii::$app->user->identity;
                $cart = Cart::find()->where(['goods_id'=>$goods_id])->andWhere(['member_id'=>$member])->one();

                if( $cart->delete())
                {
                    echo '1';
                }

            }
                break;
        }

    }


}