<?php
namespace backend\controllers;
use backend\filters\AdminFilter;
use backend\models\Auth_item;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Request;

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/9 0009
 * Time: 11:53
 */
class PermissionController extends Controller
{
    //显示权限列表
    public function actionIndex()
    {
        //创建权限对象name
        $permission = \Yii::$app->authManager;
        //获取权限数据
        $rows = $permission->getPermissions();
        //var_dump($rows->name);die;

        return $this->render('index',['rows'=>$rows]);
    }

    //添加权限
    public function actionAdd()
    {

        //显示添加表单
        $model = new Auth_item();
        $request = new Request();
        //设置权限场景
        $model->scenario = Auth_item::SCENARIO_Add;
        if($request->isPost)
        {
            //接收数据
            $model->load($request->post());
            //验证数据
            if($model->validate()&&$model->add())
            {
                //跳转
                \Yii::$app->session->setFlash('success','添加成功');
                $this->redirect(['permission/index']);
            }

        }
        return $this->render('add',['model'=>$model]);
    }

    //权限删除
    public function actionDel()
    {
        //创建rbac组件对象
        $rbac = \Yii::$app->authManager;
        //接收数据
        $name = \Yii::$app->request->post('name');
        //根据name调用组件对象查询数据
        $permission = $rbac->getPermission($name);
        //删除
       $result = $rbac->remove($permission);
        if ($result)
        {
            echo '1';
        }
    }

    //权限修改
    public function actionEdit()
    {
        //接收权限名称
        $name = \Yii::$app->request->get('name');
        //创建rbac组件对象
        $rbac = \Yii::$app->authManager;
        //通过组件获取权限
        $row = $rbac->getPermission($name);
        //如果权限不存在,抛出提示
        if($row === null)
        {
            throw new NotFoundHttpException('权限不存在');

        }
        //创建表单模型
        $model = new Auth_item();
        //设置修改的时的权限场景
        $model->scenario = Auth_item::SCENARIO_Edit;
        //将权限分别赋值
        $model->name = $row->name;
        $model->old_name = $row->name;
        $model->description = $row->description;
        //接收修改后的数据
        if(\Yii::$app->request->post())
        {
            $model->load(\Yii::$app->request->post());
            //验证数据
            if($model->validate())
            {

                $row->name = $model->name;
                $row->description = $model->description;
                $rbac->update($name,$row);

                //跳转
                \Yii::$app->session->setFlash('success','修改成功');
                $this->redirect(['permission/index']);
            }


        }
        //将数据回显到表单
       return $this->render('edit',['model'=>$model]);
    }

    Public function behaviors()
    {
        Return
            [
                [
                    'class'=>AdminFilter::className(),
                    //'only'=>['add'],//指定执行的过虑器的操作,
                    //'except'=>['login'],//除了这些操作,都有效
                ]

            ];
    }
}