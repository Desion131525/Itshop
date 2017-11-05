<?php

namespace backend\controllers;

use backend\models\Article_category;
use backend\models\Article_categoryForm;
use yii\data\Pagination;
use yii\web\Request;

class Article_categoryController extends \yii\web\Controller
{   //>>1.显示分类列表
    public function actionIndex()
    {
        //实例化分页组件
        $pager = new Pagination();
        //查询数据
        $article_category = Article_category::find();
        //每页显示条数据
        $pager->pageSize = 3;
        //总记录数据(不包括-1)
        $pager->totalCount = $article_category->where(['status'=>['1','0']])->count();
        //设置偏移量
        $rows = $article_category->where(['status'=>['1','0']])->limit($pager->limit)->offset($pager->offset)->all();
        //分配数据
        return $this->render('index',['rows'=>$rows,'pager'=>$pager]);
    }

    //>>2.添加分类
    public function actionAdd()
    {
        //显示表单
        $model = new Article_categoryForm();
        $request = new Request();
        if($request->isPost)
        {
            $ac = new Article_category();
            //接收数据
            $model->load($request->post());
            //验证数据
            if($model->validate())
            {
                //保存数据
                $ac->name = $model->name;
                $ac->intro = $model->intro;
                $ac->status = $model->status;
                $ac->save();
                //跳转
                \Yii::$app->session->setFlash('success','添加成功');
                $this->redirect('index');
            }

        }

       return $this->render('add',['model'=>$model]);
    }

    //>>3.修改分类
    public function actionEdit()
    {
        $model = new Article_categoryForm();
        //接收id
        $request = new Request();
        $id = $request->get('id');
        //根据id查询数据
        $row = Article_category::findOne($id);
        $model->name = $row->name;
        $model->intro = $row->intro;
        $model->status = $row->status;
        if($request->isPost)
        {
            //接收修改后的数据
            $model->load($request->post());
            //验证数据
            if($model->validate())
            {
                //保存数据
                $row->name = $model->name;
                $row->intro = $model->intro;
                $row->status = $model->status;
                $row->save();
                //跳转
                \Yii::$app->session->setFlash('success','添加成功');
                $this->redirect('index');
            }

        }

        //将数据回显到表单
        return $this->render('edit',['model'=>$model]);
    }

    //>>4.删除分类
    public function actionDel()
    {
        //接收id
        $request = new Request();
        $id = $request->post('id');
        //根据id查询数据
        $row = Article_category::findOne($id);
        //删除数据
        $row->status = -1;
        $result = $row->save(false);
        //响应ajax
        if($result)
        {
            echo '1';
        }

    }

}
