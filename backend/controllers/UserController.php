<?php
namespace backend\controllers;
use backend\filters\AdminFilter;
use backend\models\Auth_item;
use backend\models\Edit_passwordForm;
use backend\models\LoginForm;
use backend\models\User;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\Request;

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/7 0007
 * Time: 21:54
 */
class UserController extends Controller
{
    //显示管理员列表
    public function actionIndex()
    {

        //var_dump(\Yii::$app->user->identity);die;
        //实例化分页组件
        $pager = new Pagination();
        //查询数据
        $user = User::find();
        //每页显示条数据
        $pager->pageSize = 3;
        //总记录数据
        $pager->totalCount = $user->where(['status'=>['1','0']])->count();
        //设置偏移量
        $rows = $user->where(['status'=>['1','0']])->limit($pager->limit)->offset($pager->offset)->all();
        //分配数据
        return $this->render('index',['rows'=>$rows,'pager'=>$pager]);
    }

    //添加管理员
    public function actionAdd()
    {
        //显示管理员表单
        $model = new User();
        $role_model = new Auth_item();
        $request = new Request();

        if($request->isPost)
        {
            //接收表单数据
            $model->load($request->post());
            $role_model->load($request->post());
            //验证表单数据
            if($model->validate()){
                //保存数据
                $model->password_hash = \Yii::$app->security->generatePasswordHash($model->password_hash);
                $model->created_at = time();
                if($model->save())
                {
                    //对多个用户角色进行遍历
                    foreach ($role_model->role as $roleName) {

                        //获取角色对象
                        $rbac = \Yii::$app->authManager;
                        $role = $rbac->getRole($roleName);
                        //给用户分配角色
                        $rbac->assign($role,$model->id);

                    }

                    //跳转
                    \Yii::$app->session->setFlash('success','添加成功');
                    $this->redirect(["user/index"]);
                }

            }
        }

        //获取角色
        $rbac = \Yii::$app->authManager;
        $roles = $rbac->getRoles();
        $roles = ArrayHelper::map($roles,'name','description');
        return $this->render('add',['model'=>$model,'role_model'=>$role_model,'roles'=>$roles]);
    }

    //修改管理员
    public function actionEdit()
    {
        //接收id
        $request = new Request();
        $role_model = new Auth_item();
        $id = $request->get('id');
        $rows = User::findOne($id);
        //获取角色
        $rbac = \Yii::$app->authManager;
        $roles = $rbac->getRoles();
        $roles = ArrayHelper::map($roles,'name','description');
        $uer_roles = $rbac->getRolesByUser($id);
        //遍历用户角色
        foreach ($uer_roles as $uer_role)
        {
            $role_model->role = $uer_role->name;
        }
        if($request->post())
        {
            //接收修改数据
            $rows->load($request->post());
            $role_model->load($request->post());
            //验证数据
            if($rows->validate())
            {
                //保存
                $rows->updated_at = time();
                if(  $rows->save())
                {
                    //删除原来的用户角色
                    $rbac->revokeAll($id);
                    //对多个用户角色进行遍历
                    if($role_model->role)
                    {
                        foreach ($role_model->role as $roleName)
                        {

                            //获取角色对象
                            $role = $rbac->getRole($roleName);
                            //给用户分配角色
                            $rbac->assign($role,$id);

                        }
                    }

                    //跳转
                    \Yii::$app->session->setFlash('success','添加成功');
                    $this->redirect(["user/index"]);
                }

            }

        }

        //回显示表单
        return $this->render('edit',['rows'=>$rows,'role_model'=>$role_model,'roles'=>$roles]);
    }

    //>>4.删除用户
    public function actionDel()
    {
        //接收id
        $request = new Request();
        $id = $request->post('id');

        //根据id查询数据
        $rows = User::findOne($id);

        //删除数据
        $rows->status = -1;
        $result = $rows->save(false);

        //响应ajax
        if($result)
        {
            echo '1';
        }

    }

    //显示登陆表单
    public function actionLogin()
    {
        //创建表单模型
        $loginForm = new LoginForm();
        //接收表单数据
        $request = new Request();
        if($request->isPost)
        {
            $loginForm->load($request->post());
            //验证数据
            if ($loginForm->validate())
            {//var_dump($loginForm);die;
                if($loginForm->login($loginForm->cookie))
                {
                    //跳转
                    \Yii::$app->session->setFlash('success','登陆成功');
                    $this->redirect(['user/index']);
                }

            }
        }

        return $this->render('login',['loginForm'=>$loginForm]);

    }

    //注销
    public function actionLogout()
    {
        \Yii::$app->user->logout();
        return $this->redirect(['user/login']);
    }
    //密码修改
    public function actionEdit_password()
    {
        //显示修改密码表单
        $model = new Edit_passwordForm();
        $request = new Request();

        //验证数据
        if($request->post())
        {
            //接收数据
            $model->load($request->post());

            if($model->validate())
            {
                //验证旧密码
                $password_hash = \Yii::$app->user->identity->password_hash;

                $result = \Yii::$app->security->validatePassword($model->old_pwd,$password_hash);
                //旧密码正确再验证两次输入的新密码
                if($result)
                {
                    //新密码正确 更新到数据库
                    User::updateAll(
                        ['password_hash'=>\Yii::$app->security->generatePasswordHash($model->new_pwd)],
                            ['id'=>\Yii::$app->user->id]

                    );
                    //并跳转到登陆页面重新登陆
                    \Yii::$app->user->logout();
                    \Yii::$app->session->setFlash('success','密码修改成功,请重新登陆');
                    return $this->redirect(['user/login']);
                }else{
                    $model->addError('old_pwd','旧密码错误');
                }
            }

        }
        //保存修改数据
       return $this->render('edit_password',['model'=>$model]);
    }

    Public function behaviors()
    {
        Return
            [
                [
                    'class'=>AdminFilter::className(),
                    //'only'=>['goods/add'],//指定执行的过虑器的操作,
                    'except'=>['login'],//除了这些操作,都有效
                ]

            ];
    }
}