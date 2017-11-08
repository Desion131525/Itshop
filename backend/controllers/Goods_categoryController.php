<?php
namespace backend\controllers;
use backend\models\Goods_category;
use backend\models\Goods_categoryForm;
use yii\data\Pagination;
use yii\web\Controller;
use yii\web\Request;

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/4 0004
 * Time: 16:28
 */
class Goods_categoryController extends Controller
{
    //>>1.显示分类列表
    public function actionIndex()
    {
        //实例化分页组件
        $pager = new Pagination();
        //查询数据
        $goods_category = Goods_category::find();
        //每页显示条数据
        $pager->pageSize = 3;
        //总记录数据
        $pager->totalCount = $goods_category->count();
        //设置偏移量
        $rows = $goods_category->limit($pager->limit)->offset($pager->offset)->all();
        //分配数据
        return $this->render('index',['rows'=>$rows,'pager'=>$pager]);
    }

    //>>2.添加分类
    public function actionAdd()
    {

        //显示表单
        $model = new Goods_categoryForm();
        $model->parent_id = 0;

        $request = new Request();
        if($request->isPost)
        {
            $gc = new Goods_category();
            //接收数据
            $model->load($request->post());
            //验证数据
            if($model->validate())
            {
                //保存数据
                $gc->name = $model->name;
                $gc->intro = $model->intro;
                $gc->parent_id = $model->parent_id;
                if ($model->parent_id == 0)
                {

                        $gc->makeRoot() ;



                }else{
                    $parent = Goods_category::findOne(['id'=>$gc->parent_id]);
                    $gc->prependTo($parent);

                }

                //跳转
                \Yii::$app->session->setFlash('success','添加成功');
                $this->redirect('index');
            };

        };

        return $this->render('add',['model'=>$model]);
    }

    //>>3.修改分类
    public function actionEdit()
    {
        $model = new Goods_categoryForm();
        //接收id
        $request = new Request();
        $id = $request->get('id');
        //根据id查询数据
        $row = Goods_category::findOne($id);
        $model->name = $row->name;
        $model->intro = $row->intro;
        $model->parent_id = $row->parent_id;
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
                $row->parent_id = $model->parent_id;
                //判断
                if ($model->parent_id == 0)
                {
                    //修改根节点
                    //但是如果是根节点修改为根节点就会报错,所以要再判断一次
                    if ($row->getOldAttribute('parent_id') == 0)
                    {
                        //如果要修改的节点为根节点,那么就直接保存.
                        $row->save();
                    }else{
                        $row->makeRoot() ;
                    }

                }else{
                    $parent = Goods_category::findOne(['id'=>$row->parent_id]);
                    $row->prependTo($parent);

                }
                //$row->save();
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
        $row = Goods_category::findOne($id);
        //删除数据 只能删除空节点,
        //当一个节点是叶子节点的时候,他就是空节点,所以只要判断当前节点是否是叶子节点即可
        if($row->isLeaf())
        {
           if($row->parent_id !=0)
           {
               //如果叶子节点不是根节点,直接删除.
               $result = $row->delete();
           }else{
             //节点和他的儿子一起删除
               //$result = $row->deleteWithChildren();

           }
        }else{
            //有子节点
            echo '有子节点 不能删除';
        }


        //响应ajax
        if($result)
        {
            echo '1';
        }

    }

}