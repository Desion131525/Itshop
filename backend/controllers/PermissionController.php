<?php
namespace backend\controllers;
use backend\models\Auth_item;
use yii\web\Controller;
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
        //创建权限对象
        $permission = \Yii::$app->authManager;
        //显示添加表单
        $model = new Auth_item();
        $request = new Request();
        if($request->isPost)
        {
            //接收数据
            $model->load($request->post());
            //验证数据
            if($model->validate())
            {
                //保存数据
                $per1 = $permission->createPermission($model->name);
                $per1->description = $model->description;
                $permission->add($per1);
                //跳转
                \Yii::$app->session->setFlash('success','添加成功');
                $this->redirect('index');
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
        //创建表单模型
        $model = new Auth_item();
        //将权限分别赋值
        $model->name = $row->name;
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
            }
            //跳转
            \Yii::$app->session->setFlash('success','修改成功');
            $this->redirect('index');
        }
        //将数据回显到表单
       return $this->render('edit',['model'=>$model]);
    }
}