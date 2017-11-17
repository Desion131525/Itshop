<?php
namespace backend\controllers;
use backend\filters\AdminFilter;
use backend\models\Auth_item;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\Request;

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/9 0009
 * Time: 15:56
 */
class RoleController extends Controller
{
    //添加角色
    public function actionAdd()
    {
        //创建rbac对象
        $rbac = \Yii::$app->authManager;
        //显示添加表单
        $model = new Auth_item();
        $request = new Request();
        if($request->isPost)
        {
            //接收表单数据
            $model->load($request->post());
            //验证表单数据
            if ($model->validate())
            {
                //保存数据
                $role = $rbac->createRole($model->name);
                $role->description = $model->description;
                $rbac->add($role);
                //对多个权限进行遍历
                foreach ($model->permissions as $permissionName)
                {
                    //根据权限的名称获取权限对象
                    $permission = $rbac->getPermission($permissionName);
                    //给角色分配权限
                    $rbac->addChild($role,$permission);
                }
                //跳转
                \Yii::$app->session->setFlash('success','添加成功');
                $this->redirect(['role/index']);
            }
        }

        //获取权限
        $permissions = $rbac->getPermissions();
        $permissions = ArrayHelper::map($permissions,'name','description');

        //跳转
        return $this->render('add',['model'=>$model,'permissions'=>$permissions]);

    }

    //显示角色列表
    public function actionIndex()
    {
        //获取角色数据
        $rabc = \Yii::$app->authManager;
        $roles = $rabc ->getRoles();
        //渲染视图
        return $this->render('index',['roles'=>$roles]);
    }

    //角色删除
    public function actionDel()
    {
        //创建rbac组件对象
        $rbac = \Yii::$app->authManager;
        //接收数据
        $name = \Yii::$app->request->post('name');
        //根据name调用组件对象查询数据
        $role = $rbac->getRole($name);
        //删除
        $result = $rbac->remove($role);
        if ($result)
        {
            echo '1';
        }
    }

    //角色修改
    public function actionEdit()
    {
        //创建表单模型
        $model = new Auth_item();
        $request = new Request();
        //接收修改id
        $name = $request->get('name');
        //根据id获取角色数据
        $rbac = \Yii::$app->authManager;
        $row_role = $rbac->getRole($name);
        //根据角色id获取权限
        $row_per = $rbac->getPermissionsByRole($name);
        //将回显数据赋值给表单属性
        if($row_role)
        {
            $model->name = $row_role->name;
            $model->description= $row_role->description;
        }else{
            throw new HttpException(403,'该记录不存在');
            return false;
        }

        //获取权限数据
        $permissions = $rbac->getPermissions();
        $permissions = ArrayHelper::map($permissions,'name','description');
        //对权限数据进行遍历
        foreach ($row_per as $v)
        {
            $model->permissions[]= $v->name;

        }
        //接收修改后的数据
        if($request->isPost)
        {
            $model->load($request->post());
            $row_role->name =$model->name;
            $row_role->description =$model->description;
            //保存角色修改
            $result = $rbac->update($name,$row_role);
            //判断是否有修改权限
            if($result)
            {
                if($model->permissions)
                {
                    //删除原来角色权限的
                    $rbac->removeChildren($row_role);

                    //遍历接收到的权限
                    foreach ($model->permissions as $permissionName)
                    {
                        //根据权限的名称获取权限对象
                        $permission = $rbac->getPermission($permissionName);
                        //给角色分配权限
                        $rbac->addChild($row_role,$permission);

                    }
                }

            }else{

            }

            //跳转
            \Yii::$app->session->setFlash('success','修改成功');
            $this->redirect(['role/index']);
        }
        //将数据回显到修改表单
        return $this->render('edit',['model'=>$model,'permissions'=>$permissions]);
    }

   /* Public function behaviors()
    {
        Return
            [
                [
                    'class'=>AdminFilter::className(),
                    //'only'=>['add'],//指定执行的过虑器的操作,
                    //'except'=>['login'],//除了这些操作,都有效
                ]

            ];
    }*/
}