<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/14 0014
 * Time: 10:50
 */

namespace frontend\controllers;


use frontend\models\Address;
use frontend\models\AddressForm;
use yii\web\Controller;
use yii\web\Request;

class AddressController extends Controller
{ public  $enableCsrfValidation = false;
    //显示地址页面
    public function actionAddress()
    {
        $request = new Request();
        $model = new Address();
        $rows = Address::find()->all();
        //var_dump($rows);die;
        if($request->get())
        {
            $model->load($request->post(),'');

            if($model->validate())
            {

                if($model->save())
                {
                    $this->redirect(['address/address']);
                };
            }

        }
        return $this->render('address',['rows'=>$rows]);
    }

    //删除地地址
    public function actionDel()
    {
        //接收id
        $request = new Request();
        $id = $request->post('id');
        //根据id查询数据
        $address = Address::findOne($id);
        //删除数据

        $result = $address->delete();
        //响应ajax
        if($result)
        {
            echo '1';
        }
    }


    //修改
    public function actionEdit()
    {
        //接收id
        $request = new Request();
        $id = $request->get('id');
        //根据id查询数据
        $address = Address::findOne($id)->toArray();
        //var_dump($address);die;
       // var_dump($address);die;
        if($address)
        {

          return  json_encode($address);
        }

    }

  //ajax添加
    public function actionAdd()
    {
        $request = new Request();
        $id = $request->get('edit_id');

        if($id)
        {$address = Address::findOne(['id'=>$id]);
            $address->load($request->get(),'');
            if($address->save())
            {
                echo '1';

            }
        }else{
            $model = new Address();
            $model->load($request->get(),'');
            //获取用户id
            $member = \Yii::$app->user->identity;
            $model->member_id = $member->id;
            if($model->save())
            {
                echo '1';

            }
        }

    }










    //测试
    public function actionTest()
    {
        return $this->render('test');
    }
}