<?php
namespace backend\controllers;
use backend\models\Goods;
use backend\models\GoodsForm;
use yii\data\Pagination;
use yii\web\Controller;
use yii\web\Request;
use yii\web\UploadedFile;

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/4 0004
 * Time: 20:25
 */
class GoodsController extends Controller
{
    //>>1显示品牌列表
    public function actionIndex()
    {
        //实例化分页组件
        $pager = new Pagination();
        //查询数据
        $goods = Goods::find();
        //每页显示条数据
        $pager->pageSize = 3;
        //总记录数据
        $pager->totalCount = $goods->where(['status'=>1])->count();
        //设置偏移量
        $rows = $goods->where(['status'=>[0,1]])->limit($pager->limit)->offset($pager->offset)->all();
        //分配数据
        return $this->render('index',['rows'=>$rows,'pager'=>$pager]);
    }

    //>>2.添加品牌
    public function actionAdd()
    {
        //显示添加表单
        $model = new GoodsForm();
        $request = new Request();
        if($request->isPost)
        {
            $goods = new Goods();
            //接收表单数据
            $model->load($request->post());
            //将文件封装成对象
            $model->imgFile = UploadedFile::getInstance($model,'imgFile');

            //验证表单数据
            if ($model->validate())
            {
                if($model->imgFile!=null)
                {
                    //获取文件扩展名
                    $ext = $model->imgFile->extension;
                    //拼接文件路径
                    $fileName = '/upload/'.uniqid().'.'.$ext;
                    //指定文件保存的路径
                    $model->imgFile->saveAs(\Yii::getAlias('@webroot').$fileName,0);
                    $goods->logo = $fileName;
                }else{
                    $goods->logo =  \Yii::getAlias('@web').'/upload/2.jpg';
                }

                //将文件路径保存到数据库
                $goods->name = $model->name;
                $goods->sn = $model->sn;
                $goods->brand_id = $model->brand_id;
                $goods->goods_category_id = $model->goods_category_id;
                $goods->market_price = $model->market_price;
                $goods->shop_price = $model->shop_price;
                $goods->stock = $model->stock;
                $goods->status = $model->status;
                $goods->is_on_sale = $model->is_on_sale;
                $goods->create_time = time();
                $goods->view_times = $model->view_times;
                $goods->save(false);
                //跳转
                \Yii::$app->session->setFlash('success','添加成功');
                $this->redirect('index');
            }
        }
        //跳转
        return $this->render('add',['model'=>$model]);
    }

    //>>3.修改商品
    public function actionEdit()
    {
        $model = new GoodsForm();
        //接收id
        $request = new Request();
        $id = $request->get('id');
        //根据id查询数据
        $row = Goods::findOne($id);
        $model->name = $row->name;
        $model->sn = $row->sn;
        $model->logo = $row->logo;
        $model->brand_id = $row->brand_id;
        $model->goods_category_id = $row->goods_category_id;
        $model->market_price = $row->market_price;
        $model->shop_price = $row->shop_price;
        $model->stock = $row->stock;
        $model->status = $row->status;
        $model->is_on_sale = $row->is_on_sale;
        $model->create_time = $row->create_time;
        $model->view_times = $row->view_times;

        if($request->isPost)
        {
            //接收修改后的表单数据
            $model->load($request->post());
            //将文件封装成对象
            $model->imgFile = UploadedFile::getInstance($model,'imgFile');
            //验证数据
            if($model->validate())
            {   //判断是否有图片上传
                if ($model->imgFile!=null)
                {
                    //获取文件扩展名
                    $ext = $model->imgFile->extension;
                    //拼接文件路径
                    $fileName = '/upload/'.uniqid().'.'.$ext;
                    //指定文件保存的路径
                    $model->imgFile->saveAs(\Yii::getAlias('@webroot').$fileName,0);
                    $row->logo = $fileName;
                }
                //将文件路径保存到数据库
                $row->name = $model->name;
                $row->sn = $model->sn;
                $row->brand_id = $model->brand_id;
                $row->goods_category_id = $model->goods_category_id;
                $row->market_price = $model->market_price;
                $row->shop_price = $model->shop_price;
                $row->stock = $model->stock;
                $row->status = $model->status;
                $row->is_on_sale = $model->is_on_sale;
                $row->view_times = $model->view_times;
                $row->save(false);
                //跳转
                \Yii::$app->session->setFlash('success','修改成功');
                $this->redirect('index');
            }

        }

        //将数据回显到表单
        return $this->render('edit',['model'=>$model]);
    }

    //>>4.删除品牌
    public function actionDel()
    {
        //接收id
        $request = new Request();
        $id = $request->post('id');
        //根据id查询数据
        $goods = Goods::findOne($id);
        //删除数据
        $goods->status = -1;
        $result = $goods->save(false);
        //响应ajax
        if($result)
        {
            echo '1';
        }

    }

    //>>5.回收列表
    public function actionRecycle()
    {
        //实例化分页组件
        $pager = new Pagination();
        //查询数据
        $goods = Goods::find();
        //每页显示条数据
        $pager->pageSize = 3;
        //总记录数据
        $pager->totalCount = $goods->where(['status'=>['-1']])->count();
        //设置偏移量
        $rows = $goods->where(['status'=>['-1']])->limit($pager->limit)->offset($pager->offset)->all();
        //分配数据
        return $this->render('recycle',['rows'=>$rows,'pager'=>$pager]);
    }

    //恢复
    public function actionRestore()
    {
        //接收id
        $request = new Request();
        $id = $request->post('id');
        //根据id查询数据
        $row = Goods::findOne($id);
        //恢复数据
        $row->status = 1;
        $result = $row->save(false);

        //响应ajax
        if($result)
        {
            echo '1';
        }
    }

    //永久删除
    public function actionDelete()
    {
        //接收id
        $request = new Request();
        $id = $request->post('id');
        //根据id查询数据
        $row = Goods::findOne($id);
        //删除数据
        $result = $row->delete();
        //响应ajax
        if($result)
        {
            echo '1';
        }
    }

}